<?php
namespace VS\TimeSheet\Domain\Model;

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * @FLOW3\Entity
 */
class Activity {
    
    /**
     * @var \VS\TimeSheet\Domain\Model\Task
     * @FLOW3\Validate(type="NotEmpty")
     * @ORM\ManyToOne(inversedBy="activities")
     */
    protected $task;

    /**
     * @var \VS\TimeSheet\Domain\Model\Project
     * @FLOW3\Validate(type="NotEmpty")
     * @ORM\ManyToOne(inversedBy="activities")
     */
    protected $project;

    /**
     * @var \TYPO3\FLOW3\Security\Account
     * @FLOW3\Validate(type="NotEmpty")
     * @ORM\ManyToOne
     */
    protected $account;

    /**
     * @var \DateTime
     * @FLOW3\Validate(type="NotEmpty")
     */
    protected $date;

    /**
     * @var int
     */
    protected $minutes;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $comment;

    /**
     * @var \Doctrine\Common\Collections\Collection<\VS\TimeSheet\Domain\Model\Tag>
     * @ORM\ManyToMany
     */
    protected $tags;

    public function __construct() {
        $this->date = new \DateTime();
        $this->comment = '';
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param \TYPO3\FLOW3\Security\Account $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return \TYPO3\FLOW3\Security\Account
     */
    public function getAccount()
    {
        return $this->account;
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
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param int $minutes
     */
    public function setMinutes($minutes)
    {
        $this->minutes = $minutes;
    }

    /**
     * @return int
     */
    public function getMinutes()
    {
        return $this->minutes;
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Task $task
     */
    public function setTask($task)
    {
        $this->task = $task;
    }

    /**
     * @return \VS\TimeSheet\Domain\Model\Task
     */
    public function getTask()
    {
        return $this->task;
    }
    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
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
}
