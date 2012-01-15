<?php
namespace VS\TimeSheet\Domain\Model;

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * @FLOW3\Entity
 */
class ProjectTemplate {

    /**
     * @var string
     * @FLOW3\Validate(type="NotEmpty")
     */
    protected $name;

    /**
     * @var \Doctrine\Common\Collections\Collection<\VS\TimeSheet\Domain\Model\Task>
     * @ORM\ManyToMany
     * @ORM\OrderBy({"identifier" = "ASC"})
     */
    protected $tasks;

    /**
	 * @var \Doctrine\Common\Collections\Collection<\VS\TimeSheet\Domain\Model\Project>
	 * @ORM\OneToMany(mappedBy="projectTemplate")
	 */
	protected $projects;

    /**
     * Constructor
     */
    public function __construct($name = '') {
		$this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();

        $this->name = $name;
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
     * @param Task $task
     * @return void
     */
    public function addTask(\VS\TimeSheet\Domain\Model\Task $task) {
		$this->tasks->add($task);
	}

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param Project $project
     * @return void
     */
    public function addProject(\VS\TimeSheet\Domain\Model\Project $project) {
		$this->projects->add($project);
	}

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }
}
