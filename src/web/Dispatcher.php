<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/7/14
 * Time: 下午8:03
 */

namespace swoft\web;

/**
 * Class Dispatcher
 * @package swoft\web
 */
class Dispatcher implements DispatcherInterface
{
    const MATCH_FAV_ICO = '/favicon.ico';

    /**
     * event handlers
     * @var array
     */
    private static $events = [];

    /**
     * some setting for self
     * @var array
     */
    private $config = [
        // IFilter the `/favicon.ico` request.
        'filterFavicon' => false,

        // default action method name
        'defaultAction' => 'index',

        // enable dynamic action.
        // e.g
        // if set True;
        //  $router->any('/demo/{act}', app\controllers\Demo::class);
        //  you access '/demo/test' will call 'app\controllers\Demo::test()'
        'dynamicAction' => false,

        // action executor. will auto call controller's executor method to run all action.
        // e.g: 'actionExecutor' => 'run'`
        //  $router->any('/demo/{act}', app\controllers\Demo::class);
        //  you access `/demo/test` will call `app\controllers\Demo::run('test')`
        'actionExecutor' => '', // 'run'
    ];

    private $initialized;

    /**
     * object creator.
     * @param \Closure $matcher
     * @param array $config
     * @return self
     * @throws \LogicException
     */
    public static function make(array $config = [], \Closure $matcher = null)
    {
        return new self($config, $matcher);
    }

    /**
     * object constructor.
     * @param \Closure $matcher
     * @param array $config
     * @throws \LogicException
     */
    public function __construct(array $config = [], \Closure $matcher = null)
    {
        $this->initialized = false;
        $this->setConfig($config);

        if ($matcher) {
            $this->setMatcher($matcher);
        }
    }

    /**
     * @param array $config
     * @throws \LogicException
     */
    public function setConfig(array $config)
    {
        if ($this->initialized) {
            throw new \LogicException('Has already started to distributed routing, and configuration is not allowed!');
        }

        foreach ($config as $name => $value) {
            if (isset($this->config[$name])) {
                $this->config[$name] = $value;
            }
        }
    }

//////////////////////////////////////////////////////////////////////
/// route callback handler dispatch
//////////////////////////////////////////////////////////////////////

    /** @var \Closure */
    private $matcher;

    /**
     * Runs the callback for the given request
     * @param string $path
     * @param null|string $method
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function dispatch($path = null, $method = null)
    {
        $this->initialized = true;
        $path = $path ?: parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // if 'filterFavicon' setting is TRUE
        if ($path === self::MATCH_FAV_ICO && $this->config['filterFavicon']) {
            return null;
        }

        $method = $method ?: $_SERVER['REQUEST_METHOD'];
        $matcher = $this->matcher;

        if (!$info = $matcher($path, $method)) {
            return $this->handleNotFound($path);
        }

        list($path, $route) = $info;

        $result = null;
        $options = isset($route['option']) ? $route['option'] : [];
        unset($route['option']);

        // trigger route found event
        $this->fire(self::ON_FOUND, [$path, $route]);

        // schema,domains ... metadata validate
        if (false === $this->validateMetadata($route)) {
            return $result;
        }

        // fire enter event
        if (isset($options['enter']) && false === $this->callRouteEvent($options['enter'], $path)) {
            return $result;
        }

        $handler = $route['handler'];
        $matches = isset($route['matches']) ? $route['matches'] : null;

        try {
            // trigger route exec_start event
            $this->fire(self::ON_EXEC_START, [$path, $route]);

            $result = $this->callMatchedRouteHandler($path, $handler, $matches);

            // fire leave event
            if (isset($options['leave'])) {
                $this->callRouteEvent($options['leave'], $path);
            }

            // trigger route exec_end event
            $this->fire(self::ON_EXEC_END, [$path, $route]);
        } catch (\Exception $e) {
            // trigger route exec_error event
            $this->fire(self::ON_EXEC_ERROR, [$e, $path, $route]);
        } catch (\Throwable $e) {
            // trigger route exec_error event
            $this->fire(self::ON_EXEC_ERROR, [$e, $path, $route]);
        }

        return $result;
    }

    /**
     * @param array $route
     * [
     *     'domains'  => [ 'a-domain.com', '*.b-domain.com'],
     *     'schema' => 'https',
     * ]
     */
    public function validateMetadata(array $route)
    {
        // validate Schema

        // validate validateDomains
        // $serverName = $_SERVER['SERVER_NAME'];

    }

