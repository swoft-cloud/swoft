<?php

namespace Swoft\Web;

use Swoft\App;
use Swoft\Bean\Collector;

/**
 * 路由组件
 *
 * @uses      Router
 * @version   2017年07月14日
 * @author    inhere <in.798@qq.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 *
 * @method get(string $route, mixed $handler, array $opts = [])
 * @method post(string $route, mixed $handler, array $opts = [])
 * @method put(string $route, mixed $handler, array $opts = [])
 * @method delete(string $route, mixed $handler, array $opts = [])
 * @method options(string $route, mixed $handler, array $opts = [])
 * @method head(string $route, mixed $handler, array $opts = [])
 * @method search(string $route, mixed $handler, array $opts = [])
 * @method trace(string $route, mixed $handler, array $opts = [])
 * @method any(string $route, mixed $handler, array $opts = [])
 */
class Router implements RouterInterface
{
    /** @var int 已注册的路由数 */
    private $routeCounter = 0;

    /**
     * 内置的一些匹配参数
     * $router->get('/user/{num}', 'handler');
     *
     * @var array
     */
    private static $globalTokens
        = [
            'any' => '[^/]+',   // match any except '/'
            'num' => '[0-9]+',  // match a number
            'act' => '[a-zA-Z][\w-]+', // match a action name
            'all' => '.*'
        ];

    /** @var string */
    private $currentGroupPrefix;

    /** @var array */
    private $currentGroupOption;

    /** @var bool */
    private $initialized = false;

    /**
     * static Routes - no dynamic argument match
     * 整个路由 path 都是静态字符串 e.g. '/user/login'
     *
     * @var array
     * [
     *     '/user/login' => [
     *         'GET' => [
     *              'handler' => 'handler',
     *              'option' => null,
     *          ],
     *         'POST' => [
     *              'handler' => 'handler',
     *              'option' => null,
     *          ],
     *          ...
     *      ]
     * ]
     */
    private $staticRoutes = [];

    /**
     * regular Routes - have dynamic arguments, but the first node is normal.
     * 第一节是个静态字符串，称之为有规律的动态路由。按第一节的信息进行存储
     * e.g '/hello[/{name}]' '/user/{id}'
     *
     * @var array[]
     * [
     *     // 先用第一个字符作为 key，进行分组
     *     'a' => [
     *          // 第一节只有一个字符, 使用关键字'_NO_'为 key 进行分组
     *         '_NO_' => [
     *              [
     *                  'first' => '/a',
     *                  'regex' => '/a/(\w+)',
     *                  'method' => 'GET',
     *                  'handler' => 'handler',
     *                  'option' => null,
     *              ]
     *          ],
     *          // 第一节有多个字符, 使用第二个字符 为 key 进行分组
     *         'd' => [
     *              [
     *                  'first' => '/add',
     *                  'regex' => '/add/(\w+)',
     *                  'method' => 'GET',
     *                  'handler' => 'handler',
     *                  'option' => null,
     *              ],
     *              ... ...
     *          ],
     *          ... ...
     *      ],
     *     'b' => [
     *        'l' => [
     *              [
     *                  'first' => '/blog',
     *                  'regex' => '/blog/(\w+)',
     *                  'method' => 'GET',
     *                  'handler' => 'handler',
     *                  'option' => null,
     *              ],
     *              ... ...
     *          ],
     *          ... ...
     *     ],
     * ]
     */
    private $regularRoutes = [];

    /**
     * vague Routes - have dynamic arguments,but the first node is exists regex.
     * 第一节就包含了正则匹配，称之为无规律/模糊的动态路由
     * e.g '/{some}/{some2}'
     *
     * @var array
     * [
     *     [
     *         'regex' => '/(\w+)/some',
     *         'method' => 'GET',
     *         'handler' => 'handler',
     *         'option' => null,
     *     ],
     *      ... ...
     * ]
     */
    private $vagueRoutes = [];

    /**
     * 最近的路由缓存数组(最大数量由 {@see $tmpCacheNumber}控制)
     *
     * @var array
     * [
     *     'path' => [
     *         'GET' => [
     *              'handler' => 'handler',
     *              'option' => null,
     *          ],
     *         'POST' => [
     *              'handler' => 'handler',
     *              'option' => null,
     *          ],
     *         ... ...
     *     ]
     * ]
     */
    private $routeCaches = [];

