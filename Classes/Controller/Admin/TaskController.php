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
     * @var \VS\TimeSheet\Domain\Repository\TaskRepository
     */
    protected $taskRepository;

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\CustomerRepository
     */
    protected $customerRepository;

	/**
     * @param \VS\TimeSheet\Domain\Model\Customer $customer
     * @param \VS\TimeSheet\Domain\Model\Project $project
     */
	public function indexAction(\VS\TimeSheet\Domain\Model\Customer $customer = NULL, \VS\TimeSheet\Domain\Model\Project $project = NULL) {
        if(is_null($project)) {

            if(is_null($customer)) {
                $this->view->assign('tasks', $this->taskRepository->findAll());
            } else {
                $this->view->assign('customer', $customer);
                $this->view->assign('tasks', $this->taskRepository->findAllByCustomer($customer));
            }

        }
        else {
            $this->view->assign('tasks', $project->getTasks());
            $this->view->assign('project', $project);
            $this->view->assign('customer', $project->getCustomer());
        }
	}

    /**
     * @param \VS\TimeSheet\Domain\Model\Project $project
     */
    public function newAction(\VS\TimeSheet\Domain\Model\Project $project = NULL) {
        $task = new \VS\TimeSheet\Domain\Model\Task();

        if(!is_null($project))
            $task->addProject($project);

        $this->view->assign('task', $task);

        // Customer
        $customers = $this->customerRepository->findAll();
        $customerFirst = count($customers) != 0 ? $customers[0] : null;
        $this->view->assign('customers', $customers);

        // Project
        if(!is_null($customerFirst)) {
            $projects = $customerFirst->getProjects();
            $this->view->assign('projects', $projects);
        }
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Task $task
     * @return void
     */
    public function createAction(\VS\TimeSheet\Domain\Model\Task $task) {
        $this->taskRepository->add($task);
        $this->redirect('index');
    }
}

?>