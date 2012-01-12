<?php
namespace VS\TimeSheet\Controller\Admin;

/*                                                                        *
 * This script belongs to the FLOW3 package "VS.TimeSheet".               *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Standard controller for the VS.TimeSheet package 
 *
 * @FLOW3\Scope("singleton")
 */
class TaskController extends \VS\TimeSheet\MVC\Controller\BasicController {

    /**
     * @FLOW3\Inject
     * @var VS\TimeSheet\Domain\Repository\TaskRepository
     */
    protected $taskRepository;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('tasks', $this->taskRepository->findAll());
	}

    public function newAction(\VS\TimeSheet\Domain\Model\Project $project = NULL) {
        $task = new \VS\TimeSheet\Domain\Model\Task();

        if(!is_null($project))
            $task->addProject($project);

        $this->view->assign('task', $task);
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Project $project
     * @return void
     */
    public function createAction(\VS\TimeSheet\Domain\Model\Task $task) {
        $this->taskRepository->add($task);
        $this->redirect('index');
    }
}

?>