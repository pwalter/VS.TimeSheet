<?php
namespace VS\TimeSheet\Session;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @FLOW3\Scope("session")
 */
class ReportFilterSession
{
    /**
     * @var \DateTime $dateFrom
     */
    protected $dateFrom;

    /**
     * @var \DateTime $dateTo
     */
    protected $dateTo;

    /**
     * @var \VS\TimeSheet\Domain\Model\Customer
     */
    protected $customer;

    /**
     * @var \VS\TimeSheet\Domain\Model\Project
     */
    protected $project;

    /**
     * @var \TYPO3\FLOW3\Security\Account
     */
    protected $account;

    public function __construct() {
        $this->initialize();
    }

    public function initialize() {
        $this->reset();
    }

    public function reset() {
        $this->dateFrom = new \DateTime();
        $this->dateFrom->modify('-7 days');

        $this->dateTo = new \DateTime();
        $this->account = NULL;
    }

    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return \TYPO3\FLOW3\Security\Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return \VS\TimeSheet\Domain\Model\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    public function setProject($project)
    {
        $this->project = $project;
    }

    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param \DateTime $dateFrom
     */
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }

    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * @param \DateTime $dateTo
     */
    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;
    }

    public function getDateTo()
    {
        return $this->dateTo;
    }
}
