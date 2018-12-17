<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
 
 // 全局函数需要加上function_exists判断，不然多次回调会导致重复定义（不知道这里备注对不对，不对请修正）
if (!function_exists('demo')) {
    function demo(){
        return 123;
    }
}
