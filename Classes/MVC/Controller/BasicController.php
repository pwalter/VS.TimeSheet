<?php
namespace VS\TimeSheet\MVC\Controller;

use TYPO3\FLOW3\Annotations as FLOW3;

class BasicController extends \TYPO3\FLOW3\MVC\Controller\ActionController {

    /**
     * @FLOW3\Inject
     * @var \TYPO3\FLOW3\Security\Context
     */
    protected $securityContext;

    /**
     * @FLOW3\Inject
     * @var \VS\TimeSheet\Core\Helper
     */
    protected $helper;

    /**
     * @return \TYPO3\FLOW3\Security\Account
     */
    public function findCurrentAccount() {
        $activeTokens = $this->securityContext->getAuthenticationTokens();
        foreach ($activeTokens as $token) {
            if ($token->isAuthenticated()) {
                return $token->getAccount();
            }
        }
    }

    public function addFlashMessageError($body, $title = null) {
        $this->addFlashMessage($body, $title, \TYPO3\FLOW3\Error\Message::SEVERITY_ERROR);
    }
}
