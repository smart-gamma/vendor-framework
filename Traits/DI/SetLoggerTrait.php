<?php

namespace Gamma\Framework\Traits\DI;

/**
 * Set Logger trait
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
trait SetLoggerTrait
{
    /**
     * @DI\Inject("logger")
     * @var \LoggerInterface $logger
     */
    public $logger;

    /**
     * @param \LoggerInterface $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }
}