    //////////////////////////////////////////////////////////////////////
    /// router config
    //////////////////////////////////////////////////////////////////////

    /** @var bool 是否忽略最后的URl斜线 '/'. */
    public $ignoreLastSep = false;

    /** @var int 动态路由缓存数 */
    public $tmpCacheNumber = 0;

    /**
     * 配置此项可用于拦截所有请求。 （例如网站维护时）
     *  1. 是个URI字符串， 直接用于解析路由
     *  2. 是个闭包回调，直接调用
     * eg: '/site/maintenance' OR `function () { '系统维护中 :)'; }`
     *
     * @var mixed
     */
    public $matchAll;

    /** @var bool 自动匹配路由(像yii框架)。 如果为True，将自动查找控制器文件。 */
    public $autoRoute = false;

    /** @var string 默认的控制器命名空间, 当开启自动匹配路由时有效. eg: 'App\\Controllers' */
    public $controllerNamespace = '';

    /** @var string 控制器后缀, 当开启自动匹配路由时有效. eg: 'Controller' */
    public $controllerSuffix = '';

    /**
     * service后缀
     *
     * @var string
     */
    private $serviceSuffix = 'Service';

    /**
     * service路由
     *
     * @var array
     */
    private $serviceRoutes = [];

    /**
     * object creator.
     *
     * @param array $config
     *
     * @return self
     * @throws \LogicException
     */
    public static function make(array $config = [])
    {
        return new static($config);
    }

    /**
     * object constructor.
     *
     * @param array $config
     *
     * @throws \LogicException
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);

        $this->currentGroupPrefix = '';
        $this->currentGroupOption = [];
    }

    /**
     * @param array $config
     *
     * @throws \LogicException
     */
    public function setConfig(array $config)
    {
        if ($this->initialized) {
            throw new \LogicException('Routing has been added, and configuration is not allowed!');
        }

        foreach ($config as $name => $value) {
            if (property_exists($this, $name)) {
                $this->$name = $value;
            }
        }
    }

    //////////////////////////////////////////////////////////////////////
    /// route collection
    //////////////////////////////////////////////////////////////////////

    /**
     * Defines a route callback and method
     *
     * @param string $method
     * @param array  $args
     *
     * @return Router
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function __call($method, array $args)
    {
        if (count($args) < 2) {
            throw new \InvalidArgumentException("The method [$method] parameters is missing.");
        }

        return $this->map($method, $args[0], $args[1], $args[2] ?? []);
    }

    /**
     * 添加路由组
     *
     * @param string   $prefix
     * @param \Closure $callback
     * @param array    $opts
     */
    public function group($prefix, \Closure $callback, array $opts = [])
    {
        $prefix = '/' . trim($prefix, '/');
        $previousGroupPrefix = $this->currentGroupPrefix;
        $this->currentGroupPrefix = $previousGroupPrefix . $prefix;

        $previousGroupOption = $this->currentGroupOption;
        $this->currentGroupOption = $opts;

        $callback($this);

        $this->currentGroupPrefix = $previousGroupPrefix;
        $this->currentGroupOption = $previousGroupOption;
    }

