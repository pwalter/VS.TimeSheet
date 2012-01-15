<?php
namespace VS\TimeSheet;

use \TYPO3\FLOW3\Package\Package as BasePackage;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Package base class of the VS.TimeSheet package.
 *
 * @FLOW3\Scope("singleton")
 */
class Package extends BasePackage {

    /**
     * @param \TYPO3\FLOW3\Core\Bootstrap $bootstrap
     */
    public function boot(\TYPO3\FLOW3\Core\Bootstrap $bootstrap) {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $dispatcher->connect('VS\TimeSheet\Controller\StandardController', 'activityCreated', 'VS\TimeSheet\Service\Notification', 'sendNewActivityNotification');
    }
}
?>