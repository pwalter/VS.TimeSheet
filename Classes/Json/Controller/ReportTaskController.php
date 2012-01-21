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
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Session\ReportFilterSession
     */
    protected $filterSession;

    /**
     * @return void
     */
    public function getTaskChartDataAction() {
        $allProjects = $this->projectRepository->findAll();

        $projects = array();
        $tasks = array();
        foreach($allProjects as $project) {
            $hoursProject = 0.0;
            $projectTasks = array();

            // Add task
            foreach($project->getTasks() as $task) {
                $activities = $this->activityRepository->findAllByTask(
                    $task,
                    $this->filterSession->getAccount(),
                    $this->filterSession->getDateFrom(),
                    $this->filterSession->getDateTo());
                $hours = 0.0;
                foreach($activities as $activity)
                    $hours += $activity->getMinutes() / 60;

                if($hours == 0.0)
                    continue;

                $hoursProject += $hours;
                $projectTasks[] = array(
                    'name' => $task->getName(),
                    'code' => $task->getCode(),
                    'y' => (int)$hours,
                    'color' => $task->getColor() == '' ? \VS\TimeSheet\Core\ColorGenerator::randomLightGray() : $task->getColor()
                );
            }

            $projectTasksResult = array(); // Reset
            $threshold = is_null($this->filterSession->getAccount()) ? 3 : 0.5;
            $sumOtherHours = 0.0;
            foreach($projectTasks as $projectTask) {
                if($projectTask['y'] >= $threshold)
                    $projectTasksResult[] = $projectTask;
                else
                    $sumOtherHours += $projectTask['y'];
            }

            if($sumOtherHours > 0){
                $projectTasksResult[] = array(
                    'name' => 'Sonstige',
                    'code' => 'SONST',
                    'y' => (int)$sumOtherHours,
                    'color' => '#e8e8e8'
                );
            }

            $tasks = array_merge($tasks, $this->sorter->sort($projectTasksResult, 'y'));

            if($hoursProject == 0.0)
                continue;

            // Add project data
            $projects[] = array(
                'name' => $project->getName(),
                'code' => $project->getCode(),
                'y' => (int)$hoursProject,
                'color' => $project->getColor() == '' ? \VS\TimeSheet\Core\ColorGenerator::randomLightGray() : $project->getColor()
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