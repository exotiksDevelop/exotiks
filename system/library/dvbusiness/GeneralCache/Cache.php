<?php

namespace DvBusiness\GeneralCache;

use DvBusiness\GeneralCache\CacheItem;
use Cache as OC_Cache;

class Cache
{
    /** @var OC_Cache */
    private $cacheClient;

    /** @var int */
    private $expirationInSeconds;

    public function __construct(int $expirationInSeconds = 0)
    {
        $this->expirationInSeconds = $expirationInSeconds ?: 5000;
        $this->cacheClient = new OC_Cache('file', $this->expirationInSeconds);
    }

    public function save(CacheItem $item)
    {
        $this->cacheClient->set($item->getKey(), $item->get());
    }

    public function hasItem(string $key): bool
    {
        return (bool) $this->cacheClient->get($key);
    }

    public function deleteItem(string $key)
    {
        if ($this->hasItem($key)) {
           $this->cacheClient->delete($key);
        }
    }

    /**
     * @param $key
     * @return CacheItem|null
     */
    public function getItem(string $key)
    {
        if ($this->hasItem($key)) {
            $item = new CacheItem(
                $key,
                $this->cacheClient->get($key),
                $this->expirationInSeconds
            );
            return $item;
        }

        return null;
    }

    public function deleteItems(array $data)
    {
        foreach ($data as $key) {
            if ($this->hasItem($key)) {
                $this->deleteItem($key);
            }
        }
    }

    /**
     * @param array $data
     * @return CacheItem[]|array
     */
    public function getItems(array $data): array
    {
        $items = [];

        foreach ($data as $key) {
            if ($this->hasItem($key)) {
                $items[] = $this->getItem($key);
            }
        }

        return $items;
    }
}