<?php
namespace VS\TimeSheet\Command;

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @FLOW3\Scope("singleton")
 */
class TimesheetCommandController extends \TYPO3\FLOW3\MVC\Controller\CommandController {

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

    /**
     * @param string $identifier
     * @param string $password
     * @param string $firstName
     * @param string $lastName
     * @param string $role
     * @return void
     */
    public function createAccountCommand($identifier, $password, $firstName, $lastName, $role = 'Employee') {
        $account = $this->accountFactory->createAccountWithPassword($identifier, $password, array($role));
        $this->accountRepository->add($account);
        $personName = new \TYPO3\Party\Domain\Model\PersonName('', $firstName, '', $lastName);

        $employee = new \VS\TimeSheet\Domain\Model\Employee();
        $employee->setName($personName);
        $employee->addAccount($account);
        $employee->setHoursMonday(8);
        $employee->setHoursThursday(8);
        $employee->setHoursWednesday(8);
        $employee->setHoursTuesday(8);
        $employee->setHoursFriday(8);
        $this->employeeRepository->add($employee);
        
        $this->outputLine('The account "%s" was created.', array($identifier));
    }

    /**
     * @return void
     */
    public function initCommand() {
        // Project Template
        /*$projectTemplate = new \VS\TimeSheet\Domain\Model\ProjectTemplate('Allgemeine Aufgaben');
        $projectTemplate->addTask(new \VS\TimeSheet\Domain\Model\Task('Anpassung'));
        $projectTemplate->addTask(new \VS\TimeSheet\Domain\Model\Task('Überprüfung'));
        $projectTemplate->addTask(new \VS\TimeSheet\Domain\Model\Task('Reinigung'));

        $this->projectTemplateRepository->add($projectTemplate);
        $this->outputLine('Project template created');*/

        // HDH Customer
        $customerHdH = new \VS\TimeSheet\Domain\Model\Customer();
        $customerHdH->setNumber('0');
        $customerHdH->setCode('HdH');
        $customerHdH->setName(new \TYPO3\Party\Domain\Model\PersonName(null, 'Haus des Hörens', null, null));

        $project0 = new \VS\TimeSheet\Domain\Model\Project('--- allgemein ---');
        $project0->setNumber('0');
        $project0->setCode('HdH-P01');
        //$project0->setProjectTemplate($projectTemplate);
        $customerHdH->addProject($project0);

        $this->customerRepository->add($customerHdH);
        $this->outputLine('HdH customer created');

        // Generic Customer
        $customerKD = new \VS\TimeSheet\Domain\Model\Customer();
        $customerKD->setNumber('1');
        $customerKD->setCode('KD');
        $customerKD->setName(new \TYPO3\Party\Domain\Model\PersonName(null, 'Kunde', null, null));

        $project1 = new \VS\TimeSheet\Domain\Model\Project('--- allgemein ---');
        $project1->setNumber('1');
        $project1->setCode('KD-P01');
        //$project0->setProjectTemplate($projectTemplate);
        $customerKD->addProject($project1);

        $this->customerRepository->add($customerKD);
        $this->outputLine('KD customer created');

        foreach($this->getTasks() as $task) {
            $task = new \VS\TimeSheet\Domain\Model\Task($task['name']);
            $project = $this->projectRepository->findSingleByCustomerNumber($task['customer']);
            $this->taskRepository->add($task);
            $project->addTask($task);

            $this->projectRepository->update($project);

            $this->outputLine('Created task \'%s\' for customer %s', array($task->getName(), $project->getCustomer()->getName()->getFullName()));
        }
    }

    protected function getTasks() {
        return array(
            array('name' => 'Abdrucknahme', 'customer' => '1'),
            array('name' => 'Anpassung', 'customer' => '1'),
            array('name' => 'Anpasspapiere', 'customer' => '1'),
            array('name' => 'Aquise Neukunden', 'customer' => '0'),
            array('name' => 'Aufräumen/Saubermachen/Reparaturen', 'customer' => '0'),
            array('name' => 'Bankverkehr', 'customer' => '0'),
            array('name' => 'Beratung Hörgeräte', 'customer' => '0'),
            array('name' => 'Beratung Schallschutz', 'customer' => '0'),
            array('name' => 'Beratung Tinnitus', 'customer' => '0'),
            array('name' => 'Beratung Zubehör', 'customer' => '0'),
            array('name' => 'Besorgung', 'customer' => '0'),
            array('name' => 'Besprechung', 'customer' => '0'),
            array('name' => 'Buchhaltung', 'customer' => '0'),
            array('name' => 'Gespräch mit Kunden', 'customer' => '0'),
            array('name' => 'Hausbesuch', 'customer' => '1'),
            array('name' => 'Hörtest', 'customer' => '1'),
            array('name' => 'Kasse', 'customer' => '0'),
            array('name' => 'Kostenvoranschlag', 'customer' => '1'),
            array('name' => 'Laborarbeiten', 'customer' => '1'),
            array('name' => 'Lagerüberprüfung', 'customer' => '0'),
            array('name' => 'Marketing', 'customer' => '0'),
            array('name' => 'Montags-Meeting', 'customer' => '0'),
            array('name' => 'Nacheinstellung Hörgeräte', 'customer' => '1'),
            array('name' => 'Posteingang/-ausgang', 'customer' => '0'),
            array('name' => 'Qualitätsmanagement', 'customer' => '0'),
            array('name' => 'Rechnung', 'customer' => '1'),
            array('name' => 'Reparatur', 'customer' => '1'),
            array('name' => 'Telefonat', 'customer' => '1'),
            array('name' => 'Überprüfung Hörgeräte', 'customer' => '1'),
            array('name' => 'Überprüfung Zubehör', 'customer' => '1'),
            array('name' => 'Veranstaltung für Kunden, Ärzte usw.', 'customer' => '0'),
            array('name' => 'Verkauf Batterien, Zubehör usw.', 'customer' => '1'),
            array('name' => 'Warenbewegung', 'customer' => '0')
        );
    }
}
