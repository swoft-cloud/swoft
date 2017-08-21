<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/7/14
 * Time: 下午8:03
 */

namespace swoft\web;

/**
 * Class Router- this is object version
 * @package swoft\web
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
    private $routeCounter = 0;

    /**
     * some available patterns regex
     * $router->get('/user/{num}', 'handler');
     * @var array
     */
    private static $globalTokens = [
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
     * There are last route caches
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

    /** @var DispatcherInterface */
    private $dispatcher;

    /** @var bool ignore last '/' char. If is True, will clear last '/'. */
    public $ignoreLastSep = false;

    /** @var int */
    public $tmpCacheNumber = 0;

    /**
     * match all request.
     *  1. If is a valid URI path, will match all request uri to the path.
     *  2. If is a closure, will match all request then call it
     * eg: '/site/maintenance' or `function () { echo 'System Maintaining ... ...'; }`
     * @var mixed
     */
    public $matchAll;

    /** @var bool auto route match like yii framework. If is True, will auto find the handler controller file. */
    public $autoRoute = false;

    /** @var string The default controllers namespace, is valid when `$autoRoute = true`. eg: 'app\\controllers' */
    public $controllerNamespace = '';

    /** @var string controller suffix, is valid when `$autoRoute = true`. eg: 'Controller' */
    public $controllerSuffix = '';

    /**
     * object creator.
     * @param array $config
     * @return self
     * @throws \LogicException
     */
    public static function make(array $config = [])
    {
        return new static($config);
    }

    /**
     * object constructor.
     * @param array $config
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
     * @param string $method
     * @param array $args
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
     * Create a route group with a common prefix.
     * All routes created in the passed callback will have the given group prefix prepended.
     * @from package 'nikic/fast-route'
     * @param string $prefix
     * @param \Closure $callback
     * @param array $opts
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
     * @param string|array $method The match request method.
     * e.g
     *  string: 'get'
     *  array: ['get','post']
     * @param string $route The route path string. eg: '/user/login'
     * @param callable|string $handler
     * @param array $opts some option data
     * [
     *     'tokens' => [ 'id' => '[0-9]+', ],
     *     'domains'  => [ 'a-domain.com', '*.b-domain.com'],
     *     'schema' => 'https',
     * ]
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
            'tokens' => null,
            'domains' => null,
            'schema' => null, // ['http','https'],
            // route event. custom design ...
            // 'enter' => null,
            // 'leave' => null,
        ], $this->currentGroupOption, $opts);

        $conf = [
            'method' => $method,
            'handler' => $handler,
            'option' => $opts,
        ];

        // no dynamic param tokens
        if (strpos($route, '{') === false) {
            $this->staticRoutes[$route][$method] = $conf;

            return $this;
        }

        // have dynamic param tokens

        // replace token name To pattern regex
        list($first, $conf) = static::parseRoute(
            $route,
            static::getAvailableTokens(self::$globalTokens, $opts['tokens']),
            $conf
        );

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
     * @param string $route
     * @param array $tokens
     * @param array $conf
     * @return string
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
     * find the matched route info for the given request uri path
     * @param string $method
     * @param string $path
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

                    // cache latest $number routes.
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

                // cache last $number routes.
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
            return [$path, [
                'path' => $path,
                'handler' => $handler,
            ]];
        }

        // oo ... not found
        return false;
    }

    /**
     * handle auto route match, when config `'autoRoute' => true`
     * @param string $path The route path
     * @param string $controllerNamespace controller namespace. eg: 'app\\controllers'
     * @param string $controllerSuffix controller suffix. eg: 'Controller'
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
/// route callback handler dispatch
//////////////////////////////////////////////////////////////////////

    /**
     * Runs the callback for the given request
     * @param DispatcherInterface $dispatcher
     * @return mixed
     */
    public function dispatch(DispatcherInterface $dispatcher = null)
    {
        if ($dispatcher) {
            $this->dispatcher = $dispatcher;
        } elseif (!$this->dispatcher) {
            $this->dispatcher = new Dispatcher;
        }

        return $this->dispatcher->setMatcher(function ($path, $method) {
            return $this->match($path, $method);
        })->dispatch();
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
     * @param $str
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
     * @return DispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param DispatcherInterface $dispatcher
     * @return $this
     */
    public function setDispatcher(DispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }
}
