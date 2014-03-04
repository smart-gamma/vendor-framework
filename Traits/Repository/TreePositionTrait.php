<?php

namespace Gamma\Framework\Traits\Repository;

/**
 * Set of common functions used for tree position items 
 * 
 * @author Fabian Martin <martin@localdev.de>
 * @author Evgeniy Kuzmin <jekccs@gmail.com>
 */
trait TreePositionTrait
{
    /**
     * All items
     * @var array|null
     */
    private static $items = null;
    
	/**
	 * Rebuild the sorted list
	 *
	 * @param bool                         $saveAfterSort    save tree changes to the database
	 * @param null|TreePositionInterface[] $entities         sort this entities
	 */
	public function orderItems($saveAfterSort = true, $entities = null)
	{
		/* @var TreePositionInterface[] $entities */
		$entities = ($entities) ? : $this->findBy(array(), array("position" => "ASC"));

		$groups = $this->groupEntities($entities);
		foreach ($groups as $entities)
		{
			// sort by parents
			$byParent = array();
			foreach ($entities as $entity)
			{
				/* @var TreePositionInterface $entity */
				/* @var TreePositionInterface|int $parent */
				$parent = $entity->getParent();
				$parentId = (is_object($parent)) ? $parent->getUniqueId() : (int)$parent;
				$entity->setTreeLevel(-1);

				$parents =& $byParent[$parentId];
				if (!isset($parents))
				{
					$parents = array();
				}
				$parents[] = $entity;
			}

			if (array_key_exists(0, $byParent))
			{
				$position = 0;
				$this->orderItemList($byParent, $position, $byParent[0], 0, 0);
			}
		}
		if ($saveAfterSort)
		{
			$this->_em->flush();
		}
	}

	/**
	 * Rebuilds the sort order
	 *
	 * @param TreePositionInterface[] $all       All items sorted by parent
	 * @param int                     $position  current position
	 * @param TreePositionInterface[] $children  child elements
	 * @param int                     $root      id of the root element
	 * @param int                     $level     current level
	 */
	protected function orderItemList(&$all, &$position, &$children, $root, $level)
	{
		$levelPosition = 0;
		foreach ($children as $entity)
		{
			if ($entity->getTreeLevel() >= 0)
			{
				continue;
			}
			$id = $entity->getUniqueId();
			$levelPosition += 2;

			$entity->setPosition($levelPosition);
			$entity->setTreeLevel($level);
			$entity->setTreePosition($position++);
			$entity->setTreeRoot($root);

			if (array_key_exists($id, $all))
			{
				$childRoot = ($root) ? : (int)$entity->getId();

				/* @var TreePositionInterface[] $items */
				$items =& $all[$id];
				$this->orderItemList($all, $position, $items, $childRoot, $level + 1);
			}
		}
		unset($children);
	}

	/**
	 * Groups pages in sorting groups, to allow a better sorting
	 *
	 * @param TreePositionInterface[] $entities  entities
	 *
	 * @return array
	 */
	protected function groupEntities($entities)
	{
		return array($entities);
	}

	/**
	 * Find elements without parent
	 *
	 * @return array
	 */
	public function findParents()
	{
		$query = $this->createQueryBuilder("p")
				->where("p.parent IS NULL")
				->getQuery();

		return $query->getResult();
	}

    /**
     * cache the findAll function for one request
     * @return array
     */
    public function findAll()
    {
        if (self::$items == null) {
            self::$items = parent::findBy(array(), array("treePosition" => "ASC"));
        }

        return self::$items;
    }

	/**
	 * Move entity up in tree
	 *
	 * @param TreePositionInterface|mixed $entity        entity you want to move up
	 * @param bool                        $moveToTop     move to top
	 */
	public function moveUp($entity, $moveToTop)
	{
		if (!($entity instanceof TreePositionInterface))
		{
			$entity = $this->find($entity);
		}

		if ($entity)
		{
			$newPosition = ($moveToTop === true) ? 1 : $entity->getPosition() - ($moveToTop * 2) - 1;
			$entity->setPosition($newPosition);
			$this->_em->flush();
			$this->orderItems();
		}
	}