    /**
     * @param string|array    $method  匹配请求方法
     *                                 e.g
     *                                 string: 'get'
     *                                 array: ['get','post']
     * @param string          $route   路由PATH. eg: '/user/login'
     * @param callable|string $handler 路由处理器
     * @param array           $opts    选项数据
     *                                 [
     *                                 'tokens' => [ 'id' => '[0-9]+', ],
     *                                 'domains'  => [ 'a-domain.com', '*.b-domain.com'],
     *                                 'schema' => 'https',
     *                                 ]
     *
     * @return static
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function map($method, $route, $handler, array $opts = [])
    {
        if (!$this->initialized) {
            $this->initialized = true;
        }

        // array
        if (is_array($method)) {
            foreach ((array)$method as $m) {
                $this->map($m, $route, $handler, $opts);
            }

            return $this;
        }

        // string - register route and callback

        $method = strtoupper($method);
        $hasPrefix = (bool)$this->currentGroupPrefix;

        // validate arguments
        static::validateArguments($method, $handler);

        if ($route = trim($route)) {
            // always add '/' prefix.
            $route = $route{0} === '/' ? $route : '/' . $route;
        } elseif (!$hasPrefix) {
            $route = '/';
        }

        $route = $this->currentGroupPrefix . $route;

        // setting 'ignoreLastSep'
        if ($route !== '/' && $this->ignoreLastSep) {
            $route = rtrim($route, '/');
        }

        $this->routeCounter++;
        $opts = array_replace([
            'tokens'  => null,
            'domains' => null,
            'schema'  => null, // ['http','https'],
            // route Event. custom design ...
            // 'enter' => null,
            // 'leave' => null,
        ], $this->currentGroupOption, $opts);

        $conf = [
            'method'  => $method,
            'handler' => $handler,
            'option'  => $opts,
        ];

        // no dynamic param tokens
        if (strpos($route, '{') === false) {
            $this->staticRoutes[$route][$method] = $conf;

            return $this;
        }

        // have dynamic param tokens

        // replace token name To pattern regex
        list($first, $conf) = static::parseRoute($route, static::getAvailableTokens(self::$globalTokens, $opts['tokens']), $conf);

        // route string is regular
        if ($first) {
            $twoLevelKey = $first{1} ?? self::DEFAULT_TWO_LEVEL_KEY;
            $this->regularRoutes[$first{0}][$twoLevelKey][] = $conf;
        } else {
            $this->vagueRoutes[] = $conf;
        }

        return $this;
    }

    /**
     * @param $method
     * @param $handler
     *
     * @throws \InvalidArgumentException
     */
    public static function validateArguments($method, $handler)
    {
        $supStr = implode('|', self::SUPPORTED_METHODS);

        if (false === strpos('|' . $supStr . '|', '|' . $method . '|')) {
            throw new \InvalidArgumentException("The method [$method] is not supported, Allow: $supStr");
        }

        if (!$handler || (!is_string($handler) && !is_object($handler))) {
            throw new \InvalidArgumentException('The route handler is not empty and type only allow: string,object');
        }

        if (is_object($handler) && !is_callable($handler)) {
            throw new \InvalidArgumentException('The route object handler must be is callable');
        }
    }

    /**
     * 解析路由PATH
     *
     * @param string $route
     * @param array  $tokens
     * @param array  $conf
     *
     * @return array
     * @throws \LogicException
     */
    public static function parseRoute($route, array $tokens, array $conf)
    {
        $first = null;
        $tmp = $route;

        // 解析可选参数位
        // '/hello[/{name}]'      match: /hello/tom   /hello
        // '/my[/{name}[/{age}]]' match: /my/tom/78  /my/tom
        if (false !== strpos($route, ']')) {
            $withoutClosingOptionals = rtrim($route, ']');
            $optionalNum = strlen($route) - strlen($withoutClosingOptionals);

            if ($optionalNum !== substr_count($withoutClosingOptionals, '[')) {
                throw new \LogicException('Optional segments can only occur at the end of a route');
            }

            // '/hello[/{name}]' -> '/hello(?:/{name})?'
            $route = str_replace(['[', ']'], ['(?:', ')?'], $route);
        }

        // 解析参数，替换为对应的 正则
        if (preg_match_all('#\{([a-zA-Z_][a-zA-Z0-9_-]*)\}#', $route, $m)) {
            /** @var array[] $m */
            $replacePairs = [];

            foreach ($m[1] as $name) {
                $key = '{' . $name . '}';
                // 匹配定义的 token  , 未匹配到的使用默认 self::DEFAULT_REGEX
                $regex = $tokens[$name] ?? self::DEFAULT_REGEX;

                // 将匹配结果命名 (?P<arg1>[^/]+)
                // $replacePairs[$key] = '(?P<' . $name . '>' . $pattern . ')';
                $replacePairs[$key] = '(' . $regex . ')';
            }

            $route = strtr($route, $replacePairs);
        }

        // 分析路由字符串是否是有规律的

        // e.g '/hello[/{name}]' first: 'hello', '/user/{id}' first: 'user', '/a/{post}' first: 'a'
        // first node is a normal string
        if (preg_match('#^/([\w-]+)#', $tmp, $ms)) {
            $first = $ms[1];
            $conf = [
                    'first' => '/' . $first,
                    'regex' => '#^' . $route . '$#',
                ] + $conf;
            // first node contain regex param '/{some}/{some2}'
        } else {
            $conf['regex'] = '#^' . $route . '$#';
        }

        return [$first, $conf];
    }

