<?php

namespace Gamma\Framework\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

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
     * Get path prefix to cache result
     * @param string $callerName - function name of called method 
     * @return string
     */
    public function getCachePrefix($callerName)
    {
        return $this->cacheHost. '_' .$this->cacheLocale. '_' .$callerName;
    }
    
    /*
     * Init cache params for proper path generation
     */
    public function initCachePath(Request $request)
    {
        $this->cacheHost = $request->getHost();
        $this->cacheLocale = $request->getLocale();
    }
    
    /*
     * Builds path to cache for dynamicly queries built with createSearchQuery method
     * @return string
     */
    public function getCreatedSearchQueryCachePath() {
        
        $extraWhere = '';
        
        if(isset($this->extraWhere) && isset($this->extraParameters)) {
            
            $extraWhere = implode("_", $this->extraWhere);
            
            foreach($this->extraParameters as $parameter) {
                if(is_object($parameter[1]))
                    $replacement = $parameter[1]->getId();
                elseif(is_array($parameter[1])){
                    $replacement = implode('_', $parameter[1]);
                }    
                else
                    $replacement = $parameter[1];
                
                $extraWhere = str_replace(":".$parameter[0], $replacement, $extraWhere);
            }    
            $extraWhere = str_replace(' ', '_', $extraWhere); 
        }
 
        return $extraWhere;
    }
}