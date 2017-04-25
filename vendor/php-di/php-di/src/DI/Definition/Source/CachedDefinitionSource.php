<?php

namespace DI\Definition\Source;

use DI\Definition\CacheableDefinition;
use DI\Definition\Definition;
use Doctrine\Common\Cache\Cache;

/**
 * Caches another definition source.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class CachedDefinitionSource implements DefinitionSource
{
    /**
     * Prefix for cache key, to avoid conflicts with other systems using the same cache.
     * @var string
     */
    const CACHE_PREFIX = 'DI\\Definition\\';

    /**
     * @var DefinitionSource
     */
    private $source;

    /**
     * @var Cache
     */
    private $cache;

    public function __construct(DefinitionSource $source, Cache $cache)
    {
        $this->source = $source;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition($name)
    {
        // Look in cache
        $definition = $this->fetchFromCache($name);

        if ($definition === false) {
            $definition = $this->source->getDefinition($name);

            // Save to cache
            if ($definition === null || ($definition instanceof CacheableDefinition)) {
                $this->saveToCache($name, $definition);
            }
        }

        return $definition;
    }

    /**
     * @return Cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Fetches a definition from the cache.
     *
     * @param string $name Entry name
     * @return Definition|null|bool The cached definition, null or false if the value is not already cached
     */
    private function fetchFromCache($name)
    {
        $cacheKey = self::CACHE_PREFIX . $name;

        $data = $this->cache->fetch($cacheKey);

        if ($data !== false) {
            return $data;
        }

        return false;
    }

    /**
     * Saves a definition to the cache.
     *
     * @param string          $name Entry name
     * @param Definition|null $definition
     */
    private function saveToCache($name, Definition $definition = null)
    {
        $cacheKey = self::CACHE_PREFIX . $name;

        $this->cache->save($cacheKey, $definition);
    }
}
