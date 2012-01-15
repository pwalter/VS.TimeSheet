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
class StandardController extends \VS\TimeSheet\MVC\Controller\BasicController {

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
     * @FLOW3\Inject
     * @var \TYPO3\FLOW3\Security\AccountRepository
     */
    protected $accountRepository;

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\ActivityRepository
     */
    protected $activityRepository;

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\ProjectRepository
     */
    protected $projectRepository;

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
        $activity = new \VS\TimeSheet\Domain\Model\Activity();
        $now = new \DateTime(null, new \DateTimeZone('Europe/Berlin'));
        $account = $this->findCurrentAccount();

        if(!is_null($account)) {
            $activity->setAccount($account);
            $this->view->assign('accounts', array($account));
        } else {
            $this->accountRepository->setDefaultOrderings(array('accountIdentifier' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING));
            $accounts = $this->accountRepository->findAll();
            $this->view->assign('accounts', $this->helper->appendOptional($accounts));
        }

        /** Customer **/
        $customers = $this->customerRepository->findAll();
        $customerFirst = count($customers) != 0 ? $customers[0] : null;
        $this->view->assign('customers', $customers);

        /** Project for first Customer **/
        if(!is_null($customerFirst)) {
            $projects = $customerFirst->getProjects();
            $projectFirst = count($projects) != 0 ? $projects[0] : null;
            $this->view->assign('projects', $projects);

            if(!is_null($projectFirst)) {
                $activity->setProject($projectFirst);
                $tasks = array();
                foreach($projectFirst->getTasks() as $task) {
                    if($task->getActive())
                        $tasks[] = $task;
                }
                $this->view->assign('tasks', $tasks);
            }
        }

        $this->view->assign('activity', $activity);

        $this->view->assign('helptimespan', <<<LABEL
Dauer:
<ul>
    <li>"<strong>2h 15m</strong>" = 2 Stunden und 15 Minuten</li>
    <li>"<strong>1h</strong>" = 1 Stunde</li>
    <li>"<strong>50m</strong>" = 50 Minuten</li>
    <li>"<strong>10</strong>" = 10 Minuten</li>
    <li>"<strong>02:15</strong>" = 2 Stunden und 15 Minuten</li>
</ul>
<br />
Uhrzeiten:
<ul>
    <li>"<strong>12 - 14</strong>" = 12 Uhr bis 14 Uhr</li>
    <li>"<strong>12:30 - 14</strong>" = 12:30 Uhr bis 14 Uhr</li>
    <li>"<strong>12 - 14:45</strong>" = 12 Uhr bis 14:45 Uhr</li>
</ul>
LABEL
);

        if(!is_null($account)) {
            $this->view->assign('account', $account);

            $this->view->assign('date', $now);

            $activities = $this->activityRepository->findByDate(
                 $account,
                 $now
            );

            $sollMinutes = $this->helper->getEmployeeWorkingMinutesByDate($account->getParty(), $now);
            $this->view->assign('sollMinutes', $sollMinutes);

            $sumMinutes = 0;
            foreach($activities as $activity) {
                $sumMinutes += $activity->getMinutes();
            }
            $this->view->assign('istMinutes', $sumMinutes);

            /* Percentage */
            $percentage = 0;
            if($sollMinutes != 0)
                $percentage = round(100-((($sollMinutes-$sumMinutes)/$sollMinutes)*100), 2);

            $this->view->assign('percentageMinutes', $percentage);
        } else {
            $items = array();
            foreach($accounts as $account) {
                $sollMinutes = $this->helper->getEmployeeWorkingMinutesByDate($account->getParty(), $now);
                $activities = $this->activityRepository->findByDate($account, $now);
                $istMinutes = 0;
                foreach($activities as $activity) {
                    $istMinutes += $activity->getMinutes();
                }

                $deltaMinutes = $sollMinutes - $istMinutes;
                if($deltaMinutes <= 0)
                    continue;

                $items[] = array(
                    'account' => $account,
                    'missingMinutes' => $deltaMinutes
                );
            }
            $this->view->assign('items', $items);
        }
	}

    /**
     * @param \VS\TimeSheet\Domain\Model\Activity $activity
     * @return void
     */
    public function createActivityAction(\VS\TimeSheet\Domain\Model\Activity $activity) {
        $errors = false;

        if(is_null($activity->getAccount())) {
            $this->addFlashMessageError('Mitarbeiter muss angegeben sein!');
            $errors = true;
        }

        if($errors) {
            $this->redirect('index');
            return;
        }

        $this->activityRepository->add($activity);

        $this->addFlashMessage('Erfolgreich '.$this->helper->formatMinutesToTimespan($activity->getMinutes(), 'h m').' für '.$activity->getTask()->getName().' auf das Zeitkonto von '.$activity->getAccount()->getParty()->getName()->getFullName().' gebucht');
        $this->redirect('index');
    }

}

?>