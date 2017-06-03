<?php

namespace DI\Cache;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ClearableCache;
use Doctrine\Common\Cache\FlushableCache;

/**
 * Simple implementation of a cache based on an array.
 *
 * This implementation can be used instead of Doctrine's ArrayCache for
 * better performances (because simpler implementation).
 *
 * The code is based on Doctrine's ArrayCache provider:
 * @see \Doctrine\Common\Cache\ArrayCache
 * @link   www.doctrine-project.org
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author Jonathan Wage <jonwage@gmail.com>
 * @author Roman Borschel <roman@code-factory.org>
 * @author David Abdemoulaie <dave@hobodave.com>
 */
class ArrayCache implements Cache, FlushableCache, ClearableCache
{
    /**
     * @var array
     */
    private $data = [];

    public function fetch($id)
    {
        return $this->contains($id) ? $this->data[$id] : false;
    }

    public function contains($id)
    {
        // isset() is required for performance optimizations, to avoid unnecessary function calls to array_key_exists.
        return isset($this->data[$id]) || array_key_exists($id, $this->data);
    }

    public function save($id, $data, $lifeTime = 0)
    {
        $this->data[$id] = $data;

        return true;
    }

    public function delete($id)
    {
        unset($this->data[$id]);

        return true;
    }

    public function getStats()
    {
        return null;
    }

    public function flushAll()
    {
        $this->data = [];

        return true;
    }

    public function deleteAll()
    {
        return $this->flushAll();
    }
}