    /**
     * @param mixed $handler
     * string - func name, class name
     * array - [class, method]
     * object - Closure, Object
     * @param string $path
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function callRouteEvent($handler, $path)
    {
        if (!$handler) {
            return true;
        }

        if (is_array($handler)) {
            return call_user_func($handler, $path);
        }

        if (is_string($handler)) {
            if (function_exists($handler)) {
                return $handler($path);
            }

            if (class_exists($handler) && method_exists($handler, '__invoke')) {
                $cb = new $handler;

                return $cb($path);
            }

            throw new \InvalidArgumentException('route event handler is not callable!');
        }

        // a \Closure or Object implement '__invoke'
        if (is_object($handler) && method_exists($handler, '__invoke')) {
            return $handler($path);
        }

        throw new \InvalidArgumentException('route event handler is not callable!');
    }

    /**
     * @param string $path Request uri path
     * @param bool $actionNotExist
     *  True: The `$path` is matched success, but action not exist on route parser
     *  False: The `$path` is matched fail
     * @return bool|mixed
     */
    private function handleNotFound($path, $actionNotExist = false)
    {
        // Run the 'notFound' callback if the route was not found
        if (!isset(self::$events[self::ON_NOT_FOUND])) {
            $notFoundHandler = function ($path, $actionNotExist) {
                 header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
                 echo "<h1 style='width: 60%; margin: 5% auto;'>:( 404<br>Page Not Found <code style='font-weight: normal;'>$path</code></h1>";
            };

            $this->on(self::ON_NOT_FOUND, $notFoundHandler);
        } else {
            $notFoundHandler = self::$events[self::ON_NOT_FOUND];

            // is a route path. like '/site/notFound'
            if (is_string($notFoundHandler) && '/' === $notFoundHandler{0}) {
                $_GET['_src_path'] = $path;

                unset(self::$events[self::ON_NOT_FOUND]);
                return $this->dispatch($notFoundHandler);
            }
        }

        // trigger notFound event
        return is_array($notFoundHandler) ?
            call_user_func($notFoundHandler, $path, $actionNotExist) :
            $notFoundHandler($path, $actionNotExist);
    }

    /**
     * the default matched route parser.
     * @param string $path The route path
     * @param callable $handler The route path handler
     * @param array $matches Matched param from path
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    private function callMatchedRouteHandler($path, $handler, array $matches = null)
    {
        // Remove $matches[0] as [1] is the first parameter.
        if ($matches) {
            array_shift($matches);
            $matches = array_values($matches);
        }

        // is a \Closure or a callable object
        if (is_object($handler)) {
            return $matches ? $handler(...$matches) : $handler();
        }

        //// $handler is string

        // is array ['controller', 'action']
        if (is_array($handler)) {
            $segments = $handler;
        } elseif (is_string($handler)) {
            if (strpos($handler, '@') === false && function_exists($handler)) {
                return $matches ? $handler(...$matches) : $handler();
            }

            // e.g `controllers\Home@index` Or only `controllers\Home`
            $segments = explode('@', trim($handler));
        } else {
            throw new \InvalidArgumentException('Invalid route handler');
        }

        // Instantiation controller
        $controller = new $segments[0]();

        // Already assign action
        if (isset($segments[1])) {
            $action = $segments[1];

            // use dynamic action
        } elseif ((bool)$this->config['dynamicAction']) {
            $action = isset($matches[0]) ? trim($matches[0], '/') : $this->config['defaultAction'];

            // defined default action
        } elseif (!$action = $this->config['defaultAction']) {
            throw new \RuntimeException("please config the route path [$path] controller action to call");
        }

        $action = Router::convertNodeStr($action);

        // if set the 'actionExecutor', the action handle logic by it.
        if ($executor = $this->config['actionExecutor']) {
            return $controller->$executor($action, $matches);
        }

        // action method is not exist
        if (!$action || !method_exists($controller, $action)) {
            return $this->handleNotFound($path, true);
        }

        // call controller's action method
        return $matches ? $controller->$action(...$matches) : $controller->$action();
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Defines callback on happen event
     * @param $event
     * @param callable $handler
     */
    public function on($event, $handler)
    {
        if (self::isSupportedEvent($event)) {
            self::$events[$event] = $handler;
        }
    }

    /**
     * Trigger event
     * @param $event
     * @param array $args
     * @return mixed
     */
    protected function fire($event, array $args = [])
    {
        if (isset(self::$events[$event]) && ($cb = self::$events[$event])) {
            return !is_array($cb) ? $cb(...$args) : call_user_func_array($cb, $args);
        }

        return null;
    }

    /**
     * @param $event
     * @return bool
     */
    public static function hasEventHandler($event)
    {
        return isset(self::$events[$event]);
    }

    /**
     * @return array
     */
    public static function getSupportedEvents()
    {
        return [self::ON_FOUND, self::ON_NOT_FOUND, self::ON_EXEC_START, self::ON_EXEC_END, self::ON_EXEC_ERROR];
    }

    /**
     * @param $name
     * @return array
     */
    public static function isSupportedEvent($name)
    {
        return in_array($name, static::getSupportedEvents(), true);
    }

    /**
     * @return \Closure
     */
    public function getMatcher()
    {
        return $this->matcher;
    }

    /**
     * @param \Closure $matcher
     * @return $this
     */
    public function setMatcher(\Closure $matcher)
    {
        $this->matcher = $matcher;

        return $this;
    }
}
