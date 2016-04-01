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
        if (!is_int($limit) || $limit <= 0) {
            throw new Exception(sprintf("Parameter \$limit must be positive integer")); 
        }
        
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
        $key = $join['as'];
        if (!array_key_exists($key, $this->extraJoins)) {
            $this->extraJoins[$key] = $join;
        } else {
            if($this->extraJoins[$key] != $join) {
                throw new Exception('Join key:'.$key.' is already used for join condition:"'.$this->extraJoins[$key].'"');
            }
        }      
    }

    /**
     * @param array   $where
     * @param astring $key
     */
    public function addExtraWhere($where, $key = null)
    {
        if ($key === null) {
            $key = md5($where);
        } 
        
        if (!array_key_exists($key, $this->extraWhere)) {
            $this->extraWhere[$key] = $where;
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
     * @param  string $alias table alias 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createSearchQuery($alias = 'p')
    {
        /* @var $query \Doctrine\ORM\QueryBuilder */
        $query = $this->createQueryBuilder($alias);

        // joins
        foreach ($this->extraJoins as $join) {
            $query->$join['type']($join['field'], $join['as']); 
        }

        // default where
        if ($this->defaultWhere) {
            $query->where($this->defaultWhere); 
        }

        // extra where
        foreach ($this->extraWhere as $where) {
            $query->andWhere($where); 
        }

        // extra or wheres
        foreach ($this->extraOrWhere as $where) {
            $query->orWhere($where); 
        }

        //extra parameters
        foreach ($this->extraParameters as $parameter) {
            $query->setParameter($parameter[0], $parameter[1]); 
        }

        //order
        if(is_array($this->order)) {
            $query->orderBy($this->order[0], $this->getFieldOrderDirection($this->orderDirection, 0));
           
            $order = $this->order;
            unset($order[0]);
            $i = 1;
           
            foreach($order as $extra) {
                $query->addOrderBy($extra, $this->getFieldOrderDirection($this->orderDirection, $i++)); 
            }
        } else {    
            $query->orderBy($this->order, $this->getFieldOrderDirection($this->orderDirection, 0));
        }    
        
        if(0 < $this->limit) {
            $query->setMaxResults($this->limit); 
        }

        //Debug query
        //echo "<br>QUERY:".$query->getQuery()->getSql();
        return $query;
    }
    
    /**
     * 
     * @param string|array $orderDirection
     * @return string
     */
    private function getFieldOrderDirection($orderDirection, $elementIndex)
    {
        if(is_array($orderDirection)) {
            return $orderDirection[$elementIndex];
        } else {
            return $orderDirection;
        }
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
