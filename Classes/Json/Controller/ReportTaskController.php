<?php
namespace VS\TimeSheet\Json\Controller;

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
class ReportTaskController extends \VS\TimeSheet\MVC\Controller\BasicJsonController {

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
     * @var \VS\TimeSheet\Core\Sorter
     */
    protected $sorter;

    /**
     * @return void
     */
    public function getTaskChartDataAction() {
        $allProjects = $this->projectRepository->findAll();

        $from = new \DateTime();
        $from->modify('-7 days');

        $to = new \DateTime();

        $projects = array();
        $tasks = array();
        foreach($allProjects as $project) {
            $hoursProject = 0.0;
            $projectTasks = array();

            // Add task
            $sumOthers = 0.0;
            foreach($project->getTasks() as $task) {
                $activities = $this->activityRepository->findAllByTask($task, $from, $to);
                $hours = 0.0;
                foreach($activities as $activity)
                    $hours += $activity->getMinutes() / 60;

                $hoursProject += $hours;

                if($hours > 3) {
                    $projectTasks[] = array(
                        'name' => $task->getName(),
                        'code' => $task->getCode(),
                        'y' => (int)$hours,
                        'color' => $task->getColor()
                    );
                } else {
                    $sumOthers += $hours;
                }
            }

            $projectTasks[] = array(
                'name' => 'Sonstige',
                'code' => 'sont',
                'y' => (int)$sumOthers,
                'color' => '#ffffff'
            );

            $tasks = array_merge($tasks, $this->sorter->sort($projectTasks, 'y'));

            // Add project data
            $projects[] = array(
                'name' => $project->getName(),
                'code' => $project->getCode(),
                'y' => (int)$hoursProject,
                'color' => $project->getColor()
            );

            //$tasks = $this->sorter->sort($tasks, 'y');
        }

        $this->view->assign('value', array(
            'projects' => $projects,
            'tasks' => $tasks
        ));
    }
}

?>