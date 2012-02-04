<?php
namespace VS\TimeSheet\Json\Controller;

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
class StandardController extends \VS\TimeSheet\MVC\Controller\BasicJsonController {

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\ProjectRepository
     */
    protected $projectRepository;

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\CustomerRepository
     */
    protected $customerRepository;

    /**
     * @param \VS\TimeSheet\Domain\Model\Customer $customer
     * @return void
     */
    public function customerSelectedAction(\VS\TimeSheet\Domain\Model\Customer $customer) {
        $projects = array();
        $firstProject = null;
        foreach ($customer->getProjects() as $project) {
            if(is_null($firstProject))
                $firstProject = $project;

            $projects[] = array(
                'value' => $this->helper->getIdentifier($project),
                'text' => $project->getName()

            );
        }

        $tasks = array();
        if(!is_null($firstProject)) {
            foreach($firstProject->getTasks() as $task) {
                if($task->getActive())
                    $tasks[] = array(
                        'value' => $this->helper->getIdentifier($task),
                        'text' => $task->getName()
                    );
            }
        }

        $this->view->assign('value', array(
            'projects' => $projects,
            'tasks' => $tasks
        ));
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Project $project
     * @return void
     */
    public function projectSelectedAction(\VS\TimeSheet\Domain\Model\Project $project) {
        $value = array();
        foreach ($project->getTasks() as $task) {
            $value[] = array(
                'value' => $this->helper->getIdentifier($task),
                'text' => $task->getName()

            );
        }
        $this->view->assign('value', $value);
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Task $task
     * @return void
     */
    public function taskDetailsAction(\VS\TimeSheet\Domain\Model\Task $task) {
        $this->view->assign('value', $task);
    }


    public function loadSelectOptionsAction() {
        $customers = array();
        foreach($this->customerRepository->findAll() as $customer) {
            $projects = array();

            foreach($customer->getProjects() as $project) {
                $tasks = array();

                foreach($project->getTasks() as $task) {
                    $tasks[] = array(
                        'value' => $this->helper->getIdentifier($task),
                        'text' => $task->getName()
                    );
                }

                $projects[$this->helper->getIdentifier($project)] = array(
                    'value' => $this->helper->getIdentifier($project),
                    'text' => $project->getName(),
                    'tasks' => $tasks
                );
            }

            $customers[$this->helper->getIdentifier($customer)] = array(
                'value' => $this->helper->getIdentifier($project),
                'text' => $customer->getName()->getFullName(),
                'projects' => $projects
            );
        }

        $this->view->assign('value', array(
            'customers' => $customers
        ));
    }
}

?>