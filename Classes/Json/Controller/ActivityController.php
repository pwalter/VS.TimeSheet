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
class ActivityController extends \VS\TimeSheet\MVC\Controller\BasicJsonController {

    /**
     * @FLOW3\Inject
     * @var \TYPO3\FLOW3\Security\AccountRepository
     */
    protected $accountRepository;

    /**
     * @param string $term
     * @return void
     */
    public function completeAccountAction($term = '') {
        $value = array();
        foreach($this->accountRepository->findAllByTerm(trim($term)) as $account) {
            $fullName = $account->getParty()->getName()->getFullName();
            $value[] = array(
                'id' => $this->helper->getIdentifier($account),
                'label' => $fullName,
                'value' => $fullName
            );
        }

        $this->view->assign('value', $value);
    }

    /**
     * @param string $text
     * @return void
     */
    public function parseQuickCreateAction($text = '') {
        $parts = preg_split('| |', $text);

        $account = null;
        foreach($parts as $part) {
            // Try account
            $acc = $this->accountRepository->findByAccountIdentifier($part);
            if(!is_null($acc)) {
                $account = $acc;
                continue;
            }
        }

        $value = array(
            'account' => $account,
            'text' => $text,
            'parts' => $parts
        );
        $this->view->assign('value', $value);
    }
}

?>