<?php
namespace VS\TimeSheet\Controller\Report;

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
     * @var \TYPO3\FLOW3\Security\AccountRepository
     */
    protected $accountRepository;

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Session\ReportFilterSession
     */
    protected $filterSession;


    public function indexAction() {
        $this->view->assign('accounts', $this->helper->appendOptional($this->accountRepository->findAll()));
        $this->view->assign('filter', $this->filterSession);
    }

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @param \TYPO3\FLOW3\Security\Account $account
     */
    public function filterAction($dateFrom = NULL, $dateTo = NULL, $account = NULL) {
        $timezone = new \DateTimeZone('Europe/Berlin');

        if(!is_null($dateFrom)) {
            $this->filterSession->setDateFrom(new\DateTime($dateFrom, $timezone));
        }

        if(!is_null($dateTo)) {
            $this->filterSession->setDateTo(new\DateTime($dateTo, $timezone));
        }

        // todo: This is required because once the object is in the Session, Doctrine Lazy-Loading will not work :-/
        if(!is_null($account)) {
            $account->getParty()->getName()->getFullName();
        } else {
            $account = NULL;
        }
        $this->filterSession->setAccount($account);

        $this->redirect('index');
    }

    /**
     * Reset all filters in the session
     */
    public function filterResetAction() {
        $this->filterSession->reset();
        $this->redirect('index');
    }
}

?>