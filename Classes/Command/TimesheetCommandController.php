<?php
namespace VS\TimeSheet\Command;

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @FLOW3\Scope("singleton")
 */
class TimesheetCommandController extends \TYPO3\FLOW3\Cli\CommandController {

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
}
