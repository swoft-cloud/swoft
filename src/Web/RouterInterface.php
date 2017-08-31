<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/7/16
 * Time: 下午10:43
 */

namespace Swoft\Web;

/**
 * Interface RouterInterface
 * @package Swoft\Web
 */
interface RouterInterface
{
    const ANY_METHOD = 'ANY';

    // match result status
    const STS_FOUND = 1;
    const STS_NOT_FOUND = 2;
    const STS_METHOD_NOT_ALLOWED = 3;

    const DEFAULT_REGEX = '[^/]+';
    const DEFAULT_TWO_LEVEL_KEY = '_NO_';

    /**
     * supported Methods
     * @var array
     */
    const SUPPORTED_METHODS = [
        'ANY',
        'GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD', 'SEARCH', 'CONNECT', 'TRACE',
    ];

    /**
     * @return array
     */
    public static function getSupportedMethods();
}
