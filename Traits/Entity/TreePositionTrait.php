<?php

namespace Gamma\Framework\Traits\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Position Entity, don't forget to implement the TreePositionInterface
 *
 * @author Fabian Martin <martin@localdev.de>
 */
trait TreePositionTrait
{
    /**
     * Position
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $position = 30000;

    /**
     * Tree Position
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $treePosition = 0;

    /**
     * Tree Level
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $treeLevel = 0;

    /**
     * Tree Root
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $treeRoot = 0;

    /**
     * Returns the id from the top element
     *
     * @return int
     */
    public function getUniqueId()
    {
        return $this->getId();
    }

    /**
     * Set position
     *
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Get position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set tree Position
     *
     * @param int $treePosition
     */
    public function setTreePosition($treePosition)
    {
        $this->treePosition = $treePosition;
    }

    /**
     * Get tree Position
     *
     * @return int
     */
    public function getTreePosition()
    {
        return $this->treePosition;
    }

    /**
     * Set tree Level
     *
     * @param int $treeLevel
     */
    public function setTreeLevel($treeLevel)
    {
        $this->treeLevel = $treeLevel;
    }

    /**
     * Get tree Level
     *
     * @return int
     */
    public function getTreeLevel()
    {
        return $this->treeLevel;
    }

    /**
     * Set tree Root
     *
     * @param int $treeRoot
     */
    public function setTreeRoot($treeRoot)
    {
        $this->treeRoot = $treeRoot;
    }

    /**
     * Get tree Root
     *
     * @return int
     */
    public function getTreeRoot()
    {
        return $this->treeRoot;
    }
}