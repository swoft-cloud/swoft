<?php

namespace DI;

use Interop\Container\Exception\ContainerException;

/**
 * Exception for the Container.
 */
class DependencyException extends \Exception implements ContainerException
{
}
