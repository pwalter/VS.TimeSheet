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

        $activities = $this->activityRepository->findBetweenDates(
             $this->findCurrentAccount(),
             $date1,
             $date2
        );

        $sumMinutes = 0;
        foreach($activities as $activity)
            $sumMinutes += $activity->getMinutes();
        $this->view->assign('istMinutes', $sumMinutes);

        $sollMinutes = $this->helper->getEmployeeWorkingMinutes($this->findCurrentAccount()->getParty(), $date1, $date2);

        $this->view->assign('sollMinutes', $sollMinutes);

        /* Percentage */
        $percentage = 0;
        if($sollMinutes != 0)
            $percentage = round(100-((($sollMinutes-$sumMinutes)/$sollMinutes)*100), 2);

        $this->view->assign('percentageMinutes', $percentage);
        $this->view->assign('weekdays', $date2->diff($date1)->days+1);
        $this->view->assign('activities', $activities);

        $this->view->assign('dateFrom', $this->helper->formatDate($date1));
        $this->view->assign('dateTo', $this->helper->formatDate($date2));

        // Filter
        $this->view->assign('filter', array(
            'dateFrom' => $date1->format('d.m.Y'),
            'dateTo' => $date2->format('d.m.Y')
        ));
	}

    /**
     * @return void
     */
    public function createAction() {
        
    }
}

?>