<?php

namespace Gamma\Framework\Traits\Repository;

/**
 * functions for repositories with enabled property added
 *
 * @author Evgeniy Kuzmin <jekccs@gmail.com>
 */
trait EnabledCommonTrait
{

    /**
     * Returns the number of enabled items
     * @return int
     */
    public function getCountForEnabled()
    {
        $query = $this->createQueryBuilder("p")
            ->select("COUNT(p.id)")
            ->where("p.enabled=1")
            ->getQuery();
        $result = $query->getScalarResult();

        return $result[0][1];
    }

    /**
     * Returns the number of enabled items
     * @param  int $limit
     * @return mixed
     */
    public function getLast($limit = 5)
    {
        $query = $this->createQueryBuilder("p")
            ->select("p")
            ->where("p.enabled=1")
            ->orderBy("p.id", "DESC")
            ->getQuery()
            ->setMaxResults($limit);
        $result = $query->getResult();

        return $result;
    }
}
