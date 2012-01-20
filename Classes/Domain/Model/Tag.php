<?php
namespace VS\TimeSheet\Domain\Model;

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * @FLOW3\Entity
 */
class Tag {
    /**
     * @var string
     * @FLOW3\Validate(type="NotEmpty")
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \VS\TimeSheet\Domain\Model\Tag
     * @ORM\ManyToOne(inversedBy="childs")
     */
    protected $parent;

    /**
	 * @var \Doctrine\Common\Collections\Collection<\VS\TimeSheet\Domain\Model\Tag>
	 * @ORM\OneToMany(mappedBy="parent")
	 */
	protected $childs;

    /**
     * Constructor
     */
    public function __construct($name = '') {
        $this->childs = new \Doctrine\Common\Collections\ArrayCollection();

        $this->setName($name);
	}

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Tag $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return \VS\TimeSheet\Domain\Model\Tag
     */
    public function getParent()
    {
        return $this->parent;
    }
}