    /**
     * @param array $tokens
     * @param array $tmpTokens
     *
     * @return array
     */
    public static function getAvailableTokens(array $tokens, $tmpTokens)
    {
        if ($tmpTokens) {
            foreach ($tmpTokens as $name => $pattern) {
                $key = trim($name, '{}');
                $tokens[$key] = $pattern;
            }
        }

        return $tokens;
    }

    //////////////////////////////////////////////////////////////////////
    /// route match
    //////////////////////////////////////////////////////////////////////

    /**
     * 找到给定请求uri路径的匹配路由信息
     *
     * @param string $method
     * @param string $path
     *
     * @return mixed
     */
    public function match($path, $method)
    {
        // if enable 'matchAll'
        if ($matchAll = $this->matchAll) {
            if (is_string($matchAll) && $matchAll{0} === '/') {
                $path = $matchAll;
            } elseif (is_callable($matchAll)) {
                return [$path, $matchAll];
            }
        }

        // clear '//', '///' => '/'
        $path = rawurldecode(preg_replace('#\/\/+#', '/', $path));
        $method = strtoupper($method);
        $number = (int)$this->tmpCacheNumber;

        // setting 'ignoreLastSep'
        if ($path !== '/' && $this->ignoreLastSep) {
            $path = rtrim($path, '/');
        }

        // find in route caches.
        if ($this->routeCaches && isset($this->routeCaches[$path])) {
            if (isset($this->routeCaches[$path][$method])) {
                return [$path, $this->routeCaches[$path][$method]];
            }

            if (isset($this->routeCaches[$path][self::ANY_METHOD])) {
                return [$path, $this->routeCaches[$path][self::ANY_METHOD]];
            }
        }

        // is a static route path
        if ($this->staticRoutes && isset($this->staticRoutes[$path])) {
            if (isset($this->staticRoutes[$path][$method])) {
                return [$path, $this->staticRoutes[$path][$method]];
            }

            if (isset($this->staticRoutes[$path][self::ANY_METHOD])) {
                return [$path, $this->staticRoutes[$path][self::ANY_METHOD]];
            }
        }

        $tmp = trim($path, '/'); // clear first '/'

        // is a regular dynamic route(the first char is 1th level index key).
        if ($this->regularRoutes && isset($this->regularRoutes[$tmp{0}])) {
            $twoLevelArr = $this->regularRoutes[$tmp{0}];
            $twoLevelKey = $tmp{1} ?? self::DEFAULT_TWO_LEVEL_KEY;

            // not found
            if (!isset($twoLevelArr[$twoLevelKey])) {
                return false;
            }

            foreach ((array)$twoLevelArr[$twoLevelKey] as $conf) {
                if (0 === strpos($path, $conf['first']) && preg_match($conf['regex'], $path, $matches)) {
                    // method not allowed
                    if ($method !== $conf['method'] && self::ANY_METHOD !== $conf['method']) {
                        return false;
                    }

                    $conf['matches'] = $matches;

                    // Cache latest $number routes.
                    if ($number > 0) {
                        if (count($this->routeCaches) === $number) {
                            array_shift($this->routeCaches);
                        }

                        $this->routeCaches[$path][$conf['method']] = $conf;
                    }

                    return [$path, $conf];
                }
            }
        }

        // is a irregular dynamic route
        foreach ($this->vagueRoutes as $conf) {
            if (preg_match($conf['regex'], $path, $matches)) {
                // method not allowed
                if ($method !== $conf['method'] && self::ANY_METHOD !== $conf['method']) {
                    return false;
                }

                $conf['matches'] = $matches;

                // Cache last $number routes.
                if ($number > 0) {
                    if (count($this->routeCaches) === $number) {
                        array_shift($this->routeCaches);
                    }

                    $this->routeCaches[$path][$conf['method']] = $conf;
                }

                return [$path, $conf];
            }
        }

        // handle Auto Route
        if ($this->autoRoute && ($handler = self::matchAutoRoute($path, $this->controllerNamespace, $this->controllerSuffix))) {
            return [
                $path,
                [
                    'path'    => $path,
                    'handler' => $handler,
                ]
            ];
        }

        // oo ... not found
        return false;
    }

