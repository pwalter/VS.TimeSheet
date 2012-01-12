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
class ProjectController extends \VS\TimeSheet\MVC\Controller\BasicController {

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\CustomerRepository
     */
    protected $customerRepository;

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\ProjectRepository
     */
    protected $projectRepository;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('projects', $this->projectRepository->findAll());
	}

    public function newAction(\VS\TimeSheet\Domain\Model\Customer $customer = NULL) {
        $project = new \VS\TimeSheet\Domain\Model\Project();

        if(!is_null($customer))
            $project->setCustomer($customer);

        $this->view->assign('project', $project);
        $this->view->assign('customers', $this->customerRepository->findAll());
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Project $project
     * @return void
     */
    public function createAction(\VS\TimeSheet\Domain\Model\Project $project) {
        $this->projectRepository->add($project);
        $this->redirect('index');
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Project $project
     * @return void
     */
    public function deleteAction(\VS\TimeSheet\Domain\Model\Project $project) {
        $this->projectRepository->remove($project);
        $this->addFlashMessage('Projekt wurde erfolgreich entfernt');
        $this->redirect('index');
    }
}

?>