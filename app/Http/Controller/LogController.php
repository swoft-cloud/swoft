<?php declare(strict_types=1);


namespace App\Http\Controller;

use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Log\Helper\Log;

/**
 * Class LogController
 *
 * @since 2.0
 *
 * @Controller("log")
 */
class LogController
{
    /**
     * @RequestMapping("test")
     *
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function test(): array
    {
        // Common
        Log::info('info message');

        // Tag start
        Log::profileStart('tagName');

        // Like `sprintf()`
        Log::debug('this %s log', 'debug');
        Log::info('this %s log', 'info');
        Log::warning('this %s log', 'warning');
        Log::error('this %s log', 'error');
        Log::alert('this %s log', 'alert');
        Log::emergency('this %s log', 'emergency');

        // Tag end
        Log::profileEnd('tagName');

        // Tag2 start
        Log::profileStart('tagName');

        // Pushlog
        Log::pushLog('key', 'value');
        Log::pushLog('key', ['value']);
        Log::pushLog('key', 'value');

        // Tag2 end
        Log::profileEnd('tagName');

        // Counting
        Log::counting('mget', 1, 10);
        Log::counting('mget', 2, 10);

        return ['log'];
    }
}