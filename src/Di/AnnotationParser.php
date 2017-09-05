<?php

namespace Swoft\Di;

use Swoft\Di\Resource\AnnotationResource;

/**
 *
 *
 * @uses      AnnotationParser
 * @version   2017年09月05日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class AnnotationParser
{
    private $annotationResource;

    public function __construct(AnnotationResource $annotationResource)
    {
        $this->annotationResource = $annotationResource;
    }

    public function parseAutoController()
    {

    }
}