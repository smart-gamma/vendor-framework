<?php

namespace Gamma\Framework\Traits\DI;

/**
 * Set Redis trait
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
trait SetRedisTrait
{
    public function setRedis($redis)
    {
        $this->redis = $redis;
    }
}
