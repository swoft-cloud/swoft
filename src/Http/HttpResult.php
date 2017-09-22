<?php

namespace Swoft\Http;

use Swoft\App;
use Swoft\Web\AbstractResult;

/**
 * Http结果
 *
 * @uses      HttpResult
 * @version   2017年07月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class HttpResult extends AbstractResult
{
    public function getResult()
    {
        $this->client->recv();
        $result = $this->client->body;
        $this->client->close();

        App::debug("http调用结果=".json_encode($result));
        return $result;
    }
}