	/**
	 * Move entity down in tree
	 *
	 * @param TreePositionInterface|mixed $entity        entity you want to move down
	 * @param bool                        $moveToBottom  move to bottom
	 */
	public function moveDown($entity, $moveToBottom)
	{
		if (!($entity instanceof TreePositionInterface))
		{
			$entity = $this->find($entity);
		}

		if ($entity)
		{
			$newPosition = ($moveToBottom === true) ? PHP_INT_MAX : $entity->getPosition() + ($moveToBottom * 2) + 1;
			$entity->setPosition($newPosition);
			$this->_em->flush();
			$this->orderItems();
		}
	}

	/**
	 * Returns the sub trees
	 *
	 * @param TreePositionInterface[] $items   branches you want
	 * @param bool                    $addItem Add the current item to the list
	 *
	 * @return TreePositionInterface[]|TreePositionInterface
	 */
	public function getTreeBranch($items, $addItem = true)
	{
		$asArray = is_array($items);
		if (!$asArray)
		{
			$items = (func_num_args() > 1) ? func_get_args() : array($items);
		}

		$sortedItems = array();
		$resultItems = array();

		/* @var TreePositionInterface $item */
		foreach ($items as $item)
		{
			$sortedItems[$item->getId()] = $item;
		}

		$builder = $this->createQueryBuilder('p');

		$i = 0;
		$orX = $builder->expr()->orX();
		foreach ($items as $item)
		{
			/* @var TreePositionInterface $item */
			$andX = $builder->expr()->andX(
				'p.treeRoot=:root' . ++$i,
				'p.treeLevel>:level' . $i
			);
			$builder->setParameter('root' . $i, $item->getTreeRoot() ? $item->getTreeRoot() : $item->getId())
					->setParameter('level' . $i, $item->getTreeLevel());

			$orX->add($andX);
		}
		$builder->where($orX)->orderBy('p.treePosition', 'ASC');
		$result = $builder->getQuery()->getResult();

		/* @var TreePositionInterface $treeItem */
		foreach ($result as $pos => $treeItem)
		{
			$parent = $treeItem->getParent();
			if (is_object($parent))
			{
				$parent = $parent->getId();
			}
			if (array_key_exists($parent, $sortedItems))
			{
				$resultItems[$parent] = array();
				$slice = array_slice($result, $pos);
				foreach ($slice as $item)
				{
					if ($item->getTreeLevel() > $treeItem->getTreeLevel())
					{
						break;
					}
					elseif ($item->getTreeLevel() == $treeItem->getTreeLevel())
					{
						if ($item->getParent() != $treeItem->getParent())
						{
							break;
						}
					}

					$resultItems[$parent][] = $item;
				}
				unset($sortedItems[$parent]);
			}
		}

		if ($addItem)
		{
			foreach ($items as $item)
			{
				$key = $item->getId();
				if (!array_key_exists($key, $resultItems))
				{
					$resultItems[$key] = array($item);
				}
				else
				{
					array_unshift($resultItems[$key], $item);
				}
			}
		}

		return (!$asArray && count($items) == 1) ? array_shift($resultItems) : $resultItems;
	}

	/**
	 * Get all parent items in the TreeBranch
	 *
	 * @param TreePositionInterface $item        Current item
	 * @param string                $direction   Sort direction
	 *
	 * @return TreePositionInterface[]
	 */
	public function getParents($item, $direction = 'DESC')
	{
		$parents = array();
		if ($item->getTreeLevel())
		{
			$builder = $this->createQueryBuilder('p')
					->andWhere('p.treeLevel>:level', new Orx('p.treeRoot=:root', 'p.id=:root'))
					->orderBy('p.treeLevel', $direction);

			$builder->setParameter('root', $item->getTreeRoot());
			$builder->setParameter('level', $item->getTreeLevel());
			$parents = $builder->getQuery()->getResult();
		}

		return $parents;
	}
}
