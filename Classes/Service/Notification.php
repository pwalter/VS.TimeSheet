<?php
namespace VS\TimeSheet\Service;

use \TYPO3\FLOW3\Package\Package as BasePackage;
use TYPO3\FLOW3\Annotations as FLOW3;


class Notification {

    /**
     * @var array
     */
    protected $settings;

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Core\Helper
     */
    protected $helper;

    /**
     * @param array $settings
     */
    public function injectSettings(array $settings) {
        $this->settings = $settings;
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Activity $activity
     */
    public function sendNewActivityNotification(\VS\TimeSheet\Domain\Model\Activity $activity) {

        if ($this->settings['notifications']['activityCreate']['to']['email'] === '') return;

        $subject = 'Tätigkeit erfasst von ' . $activity->getAccount()->getParty()->getName()->getFullName();
        $body  = array();
        $body[] = 'Dauer: '.$this->helper->formatMinutesToTimespan($activity->getMinutes());
        $body[] = 'Kunde: '.$activity->getTask()->getProject()->getCustomer()->getName();
        $body[] = 'Projekt: '.$activity->getTask()->getProject()->getName();
        $body[] = 'Aufgabe: '.$activity->getTask()->getName();
        $body[] = 'Kommentar: '.$activity->getComment();

        $mail = new \TYPO3\SwiftMailer\Message();
        $mail->setFrom(array($this->settings['notifications']['activityCreate']['from']['email'] => $this->settings['notifications']['activityCreate']['from']['name']))
            ->setTo(array($this->settings['notifications']['activityCreate']['to']['email'] => $this->settings['notifications']['activityCreate']['to']['name']))
            ->setSubject($subject)
            ->setBody(join("\r\n", $body))
            ->send();
    }
}
?>