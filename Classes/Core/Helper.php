<?php
namespace VS\TimeSheet\Core;

use TYPO3\FLOW3\Annotations as FLOW3;

class Helper {
	/**
	 * @FLOW3\Inject
     * @var TYPO3\FLOW3\Cache\CacheManager
	 */
	protected $cacheManager;
	
	/**
	 * @FLOW3\Inject
     * @var \TYPO3\FLOW3\Configuration\ConfigurationManager
	 */
	protected $configurationManager;

	/**
	 * @FLOW3\Inject
     * @var \TYPO3\FLOW3\Object\ObjectManagerInterface
	 */
	protected $objectManager;

    /**
     * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * Returns a setting property
	 *
	 * @param $namespace String Namespace
	 * @return $settings Array of settings
	 * @author Marc Neuhaus <apocalip@gmail.com>
	 **/
	public function getSettings($namespace = "VS.TimeSheet"){
		if(!isset($this->cache["settings"]) || !isset($this->cache["settings"][$namespace])){
			$this->cache["settings"][$namespace] = $this->configurationManager->getConfiguration(\TYPO3\FLOW3\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, $namespace);
		}
		return $this->cache["settings"][$namespace];
	}

    /**
     * @param $array
     * @param null $value
     * @param string $text
     * @return array
     */
    public function appendOptional($array, $value = null, $text = '--- Bitte wählen ---') {
        if(!is_array($array))
            $array = $array->toArray();

        return array_merge(array($value => $text), $array);
    }

    public function formatDate(\DateTime $date, $format = null) {
        $weekday = lcfirst($date->format('l'));
        $weekdays = $this->getSettings('VS.TimeSheet.weekdays');

        return $weekdays[$weekday].', '.$date->format('d.m.Y');
    }

    /**
     * @param int $minutes
     * @param string $format
     * @return string
     */
    public function formatMinutesToTimespan($minutes, $format = 'hh:mm') {
        $totalMinutes = (int)$minutes;
        $hours = (int)($totalMinutes / 60);
        $minutes = (int)($totalMinutes % 60);

        switch($format) {
            case 'h m':
                return $hours.'h'.($minutes == 0 ? '' : ' '.$minutes.'m');

            default:
            case 'hh:mm':
                return (strlen((string)$hours) == 1 ? '0'.$hours : $hours).':'.(strlen((string)$minutes) == 1 ? '0'.$minutes : $minutes);
        }
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Employee $employee
     * @param \DateTime $from
     * @param \DateTime $to
     * @return void
     */
    public function getEmployeeWorkingMinutes(\VS\TimeSheet\Domain\Model\Employee $employee, \DateTime $from, \DateTime $to) {
        $from = clone $from;
        $to = clone $to;

        $minutes = 0;

        $intervall = new \DateInterval('P1D');

        $days = $to->diff($from)->days;
        for($i = 0; $i <= $days; $i++) {
            $propertyName = 'getMinutes'.$from->format('l');
            $minutes += $employee->$propertyName();

            $from->add($intervall);
        }

        return $minutes;
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Employee $employee
     * @param \DateTime $date
     * @return int
     */
    public function getEmployeeWorkingMinutesByDate(\VS\TimeSheet\Domain\Model\Employee $employee, \DateTime $date) {
        $propertyName = 'getMinutes'.$date->format('l');

        $minutes = $employee->$propertyName();

        return $minutes;
    }

    /**
     * @throws \TYPO3\Fluid\Exception
     * @param $object
     * @return string
     */
    public function getIdentifier($object) {
        if (!is_object($object)) {
			throw new \TYPO3\FLOW3\Exception('getIdentity expects an object, ' . \gettype($object) . ' given.', 1277830439);
		}
		$identifier = $this->persistenceManager->getIdentifierByObject($object);
		if ($identifier === NULL) {
			return \TYPO3\FLOW3\Exception('Identifier is null - only persisted objects have them!');
		} else {
			return $identifier;
		}
    }

    /**
     * @param $text
     */
    public function getMinutesFromString($text) {
        $minutes = 0;

        // Syntax: 1h 40m
        if(preg_match('|^((?P<hours>[0-9]+)[sh])?[ ]*((?P<minutes>\d+)m)?$|i', $text, $matches)) {
            if(isset($matches['hours'])) {
                $minutes = ((int)$matches['hours']) * 60;
            }

            if(isset($matches['minutes'])) {
                $minutes += (int)$matches['minutes'];
            }
        }

        // Syntax: 13(:00)-14(:00)
        if(preg_match('|^(?P<from>\d{1,2}(:\d{1,2})?)[ ]?-[ ]?(?P<to>\d{1,2}(:\d{1,2})?)$|i', $text, $matches)) {
            //die($matches['from'].'-'.$matches['to']);
            $from = new \DateTime(preg_match('|:|', $matches['from']) ? $matches['from'] : $matches['from'].':00');
            $to = new \DateTime(preg_match('|:|', $matches['to']) ? $matches['to'] : $matches['to'].':00');

            $diff = $to->diff($from);

            $minutes = $diff->h * 60;
            $minutes += $diff->i;
        }

        // Syntax: 01:40
        if(preg_match('|^(?P<hours>[0-9]+):(?P<minutes>[0-9]+)$|i', $text, $matches)) {
            if(isset($matches['hours'])) {
                $minutes = ((int)$matches['hours']) * 60;
            }

            if(isset($matches['minutes'])) {
                $minutes += (int)$matches['minutes'];
            }
        }

        return $minutes;
    }
}

?>