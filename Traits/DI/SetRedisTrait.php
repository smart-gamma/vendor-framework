<?php

namespace Gamma\Framework\Traits\DI;

/**
 * Set Redis trait
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
trait SetRedisTrait
{
    /**
     * @DI\Inject("snc_redis.default")
     */
    protected $redis;

    public function setRedis($redis)
    {
        $this->redis = $redis;
    }
}