    /**
     * 自动路由的匹配处理。(当配置了 `'autoRoute' => true`)
     *
     * @param string $path                The route path
     * @param string $controllerNamespace controller namespace. eg: 'App\\Controllers'
     * @param string $controllerSuffix    controller suffix. eg: 'AutoController'
     *
     * @return bool|callable
     */
    public static function matchAutoRoute($path, $controllerNamespace, $controllerSuffix)
    {
        $cnp = $controllerNamespace;
        $sfx = $controllerSuffix;
        $tmp = trim($path, '/- ');

        // one node. eg: 'home'
        if (!strpos($tmp, '/')) {
            $tmp = self::convertNodeStr($tmp);
            $class = "$cnp\\" . ucfirst($tmp) . $sfx;

            return class_exists($class) ? $class : false;
        }

        $ary = array_map([self::class, 'convertNodeStr'], explode('/', $tmp));
        $cnt = count($ary);

        // two nodes. eg: 'home/test' 'admin/user'
        if ($cnt === 2) {
            list($n1, $n2) = $ary;

            // last node is an controller class name. eg: 'admin/user'
            $class = "$cnp\\$n1\\" . ucfirst($n2) . $sfx;

            if (class_exists($class)) {
                return $class;
            }

            // first node is an controller class name, second node is a action name,
            $class = "$cnp\\" . ucfirst($n1) . $sfx;

            return class_exists($class) ? "$class@$n2" : false;
        }

        // max allow 5 nodes
        if ($cnt > 5) {
            return false;
        }

        // last node is an controller class name
        $n2 = array_pop($ary);
        $class = sprintf('%s\\%s\\%s', $cnp, implode('\\', $ary), ucfirst($n2) . $sfx);

        if (class_exists($class)) {
            return $class;
        }

        // last second is an controller class name, last node is a action name,
        $n1 = array_pop($ary);
        $class = sprintf('%s\\%s\\%s', $cnp, implode('\\', $ary), ucfirst($n1) . $sfx);

        return class_exists($class) ? "$class@$n2" : false;
    }

    //////////////////////////////////////////////////////////////////////
    /// helper methods
    //////////////////////////////////////////////////////////////////////

    /**
     * @param array $tokens
     */
    public function addTokens(array $tokens)
    {
        foreach ($tokens as $name => $pattern) {
            $this->addToken($name, $pattern);
        }
    }

    /**
     * @param $name
     * @param $pattern
     */
    public function addToken($name, $pattern)
    {
        $name = trim($name, '{} ');
        self::$globalTokens[$name] = $pattern;
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->routeCounter;
    }

    /**
     * convert 'first-second' to 'firstSecond'
     *
     * @param $str
     *
     * @return mixed|string
     */
    public static function convertNodeStr($str)
    {
        $str = trim($str, '-');

        // convert 'first-second' to 'firstSecond'
        if (strpos($str, '-')) {
            $str = preg_replace_callback('/-+([a-z])/', function ($c) {
                return strtoupper($c[1]);
            }, trim($str, '- '));
        }

        return $str;
    }

    /**
     * @param array $staticRoutes
     */
    public function setStaticRoutes(array $staticRoutes)
    {
        $this->staticRoutes = $staticRoutes;
    }

    /**
     * @return array
     */
    public function getStaticRoutes()
    {
        return $this->staticRoutes;
    }

    /**
     * @param \array[] $regularRoutes
     */
    public function setRegularRoutes(array $regularRoutes)
    {
        $this->regularRoutes = $regularRoutes;
    }

    /**
     * @return \array[]
     */
    public function getRegularRoutes()
    {
        return $this->regularRoutes;
    }

    /**
     * @param array $vagueRoutes
     */
    public function setVagueRoutes($vagueRoutes)
    {
        $this->vagueRoutes = $vagueRoutes;
    }

    /**
     * @return array
     */
    public function getVagueRoutes()
    {
        return $this->vagueRoutes;
    }

    /**
     * @return array
     */
    public function getRouteCaches()
    {
        return $this->routeCaches;
    }

    /**
     * @return array
     */
    public function getGlobalTokens()
    {
        return self::$globalTokens;
    }

    /**
     * @return array
     */
    public static function getSupportedMethods()
    {
        return self::SUPPORTED_METHODS;
    }

