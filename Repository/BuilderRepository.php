<?php

namespace Gamma\Framework\Repository;

/**
 * Builder repository
 * Provides advanced methods for query builder 
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
abstract class BuilderRepository extends CacheRepository
{
    /**
     * Default where condition
     * @var string
     */
    protected $defaultWhere;

    /**
     * Ordering
     * @var string|array 
     */
    protected $order;

    /**
     * Ordering Direction
     * @var string
     */
    protected $orderDirection = 'ASC';

    /**
     * Extra joins
     * @var array
     */
    protected $extraJoins = array();

    /**
     * Extra where
     * @var array
     */
    protected $extraWhere = array();

    /**
     * Extra or wheres
     * @var array
     */
    protected $extraOrWhere = array();

    /**
     * Extra parameters for extra where
     * @var array
     */
    protected $extraParameters = array();

    /**
     *
     * @var type
     */
    protected $limit = 0;

    /**
     * @param string|array $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function setLimit($limit)
    {
        if (!is_int($limit) || $limit <= 0)
            throw new Exception(sprintf("Parameter \$limit must be positive integer"));
        
        $this->limit = $limit;
    }

    /**
     * @param string $order
     */
    public function setDefaultWhere($defaultWhere)
    {
        $this->defaultWhere = $defaultWhere;
    }

    /**
     * @param string $orderDirection
     */
    public function setOrderDirection($orderDirection)
    {
        $this->orderDirection = $orderDirection;
    }

    /**
     * @param array $join
     */
    public function addExtraJoins($join)
    {
        $this->extraJoins[] = $join;
    }

    /**
     * @param array $where
     * @param astring $key
     */
    public function addExtraWhere($where, $key = null)
    {
        if ($key) {
            $this->extraWhere[$key] = $where;
        } else {
            $this->extraWhere[] = $where;
        }
    }

    /**
     * @param astring $key
     */
    public function removeExtraWhere($key)
    {
        if (array_key_exists($key, $this->extraWhere)) {
            unset($this->extraWhere[$key]);
            $this->removeExtraParameters($key);
        }
    }

    /**
     * @param array $where
     */
    public function addExtraOrWhere($where)
    {
        $this->extraOrWhere[] = $where;
    }

    /**
     * @param array $join
     */
    public function addExtraParameters($extraParameter, $key = null)
    {
        if ($key) {
            $this->extraParameters[$key] = $extraParameter;
        } else {
            $this->extraParameters[] = $extraParameter;
        }
    }

    /**
     * @param string $key
     */
    public function removeExtraParameters($key)
    {
        if (array_key_exists($key, $this->extraParameters)) {
            unset($this->extraParameters[$key]);
        }
    }

    /**
     * Create pagination query constructor for search conditions
     *
     * @param string $alias table alias 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createSearchQuery($alias = 'p')
    {
        /* @var $query \Doctrine\ORM\QueryBuilder */
        $query = $this->createQueryBuilder($alias);

        // joins
        foreach ($this->extraJoins as $join)
            $query->$join['type']($join['field'], $join['as']);

        // default where
        if ($this->defaultWhere)
            $query->where($this->defaultWhere);

        // extra where
        foreach ($this->extraWhere as $where)
            $query->andWhere($where);

        // extra or wheres
        foreach ($this->extraOrWhere as $where)
            $query->orWhere($where);

        //extra parameters
        foreach ($this->extraParameters as $parameter)
            $query->setParameter($parameter[0], $parameter[1]);

        //order
        if(is_array($this->order)) {
           $query->orderBy($this->order[0], $this->orderDirection);
           
           $order = $this->order;
           unset($order[0]);
           
           foreach($order as $extra) {
              $query->addOrderBy($extra, $this->orderDirection); 
           }
        } else {    
            $query->orderBy($this->order, $this->orderDirection);
        }    
        
        if(0 < $this->limit)
            $query->setMaxResults ($this->limit);

        //Debug query
        //echo "<br>QUERY:".$query->getQuery()->getSql();
        return $query;
    }
    
    /**
     * Legacy call to get query
     */
    public function buildSearchQuery()
    {
        return $this->createSearchQuery()->getQuery();
    }
    
    /**
     * Returns the number of items which are in builder condition
     *
     * @return int
     */
    public function getCount()
    {
        $query = $this->createSearchQuery()
            ->select("COUNT(p.id)")
            ->getQuery();
        
        $queryPath = $this->getCreatedSearchQueryCachePath();
        $query->useResultCache(true, self::CACHE_DAY, $this->getCachePrefix(__FUNCTION__) . $queryPath);
        
        $result = $query->getScalarResult();

        return $result[0][1];
    }
    
    /**
     * internal helper
     */
    public function clearExtraParameters()
    {
        $this->extraJoins = array();
        $this->extraWhere = array();
        $this->extraOrWhere = array();
        $this->extraParameters = array();
    }
}
