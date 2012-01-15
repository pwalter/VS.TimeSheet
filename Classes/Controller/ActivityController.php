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
class ActivityController extends \VS\TimeSheet\MVC\Controller\BasicController {

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\TaskRepository
     */
    protected $taskRepository;

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\ActivityRepository
     */
    protected $activityRepository;

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
     * @FLOW3\Inject
     * @var VS\TimeSheet\Core\Helper
     */
    protected $helper;

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @return void
     */
    public function listAction($dateFrom = '', $dateTo = '') {
        $timezone = new \DateTimeZone('Europe/Berlin');
        $account = $this->findCurrentAccount();

        // Calculate dates to display between
        if($dateFrom == '') {
            // If today is sunday, we need to set first day of week to Monday last week
            $now = new \DateTime(null, $timezone);
            $correctDay = $now->format('l') === 'Sunday';

            $date1 = new \DateTime('Monday this week', $timezone);

            if($correctDay) {
                $date1->modify('-1 week');
            }
        } else {
            $date1 = new \DateTime($dateFrom, $timezone);
        }
        
        if($dateTo == '') {
            $date2 = clone $date1;
            $date2->modify('+6 day');
        } else {
            $date2 = new \DateTime($dateTo, $timezone);
        }

        $date1->setTime(0,0,0);
        $date2->setTime(23,59,59);

        /**************************** Table ****************************/
        // Search activities for each day separate
        $weekdays = $date2->diff($date1)->days;
        $sumTotalMinutes = 0;

        $days = array();
        for($i = $weekdays; $i >= 0; $i--) {
            $date = clone $date1;
            $date = $date->modify('+'.$i.' days');

            $activities = $this->activityRepository->findByDate($account, $date);
            $sumMinutes = 0;
            foreach($activities as $activity)
                $sumMinutes += $activity->getMinutes();

            $sumTotalMinutes += $sumMinutes;

            $days[$i] = array(
                'date' => $date,
                'activities' => $activities,
                'sumMinutes' => $sumMinutes
            );
        }
        $this->view->assign('days', $days);
        $this->view->assign('istMinutes', $sumTotalMinutes);

        //\TYPO3\FLOW3\var_dump($days);die();

        /**************************** Sidebar ****************************/
        // Calculate soll working hours
        $sollMinutes = $this->helper->getEmployeeWorkingMinutes($account->getParty(), $date1, $date2);

        $this->view->assign('sollMinutes', $sollMinutes);

        /* Percentage */
        $percentage = 0;
        if($sollMinutes != 0)
            $percentage = round(100-((($sollMinutes-$sumMinutes)/$sollMinutes)*100), 2);

        $this->view->assign('percentageMinutes', $percentage);
        $this->view->assign('weekdays', $weekdays);

        $this->view->assign('dateFrom', $this->helper->formatDate($date1));
        $this->view->assign('dateTo', $this->helper->formatDate($date2));

        // Filter
        $this->view->assign('filter', array(
            'dateFrom' => $date1->format('d.m.Y'),
            'dateTo' => $date2->format('d.m.Y')
        ));
	}

    /**
     * @param \VS\TimeSheet\Domain\Model\Activity $activity
     */
    public function editAction(\VS\TimeSheet\Domain\Model\Activity $activity) {
        $this->view->assign('activity', $activity);
        /** Customer **/
        $customers = $this->customerRepository->findAll();
        $this->view->assign('customers', $customers);

        $this->view->assign('helptimespan', <<<LABEL
Dauer:
<ul>
    <li><strong>2h 15m</strong> = 2 Stunden und 15 Minuten</li>
    <li><strong>1h</strong> = 1 Stunde</li>
    <li><strong>50m</strong> = 50 Minuten</li>
    <li><strong>10</strong> = 10 Minuten</li>
    <li><strong>02:15</strong> = 2 Stunden und 15 Minuten</li>
</ul>
<br />
Uhrzeiten:
<ul>
    <li><strong>12 - 14</strong> = 12 Uhr bis 14 Uhr</li>
    <li><strong>12:30 - 14</strong> = 12:30 Uhr bis 14 Uhr</li>
    <li><strong>12 - 14:45</strong> = 12 Uhr bis 14:45 Uhr</li>
</ul>
LABEL
);

        /** Project for first Customer **/
        $projects = $activity->getProject()->getCustomer()->getProjects();
        $this->view->assign('projects', $projects);

        $tasks = array();
        foreach($activity->getProject()->getTasks() as $task) {
            if($task->getActive())
                $tasks[] = $task;
        }
        $this->view->assign('tasks', $tasks);
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Activity $activity
     * @return void
     */
    public function updateAction(\VS\TimeSheet\Domain\Model\Activity $activity) {

        $this->activityRepository->update($activity);

        $this->addFlashMessage('Tätigkeit erfolgreich bearbeitet');
        $this->redirect('list');
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Activity $activity
     * @return void
     */
    public function deleteAction(\VS\TimeSheet\Domain\Model\Activity $activity) {
        $this->activityRepository->remove($activity);
        $this->addFlashMessage('Tätigkeit wurde erfolgreich gelöscht');
        $this->redirect('list');
    }

    /**
     * @return void
     */
    public function createAction() {
        
    }
}

?>