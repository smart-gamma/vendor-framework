<?php

namespace Gamma\Framework\Traits\Repository;

/**
 * Set of common functions used for items with slug and enabled flag
 *
 * @author Evgeniy Kuzmin <jekccs@gmail.com>
 */
trait EnabledSlugTrait
{
    /**
     * Find enabled item by slug
     * @param  string     $slug
     * @return mixed|null
     */
    public function findBySlug($slug)
    {
        $item = null;
        try {
            $query = $this->createQueryBuilder("p")
                ->where("p.enabled=1")
                ->andWhere("p.slug=:slug")
                ->setParameter("slug", $slug)
                ->getQuery();
            $item = $query->getSingleResult();
        } catch (\Exception $ex) {
        }

        return $item;
    }
}
