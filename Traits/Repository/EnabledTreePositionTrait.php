<?php

namespace Gamma\Framework\Traits\Repository;

/**
 * Set of common functions used for tree position items with enable flag and slug (common site entities)
 *
 * @author Evgeniy Kuzmin <jekccs@gmail.com>
 */
trait EnabledTreePositionTrait
{
    /**
     * find enabled items without parent
     * @return array
     */
    public function findEnabledParents($offset = 0, $max = 30)
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.enabled=1')
            ->andWhere('p.parent IS NULL')
            ->setMaxResults($max)
            ->setFirstResult($offset)
            ->orderBy('p.treePosition')
            ->getQuery();

        return $query->getResult();
    }
}
