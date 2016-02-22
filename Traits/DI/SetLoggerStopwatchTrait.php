<?php

namespace Gamma\Framework\Traits\DI;

/**
 * Set logger stopwatch trait
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
trait SetLoggerStopwatchTrait
{
    /**
     * @DI\Inject("gamma.logger.stopwatch")
     */
    protected $loggerStopwatch;

    public function setLoggerStopwatch($loggerStopwatch)
    {
        $this->loggerStopwatch = $loggerStopwatch;
    }
}
