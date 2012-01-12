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
class SetupController extends \VS\TimeSheet\MVC\Controller\BasicController {
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
     * @var \VS\TimeSheet\Domain\Repository\ProjectRepository
     */
    protected $projectRepository;

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\TagRepository
     */
    protected $tagRepository;

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\CustomerRepository
     */
    protected $customerRepository;

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\TaskRepository
     */
    protected $taskRepository;

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Domain\Repository\ProjectTemplateRepository
     */
    protected $projectTemplateRepository;

    public function initAccountsAction() {
        foreach($this->getAccounts() as $acc) {
            $account = $this->accountFactory->createAccountWithPassword($acc['login'], $acc['login'], array($acc['role']));
            $this->accountRepository->add($account);
            $personName = new \TYPO3\Party\Domain\Model\PersonName('', $acc['firstname'], '', $acc['lastname']);

            $employee = new \VS\TimeSheet\Domain\Model\Employee();
            $employee->setName($personName);
            $employee->addAccount($account);
            $employee->setMinutesSunday($acc['hours'][0] * 60);
            $employee->setMinutesMonday($acc['hours'][1] * 60);
            $employee->setMinutesTuesday($acc['hours'][2] * 60);
            $employee->setMinutesWednesday($acc['hours'][3] * 60);
            $employee->setMinutesThursday($acc['hours'][4] * 60);
            $employee->setMinutesFriday($acc['hours'][5] * 60);
            $employee->setMinutesSaturday($acc['hours'][6] * 60);
            $this->employeeRepository->add($employee);
        }
    }

    protected function getAccounts() {
        return array(
            array(
                'login' => 'pw',
                'firstname' => 'Pascal',
                'lastname' => 'Walter',
                'role' => 'Administrator',
                'hours' => array(0,0,0,0,0,0,0)
            ),
            array(
                'login' => 'abw',
                'firstname' => 'Anke',
                'lastname' => 'Bünting-Walter',
                'role' => 'Employee',
                'hours' => array(0,8.5,8.5,6,8.5,8.5,0)
            ),
            array(
                'login' => 'js',
                'firstname' => 'Johanna',
                'lastname' => 'Schmidt',
                'role' => 'Employee',
                'hours' => array(0,4,7.5,9.5,4,4,0)
            ),
            array(
                'login' => 'dg',
                'firstname' => 'David',
                'lastname' => 'Gnirs',
                'role' => 'Employee',
                'hours' => array(0,7.5,7,9.5,9.5,6.5,0)
            ),
            array(
                'login' => 'kg',
                'firstname' => 'Katrin',
                'lastname' => 'Guldenschuh',
                'role' => 'Employee',
                'hours' => array(0,9.5,9.5,7,6.5,7.5,0)
            ),
            array(
                'login' => 'pp',
                'firstname' => 'Patricia',
                'lastname' => 'Peters',
                'role' => 'Employee',
                'hours' => array(0,9.5,7,7.5,9.5,6.5,0)
            ),
            array(
                'login' => 'su',
                'firstname' => 'Samra',
                'lastname' => 'Uhlmann',
                'role' => 'Employee',
                'hours' => array(0,9,8,7,9,7,0)
            ),
            array(
                'login' => 'gpk',
                'firstname' => 'Gloria',
                'lastname' => 'Peil-Kratz',
                'role' => 'Employee',
                'hours' => array(0,5.5,5,9.5,5,5,0)
            ),
            array(
                'login' => 'sm',
                'firstname' => 'Serafin',
                'lastname' => 'Melynski',
                'role' => 'Employee',
                'hours' => array(0,8.5,9.5,6,7.5,8.5,0)
            ),
            array(
                'login' => 'ms',
                'firstname' => 'Marcel',
                'lastname' => 'Scheib',
                'role' => 'Employee',
                'hours' => array(0,6.5,9,6.5,9,6.9,0)
            )
        );
    }

    /**
     * @return void
     */
    public function initAction() {
        // HDH Customer
        $customerHdH = new \VS\TimeSheet\Domain\Model\Customer();
        $customerHdH->setNumber('0');
        $customerHdH->setCode('HdH');
        $customerHdH->setName(new \TYPO3\Party\Domain\Model\PersonName(null, 'Haus des Hörens', null, null));

        $project0 = new \VS\TimeSheet\Domain\Model\Project('Verwaltung');
        $project0->setNumber('0');
        $project0->setCode('Intern');

        $project1 = new \VS\TimeSheet\Domain\Model\Project('Tätigkeit für den Kunden');
        $project1->setNumber('1');
        $project1->setCode('KD');

        foreach($this->getTasks() as $taskItem) {
            $task = new \VS\TimeSheet\Domain\Model\Task($taskItem['name']);

            if($taskItem['project'] == 0)
                $project = $project0;
            else
                $project = $project1;
            $this->taskRepository->add($task);

            $project->addTask($task);
        }
        $customerHdH->addProject($project0);
        $customerHdH->addProject($project1);
        $this->customerRepository->add($customerHdH);


    }

    protected function getTasks() {
        return array(
            array('name' => 'Abdrucknahme', 'project' => '1'),
            array('name' => 'Anpassung', 'project' => '1'),
            array('name' => 'Anpasspapiere', 'project' => '1'),
            array('name' => 'Aquise Neukunden', 'project' => '0'),
            array('name' => 'Aufräumen/Saubermachen/Reparaturen', 'project' => '0'),
            array('name' => 'Bankverkehr', 'project' => '0'),
            array('name' => 'Beratung Hörgeräte', 'project' => '1'),
            array('name' => 'Beratung Schallschutz', 'project' => '1'),
            array('name' => 'Beratung Tinnitus', 'project' => '1'),
            array('name' => 'Beratung Zubehör', 'project' => '1'),
            array('name' => 'Besorgung', 'project' => '0'),
            array('name' => 'Besprechung', 'project' => '0'),
            array('name' => 'Buchhaltung', 'project' => '0'),
            array('name' => 'Gespräch mit Kunden', 'project' => '1'),
            array('name' => 'Hausbesuch', 'project' => '1'),
            array('name' => 'Hörtest', 'project' => '1'),
            array('name' => 'Kasse', 'project' => '0'),
            array('name' => 'Kostenvoranschlag', 'project' => '1'),
            array('name' => 'Laborarbeiten', 'project' => '1'),
            array('name' => 'Lagerüberprüfung', 'project' => '0'),
            array('name' => 'Marketing', 'project' => '0'),
            array('name' => 'Montags-Meeting', 'project' => '0'),
            array('name' => 'Nacheinstellung Hörgeräte', 'project' => '1'),
            array('name' => 'Posteingang/-ausgang', 'project' => '0'),
            array('name' => 'Qualitätsmanagement', 'project' => '0'),
            array('name' => 'Rechnung', 'project' => '1'),
            array('name' => 'Reparatur', 'project' => '1'),
            array('name' => 'Telefonat', 'project' => '1'),
            array('name' => 'Überprüfung Hörgeräte', 'project' => '1'),
            array('name' => 'Überprüfung Zubehör', 'project' => '1'),
            array('name' => 'Veranstaltung für Kunden, Ärzte usw.', 'project' => '0'),
            array('name' => 'Verkauf Batterien, Zubehör usw.', 'project' => '1'),
            array('name' => 'Warenbewegung', 'project' => '0')
        );
    }
}

?>