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

        /*
        $activities = $this->activityRepository->findBetweenDates(
             $this->findCurrentAccount(),
             $date1,
             $date2
        );

        $sumMinutes = 0;
        foreach($activities as $activity)
            $sumMinutes += $activity->getMinutes();
        $this->view->assign('istMinutes', $sumMinutes);
        */

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