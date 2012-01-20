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
class AdminTaskController extends \VS\TimeSheet\MVC\Controller\BasicJsonController {

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\ProjectRepository
     */
    protected $projectRepository;

    /**
     * @param \VS\TimeSheet\Domain\Model\Customer $customer
     * @return void
     */
    public function customerSelectedAction(\VS\TimeSheet\Domain\Model\Customer $customer) {
        $projects = array();
        $firstProject = null;
        foreach ($customer->getProjects() as $project) {
            if(is_null($firstProject))
                $firstProject = $project;

            $projects[] = array(
                'value' => $this->helper->getIdentifier($project),
                'text' => $project->getName()

            );
        }

        $this->view->assign('value', array(
            'projects' => $projects
        ));
    }
}

?>