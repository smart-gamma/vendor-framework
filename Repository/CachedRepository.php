<?php

namespace LaMelle\Framework\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Cache repository
 * Helps work with doctrine cache
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
class CacheRepository extends EntityRepository
{
    /*
     * Caching duarations
     */
    const CACHE_HOUR    = 3600;
    const CACHE_DAY     = 86400;
    const CACHE_WEEK    = 604800;
    
    /*
     * Cache host to prevent reseller shops mess caching
     * @var string
     */
    private $cacheHost = null;

    /*
     * Cache locale to prevent localized mess for multilanguages support
     * @var string
     */
    private $cacheLocale = null;
    
    /*
     * Set cache host
     * @param string $host
     */
    public function setCacheHost($host)
    {
        $this->cacheHost = $host;
    }

    /*
     * Set cache locale
     * @param string $locale
     */
    public function setCacheLocale($locale)
    {
        $this->cacheLocale = $locale;
    }  
    
    /*
     * Get path prefix to cache result
     * @param string $callerName - function name of called method 
     * @return string
     */
    public function getCachePrefix($callerName)
    {
        return $this->cacheHost. $this->cacheLocale. $callerName;
    }
}