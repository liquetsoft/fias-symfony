<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\CacheProvider;

/**
 * Array cache driver.
 *
 * @see www.doctrine-project.org
 *
 * @psalm-suppress DeprecatedInterface
 */
class DoctrineTestCaseArrayCache extends CacheProvider
{
    /** @var array */
    private $data = [];

    /** @var int */
    private $hitsCount = 0;

    /** @var int */
    private $missesCount = 0;

    /** @var int */
    private $upTime;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->upTime = time();
    }

    /**
     * {@inheritdoc}
     */
    protected function doFetch($id)
    {
        if (!$this->doContains($id)) {
            ++$this->missesCount;

            return false;
        }

        ++$this->hitsCount;

        return $this->data[$id][0];
    }

    /**
     * {@inheritdoc}
     */
    protected function doContains($id)
    {
        if (!isset($this->data[$id])) {
            return false;
        }

        $expiration = $this->data[$id][1];

        if ($expiration && $expiration < time()) {
            $this->doDelete($id);

            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doSave($id, $data, $lifeTime = 0)
    {
        $this->data[$id] = [$data, $lifeTime ? time() + $lifeTime : false];

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doDelete($id)
    {
        unset($this->data[$id]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doFlush()
    {
        $this->data = [];

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doGetStats()
    {
        return [
            Cache::STATS_HITS => $this->hitsCount,
            Cache::STATS_MISSES => $this->missesCount,
            Cache::STATS_UPTIME => $this->upTime,
            Cache::STATS_MEMORY_USAGE => null,
            Cache::STATS_MEMORY_AVAILABLE => null,
        ];
    }
}
