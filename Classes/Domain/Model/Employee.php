<?php
namespace VS\TimeSheet\Domain\Model;

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * @FLOW3\Entity
 */
class Employee extends \TYPO3\Party\Domain\Model\AbstractParty {

    /**
	 * @var \TYPO3\Party\Domain\Model\PersonName
	 * @ORM\OneToOne
	 * @FLOW3\Validate(type="NotEmpty")
	 */
	protected $name;

    /**
     * @var int
     */
    protected $minutesSunday;

    /**
     * @var int
     */
    protected $minutesMonday;

    /**
     * @var int
     */
    protected $minutesTuesday;

    /**
     * @var int
     */
    protected $minutesWednesday;

    /**
     * @var int
     */
    protected $minutesThursday;

    /**
     * @var int
     */
    protected $minutesFriday;

    /**
     * @var int
     */
    protected $minutesSaturday;


	/**
	 * Constructs this Person
	 *
	 */
	public function __construct() {
		parent::__construct();

        $this->setMinutesSunday(0);
        $this->setMinutesMonday(0);
        $this->setMinutesTuesday(0);
        $this->setMinutesWednesday(0);
        $this->setMinutesThursday(0);
        $this->setMinutesFriday(0);
        $this->setMinutesSaturday(0);
	}

	/**
	 * Sets the current name of this person
	 *
	 * @param \TYPO3\Party\Domain\Model\PersonName $name Name of this person
	 * @return void
	 */
	public function setName(\TYPO3\Party\Domain\Model\PersonName $name) {
		$this->name = $name;
	}

	/**
	 * Returns the current name of this person
	 *
	 * @return \TYPO3\Party\Domain\Model\PersonName Name of this person
	 */
	public function getName() {
		return $this->name;
	}

    /**
     * @param int $minutesFriday
     */
    public function setMinutesFriday($minutesFriday)
    {
        $this->minutesFriday = $minutesFriday;
    }

    /**
     * @return int
     */
    public function getMinutesFriday()
    {
        return $this->minutesFriday;
    }

    /**
     * @param int $minutesMonday
     */
    public function setMinutesMonday($minutesMonday)
    {
        $this->minutesMonday = $minutesMonday;
    }

    /**
     * @return int
     */
    public function getMinutesMonday()
    {
        return $this->minutesMonday;
    }

    /**
     * @param int $minutesSaturday
     */
    public function setMinutesSaturday($minutesSaturday)
    {
        $this->minutesSaturday = $minutesSaturday;
    }

    /**
     * @return int
     */
    public function getMinutesSaturday()
    {
        return $this->minutesSaturday;
    }

    /**
     * @param int $minutesSunday
     */
    public function setMinutesSunday($minutesSunday)
    {
        $this->minutesSunday = $minutesSunday;
    }

    /**
     * @return int
     */
    public function getMinutesSunday()
    {
        return $this->minutesSunday;
    }

    /**
     * @param int $minutesThursday
     */
    public function setMinutesThursday($minutesThursday)
    {
        $this->minutesThursday = $minutesThursday;
    }

    /**
     * @return int
     */
    public function getMinutesThursday()
    {
        return $this->minutesThursday;
    }

    /**
     * @param int $minutesTuesday
     */
    public function setMinutesTuesday($minutesTuesday)
    {
        $this->minutesTuesday = $minutesTuesday;
    }

    /**
     * @return int
     */
    public function getMinutesTuesday()
    {
        return $this->minutesTuesday;
    }

    /**
     * @param int $minutesWednesday
     */
    public function setMinutesWednesday($minutesWednesday)
    {
        $this->minutesWednesday = $minutesWednesday;
    }

    /**
     * @return int
     */
    public function getMinutesWednesday()
    {
        return $this->minutesWednesday;
    }

}
