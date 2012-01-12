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
class CustomerController extends \VS\TimeSheet\MVC\Controller\BasicController {

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\CustomerRepository
     */
    protected $customerRepository;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('customers', $this->customerRepository->findAll());
	}

    public function newAction() {
        
    }

    public function initializeCreateAction() {
        $this->arguments['customer']->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('name', '\TYPO3\Party\Domain\Model\PersonName');
        $this->arguments['customer']->getPropertyMappingConfiguration()->allowCreationForSubProperty('name');
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Customer $customer
     * @return void
     */
    public function createAction(\VS\TimeSheet\Domain\Model\Customer $customer) {
        $this->customerRepository->add($customer);
        $this->redirect('index');
    }
}

?>