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
class AccountController extends \VS\TimeSheet\MVC\Controller\BasicController {
	/**
     * @FLOW3\Inject
     * @var \TYPO3\FLOW3\Security\Authentication\AuthenticationManagerInterface
     */
    protected $authenticationManager;

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
     * @var \TYPO3\FLOW3\Security\Cryptography\HashService
     */
    protected $hashService;

    /**
     * @return void
     */
	public function profileAction() {
		$account = $this->findCurrentAccount();
        $this->view->assign('account', $account);
	}

    public function initializeUpdateProfileAction() {
        $this->arguments['account']->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('party', '\VS\TimeSheet\Domain\Model\Employee');
        $this->arguments['account']->getPropertyMappingConfiguration()->allowModificationForSubProperty('party');
        $this->arguments['account']->getPropertyMappingConfiguration()->allowModificationForSubProperty('party.name');
    }

    /**
     * @param \TYPO3\FLOW3\Security\Account $account
     * @param string $password
     * @return void
     */
    public function updateProfileAction(\TYPO3\FLOW3\Security\Account $account, $password = '') {
        if (count(trim($password)) != 0) {
            $account->setCredentialsSource($this->hashService->hashPassword($password));
        }

        //\TYPO3\FLOW3\var_dump($account);

        try {
            $this->accountRepository->update($account);
            $this->employeeRepository->update($account->getParty());
            $this->addFlashMessage('Sie haben Ihr Profil erfolgreich bearbeitet!');
            $this->redirect('index', 'Standard');
        } catch (Exception $e) {
            $this->addFlashMessageError($e->getMessage());
            return;
        }

    }



    /**
     * @throws \TYPO3\FLOW3\Security\Exception\AuthenticationRequiredException
     * @return void
     */
    public function loginAction() {
        try {
            $this->authenticationManager->authenticate();
            $this->redirect('index', 'Standard');
        } catch (\TYPO3\FLOW3\Security\Exception\AuthenticationRequiredException $exception) {
            $this->addFlashMessageError('Benutzername und/oder Passwort stimmen leider nicht!');
            $this->redirect('index', 'Standard');
            throw $exception;
        }
	}

    public function logoutAction() {
        $this->authenticationManager->logout();
        $this->addFlashMessage('Sie sind erfolgreich abgemeldet!');
        $this->redirect('index', 'Standard');
	}
}

?>