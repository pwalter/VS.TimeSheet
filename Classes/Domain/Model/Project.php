<?php
namespace VS\TimeSheet\Domain\Model;

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * @FLOW3\Entity
 */
class Project {
    /**
     * @var string
     * @FLOW3\Validate(type="NotEmpty")
     */
    protected $name;

    /**
     * @var string
     * @FLOW3\Validate(type="StringLength", options={ "minimum"=1, "maximum"=100 })
     * @FLOW3\Identity
     * @ORM\Column(length=100)
     */
    protected $number;

    /**
     * @var string
     * @ORM\Column(length=100)
     */
    protected $code;

    /**
     * @var \VS\TimeSheet\Domain\Model\Customer
     * @FLOW3\Validate(type="NotEmpty")
     * @ORM\ManyToOne(inversedBy="projects")
     */
    protected $customer;

    /**
     * @var \VS\TimeSheet\Domain\Model\ProjectTemplate
     * @ORM\ManyToOne(inversedBy="projects")
     */
    protected $projectTemplate;

    /**
     * @var \Doctrine\Common\Collections\Collection<\VS\TimeSheet\Domain\Model\Task>
     * @ORM\OneToMany(mappedBy="project", cascade={"all"})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    protected $tasks;

    /**
	 * @var \Doctrine\Common\Collections\Collection<\VS\TimeSheet\Domain\Model\Activity>
	 * @ORM\OneToMany(mappedBy="project", cascade={"all"})
	 */
    protected $activities;

    /**
     * Constructor
     */
    public function __construct($name = '') {
		$this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->activites = new \Doctrine\Common\Collections\ArrayCollection();
        $this->name = $name;
	}

    /**
     * @param Task $task
     * @return void
     */
    public function addTask(\VS\TimeSheet\Domain\Model\Task $task) {
		$this->tasks->add($task);
        $task->setProject($this);
	}

    /**
     * @param \VS\TimeSheet\Domain\Model\Customer $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return \VS\TimeSheet\Domain\Model\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\ProjectTemplate $projectTemplate
     */
    public function setProjectTemplate($projectTemplate)
    {
        $this->projectTemplate = $projectTemplate;
    }

    /**
     * @return \VS\TimeSheet\Domain\Model\ProjectTemplate
     */
    public function getProjectTemplate()
    {
        return $this->projectTemplate;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivities()
    {
        return $this->activities;
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
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }
}
