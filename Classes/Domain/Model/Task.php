<?php
namespace VS\TimeSheet\Domain\Model;

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * @FLOW3\Entity
 */
class Task {
    /**
     * @var string
     * @FLOW3\Validate(type="NotEmpty")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(length=100)
     */
    protected $code;

    /**
     * @var boolean
     */
    protected $active;

    /**
     * @var integer
     */
    protected $commentMinLength;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $comment;

    /**
     * @var string
     * @ORM\Column(length=50)
     */
    protected $color;

    /**
     * @var \VS\TimeSheet\Domain\Model\Project
     * @ORM\ManyToOne(inversedBy="tasks")
     */
    protected $project;

    /**
     * @var \Doctrine\Common\Collections\Collection<\VS\TimeSheet\Domain\Model\Activity>
     * @ORM\OneToMany(mappedBy="task")
     * @ORM\OrderBy({"date" = "ASC"})
     */
    protected $activities;

    /**
     * Constructor
     */
    public function __construct($name = '') {
		$this->activities = new \Doctrine\Common\Collections\ArrayCollection();

        $this->name = $name;
        $this->code = '';
        $this->comment = '';

        $this->active = TRUE;
        $this->commentMinLength = 0;
	}

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivities()
    {
        return $this->activities;
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
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param int $commentMinLength
     */
    public function setCommentMinLength($commentMinLength)
    {
        $this->commentMinLength = $commentMinLength;
    }

    /**
     * @return int
     */
    public function getCommentMinLength()
    {
        return $this->commentMinLength;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return \VS\TimeSheet\Domain\Model\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }
}
