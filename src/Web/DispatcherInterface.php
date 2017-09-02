<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/7/17
 * Time: 下午9:40
 */

namespace Swoft\Web;

/**
 * Interface DispatcherInterface
 * @package Swoft\Web
 */
interface DispatcherInterface
{
    // events
    const ON_FOUND = 'found';
    const ON_NOT_FOUND = 'notFound';
    const ON_EXEC_START = 'execStart';
    const ON_EXEC_END = 'execEnd';
    const ON_EXEC_ERROR = 'execError';

    /**
     * Runs the callback for the given request
     * @param string $path
     * @param null|string $method
     * @return mixed
     */
    public function dispatch($path = null, $method = null);

    public function getMatcher();

    public function setMatcher(\Closure $matcher);

    /**
     * @return array
     */
    public static function getSupportedEvents();
}
