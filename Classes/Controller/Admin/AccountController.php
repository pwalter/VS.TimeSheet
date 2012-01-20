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
class AccountController extends \VS\TimeSheet\MVC\Controller\BasicController {

    /**
     * @FLOW3\Inject
     * @var \TYPO3\FLOW3\Security\AccountRepository
     */
    protected $accountRepository;

    /**
     * @FLOW3\Inject
     * @var \TYPO3\FLOW3\Security\AccountFactory
     */
    protected $accountFactory;

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\EmployeeRepository
     */
    protected $employeeRepository;

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
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
        $accounts = $this->accountRepository->findAll();

		$this->view->assign('accounts', $accounts);

	}

    public function newAction() {
        $roles = array();
        foreach($this->policyService->getRoles() as $role) {
            if((string)$role == "Everybody")
                continue;

            $roles[] = $role;
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
    public function createAction(\TYPO3\FLOW3\Security\Account $account, $password, $roles) {
        if($account->getAccountIdentifier() == '' || strlen($account->getAccountIdentifier()) < 3) {
            $this->addFlashMessageError('Benutzername muss mindestens 3 Zeichen lang sein');
            $this->redirect('create');
            return;
        } else if($password == '' || strlen($password) < 4) {
            $this->addFlashMessageError('Benutzername müssen mindestens 6 Zeichen lang sein');
            $this->redirect('create');
            return;
        }

        $account = $this->accountFactory->createAccountWithPassword($account->getAccountIdentifier(), $password, $roles);

        $this->accountRepository->add($account);
        $this->redirect('index');
    }

    /**
     * @param \TYPO3\FLOW3\Security\Account $account
     * @return void
     */
    public function editAction(\TYPO3\FLOW3\Security\Account $account) {
        $this->view->assign('account', $account);

        $employee = $account->getParty();

        $selectedRoles = array();
        foreach($account->getRoles() as $role)
            $selectedRoles[] = (string)$role;
        $this->view->assign('selectedRoles', $selectedRoles);

        // Wochenarbeitszeit berechnen
        $weekdays = $this->helper->getSettings('VS.TimeSheet.weekdays');
        $sumMinutes = 0;
        foreach($weekdays as $key => $value) {
            $property = 'getMinutes'.ucfirst($key);
            $sumMinutes += $employee->$property();
        }
        $this->view->assign('weekHours', $sumMinutes);

        $roles = array();
        foreach($this->policyService->getRoles() as $role) {
            if((string)$role == "Everybody")
                continue;

            $roles[] = new \VS\TimeSheet\Security\Policy\Role((string)$role);
        }

        $this->view->assign('allRoles', $roles);
    }

    /**
     * @return void
     */
    public function initializeUpdateAction() {
        $this->arguments['account']->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('party', '\VS\TimeSheet\Domain\Model\Employee');
        $this->arguments['account']->getPropertyMappingConfiguration()->allowModificationForSubProperty('party');
        $this->arguments['account']->getPropertyMappingConfiguration()->allowModificationForSubProperty('party.name');
    }

    /**
     * @param \TYPO3\FLOW3\Security\Account $account
     * @param string $password
     * @param array $roles
     * @param array $hours
     * @return void
     */
    public function updateAction(\TYPO3\FLOW3\Security\Account $account, $password = '', $roles = array(), $hours = array()) {
        //\TYPO3\FLOW3\var_dump($roles);die();

        foreach ($account->getRoles() as $role) {
            $account->removeRole($role);
        }

        foreach ($roles as $role) {
            $account->addRole(new \TYPO3\FLOW3\Security\Policy\Role($role));
		}

        $party = $account->getParty();
        foreach($hours as $key => $value) {
            $minutes = $this->helper->getMinutesFromString($value);
            $property = 'setMinutes'.ucfirst($key);
            $party->$property($minutes);
        }

        if (trim($password) != '') {
            $account->setCredentialsSource($this->hashService->hashPassword($password));
        }

        $this->accountRepository->update($account);
        $this->employeeRepository->update($party);
        $this->addFlashMessage('Benutzer wurde erfolgreich bearbeitet');
        $this->redirect('index');
    }

    public function deleteAction(\TYPO3\FLOW3\Security\Account $account) {
        if($account->getAccountIdentifier() == $this->findCurrentAccount()->getAccountIdentifier()) {
            $this->addFlashMessageError('Sie können Ihren eigenen Benutzer nicht löschen!');
            $this->redirect('index');
            return;
        }

        $this->employeeRepository->update($account->getParty());
        $this->accountRepository->remove($account);
        $this->addFlashMessage('Benutzer wurde erfolgreich entfernt');
        $this->redirect('index');
    }
}

?>