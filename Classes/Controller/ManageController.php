<?php
namespace VS\TimeSheet\Controller;

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
class ManageController extends \VS\TimeSheet\MVC\Controller\BasicController {

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\TaskRepository
     */
    protected $taskRepository;

    /**
     * @FLOW3\Inject
     * @var \TYPO3\FLOW3\Security\AccountRepository
     */
    protected $accountRepository;

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\EmployeeRepository
     */
    protected $employeeRepository;

    /**
     * @FLOW3\Inject
     * @var \TYPO3\FLOW3\Security\AccountFactory
     */
    protected $accountFactory;

    /**
     * @FLOW3\Inject
     * @var \TYPO3\FLOW3\Security\Policy\PolicyService
     */
    protected $policyService;
    
    /**
     * @FLOW3\Inject
     * @var \TYPO3\FLOW3\Security\Cryptography\HashService
     */
    protected $hashService;

    /**
     * @FLOW3\Inject
     * @var VS\TimeSheet\Core\Helper
     */
    protected $helper;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		
	}

    public function accountsAction() {
        $this->view->assign('accounts', $this->accountRepository->findAll());
	}

    public function newAccountAction() {
        $roles = array();
        foreach($this->policyService->getRoles() as $role) {
            if((string)$role == "Everybody")
                continue;

            $roles[] = (string)$role;
        }
        $_SESSION['roles'] = $roles;
        $this->view->assign('allRoles', $roles);
    }

    /**
     * @param \TYPO3\FLOW3\Security\Account $account
     * @param $password
     * @param $roles
     * @return
     */
    public function createAccountAction(\TYPO3\FLOW3\Security\Account $account, $password, $roles) {
        if($account->getAccountIdentifier() == '' || strlen($account->getAccountIdentifier()) < 3) {
            $this->addFlashMessageError('Benutzername muss mindestens 3 Zeichen lang sein');
            $this->redirect('createAccount');
            return;
        } else if($password == '' || strlen($password) < 4) {
            $this->addFlashMessageError('Benutzername müssen mindestens 6 Zeichen lang sein');
            $this->redirect('createAccount');
            return;
        }

        $account = $this->accountFactory->createAccountWithPassword($account->getAccountIdentifier(), $password, $roles);

        $this->accountRepository->add($account);
        $this->redirect('accounts');
    }

    /**
     * @param \TYPO3\FLOW3\Security\Account $account
     * @return void
     */
    public function editAccountAction(\TYPO3\FLOW3\Security\Account $account) {
        $this->view->assign('account', $account);
        $this->view->assign('selectedRoles', $account->getRoles());
//        $roles = $this->policyService->getRoles();
//        unset($roles[array_search('Everybody', $roles)]);

        $roles = array();
        foreach($this->policyService->getRoles() as $role) {
            if((string)$role == "Everybody")
                continue;

            $roles[] = (string)$role;
        }
        $_SESSION['roles'] = $roles;

        $this->view->assign('allRoles', $roles);
        $arr = $this->helper->getSettings('VS.TimeSheet.employee.workingHoursMode');


        $this->view->assign('workingHoursModes', $this->helper->getSettings('VS.TimeSheet.employee.workingHoursMode'));
    }

    /**
     * @return void
     */
    public function initializeUpdateAccountAction() {
        //\TYPO3\FLOW3\var_dump($this->arguments['account']);
        $this->arguments['account']->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('party', '\VS\TimeSheet\Domain\Model\Employee');
        $this->arguments['account']->getPropertyMappingConfiguration()->allowModificationForSubProperty('party');
        $this->arguments['account']->getPropertyMappingConfiguration()->allowModificationForSubProperty('party.name');
    }

    /**
     * @param \TYPO3\FLOW3\Security\Account $account
     * @param string $password
     * @param array $roles
     * @return void
     */
    public function updateAccountAction(\TYPO3\FLOW3\Security\Account $account, $password = '', $roles = array()) {
        foreach ($account->getRoles() as $role) {
            $account->removeRole($role);
        }

        foreach ($roles as $role) {
            $account->addRole(new \TYPO3\FLOW3\Security\Policy\Role($_SESSION['roles'][$role]));
		}

        if (trim($password) != '') {
            $account->setCredentialsSource($this->hashService->hashPassword($password));
        }



        $this->accountRepository->update($account);
        $this->employeeRepository->update($account->getParty());
        $this->addFlashMessage('Benutzer wurde erfolgreich bearbeitet');
        $this->redirect('accounts');
    }

    public function deleteAccountAction(\TYPO3\FLOW3\Security\Account $account) {
        if($account->getAccountIdentifier() == $this->findCurrentAccount()->getAccountIdentifier()) {
            $this->addFlashMessageError('Sie können Ihren eigenen Benutzer nicht löschen!');
            $this->redirect('accounts');
            return;
        }

        $this->employeeRepository->update($account->getParty());
        $this->accountRepository->remove($account);
        $this->addFlashMessage('Benutzer wurde erfolgreich entfernt');
        $this->redirect('accounts');
    }







    public function categoriesAction() {
        $this->view->assign('categories', $this->taskRepository->findAll());
	}

    public function newCategoryAction() {
        $category = new \VS\TimeSheet\Domain\Model\Task();

        $this->view->assign('category', $category);
        $this->view->assign('parents', $this->helper->appendOptional($this->taskRepository->findAll()));
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Task $category
     * @return void
     */
    public function createCategoryAction(\VS\TimeSheet\Domain\Model\Task $category) {
        $this->taskRepository->add($category);
        $this->addFlashMessage('Kategorie wurde erfolgreich angelegt');
        $this->redirect('categories');
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Category $category
     * @return void
     */
    public function editCategoryAction(\VS\TimeSheet\Domain\Model\Task $category) {
        $this->view->assign('category', $category);
        $this->view->assign('parents', $this->helper->appendOptional($this->taskRepository->findAllExceptThis($category)));
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Task $category
     * @return void
     */
    public function updateCategoryAction(\VS\TimeSheet\Domain\Model\Task $category) {
        $this->taskRepository->update($category);
        $this->addFlashMessage('Kategorie wurde erfolgreich bearbeitet');
        $this->redirect('categories');
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Task $category
     * @return void
     */
    public function deleteCategoryAction(\VS\TimeSheet\Domain\Model\Task $category) {
        if(count($category->getChilds()) != 0) {
            $this->addFlashMessageError('Dieses Kategorie kann nicht gelöscht werden da noch Unterkategorien existieren');
            $this->redirect('category');
            return;
        }

        $this->taskRepository->remove($category);
        $this->addFlashMessage('Kategorie wurde erfolgreich entfernt');
        $this->redirect('categories');
    }
}

?>