    /**
     * 自动注册路由
     *
     * @param array $requestMapping
     */
    public function registerRoutes(array $requestMapping)
    {
        foreach ($requestMapping as $className => $mapping) {
            if (!isset($mapping['prefix'], $mapping['routes'])) {
                continue;
            }

            // 控制器prefix
            $controllerPrefix = $mapping['prefix'];
            $controllerPrefix = $this->getControllerPrefix($controllerPrefix, $className);
            $routes = $mapping['routes'];

            /* @var Controller $controller */
            $controller = App::getBean($className);
            $actionPrefix = $controller->getActionPrefix();

            // 注册控制器对应的一组路由
            $this->registerRoute($className, $routes, $controllerPrefix, $actionPrefix);
        }
    }

    /**
     * 自动注册service路由
     *
     * @param array $serviceMapping
     */
    public function registerServices(array $serviceMapping)
    {
        foreach ($serviceMapping as $className => $mapping) {
            $prefix = $mapping['name'];
            $routes = $mapping['routes'];
            $prefix = $this->getPrefix($this->serviceSuffix, $prefix, $className);

            $this->registerService($className, $routes, $prefix);
        }
    }

    /**
     * 匹配路由
     *
     * @param $func
     *
     * @return mixed
     */
    public function serviceMatch($func)
    {
        if(!isset($func)){
            throw new \InvalidArgumentException('service调用的函数不存在，func=' . $func);
        }

        return $this->serviceRoutes[$func];
    }

    /**
     * 注册一个路由
     *
     * @param string $className
     * @param array  $routes
     * @param string $prefix
     */
    private function registerService(string $className, array $routes, string $prefix)
    {
        foreach ($routes as $route) {
            $mappedName = $route['mappedName'];
            $methodName = $route['methodName'];
            if (empty($mappedName)) {
                $mappedName = $methodName;
            }

            $serviceKey = "$prefix::$mappedName";
            $this->serviceRoutes[$serviceKey] = [$className, $methodName];
        }
    }

    /**
     * 注册路由
     *
     * @param string $className        类名
     * @param array  $routes           控制器对应的路由组
     * @param string $controllerPrefix 控制器prefix
     * @param string $actionPrefix     action prefix
     */
    private function registerRoute(string $className, array $routes, string $controllerPrefix, string $actionPrefix)
    {
        // 循环注册路由
        foreach ($routes as $route) {
            if (!isset($route['route'], $route['method'], $route['action'])) {
                continue;
            }
            $mapRoute = $route['route'];
            $method = $route['method'];
            $action = $route['action'];

            // 解析注入action名称
            $actionMethod = $this->getActionMethod($actionPrefix, $action);
            $mapRoute = empty($mapRoute) ? $actionMethod : $mapRoute;

            // '/'开头的路由是一个单独的路由，未使用'/'需要和控制器组拼成一个路由
            $uri = strpos($mapRoute, '/') === 0 ? $mapRoute : $controllerPrefix . '/' . $mapRoute;
            $handler = $className . '@' . $actionMethod;

            // 注入路由规则
            $this->map($method, $uri, $handler);
        }
    }

    /**
     * 获取action方法
     *
     * @param string $actionPrefix 配置的默认action前缀
     * @param string $action       action方法
     *
     * @return string
     */
    private function getActionMethod(string $actionPrefix, string $action)
    {
        $action = str_replace($actionPrefix, '', $action);
        $action = lcfirst($action);
        return $action;
    }

    /**
     * 获取控制器prefix
     *
     * @param string $controllerPrefix 注解控制器prefix
     * @param string $className        控制器类名
     *
     * @return string
     */
    private function getControllerPrefix(string $controllerPrefix, string $className)
    {
        // 注解注入不为空，直接返回prefix
        if (!empty($controllerPrefix)) {
            return $controllerPrefix;
        }

        // 注解注入为空，解析控制器prefix
        $reg = '/^.*\\\(\w+)Controller$/';
        $prefix = '';

        if ($result = preg_match($reg, $className, $match)) {
            $prefix = '/' . lcfirst($match[1]);
        }

        return $prefix;
    }

    /**
     * 获取类前缀
     *
     * @param string $suffix
     * @param string $prefix
     * @param string $className
     *
     * @return string
     */
    private function getPrefix(string $suffix, string $prefix, string $className)
    {
        // 注解注入不为空，直接返回prefix
        if (!empty($prefix)) {
            return $prefix;
        }

        // 注解注入为空，解析控制器prefix
        $reg = '/^.*\\\(\w+)' . $suffix . '$/';
        $prefix = '';

        if ($result = preg_match($reg, $className, $match)) {
            $prefix = ucfirst($match[1]);
        }

        return $prefix;
    }
}
