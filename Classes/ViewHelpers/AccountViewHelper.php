<?php
namespace VS\TimeSheet\ViewHelpers;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @api
 */
class AccountViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

    /**
     * @FLOW3\Inject
     * @var \TYPO3\FLOW3\Security\Context
     */
    protected $securityContext;
	
	/**
     * @param string $property
     * @return string
     */
	public function render($property = "fullname") {
		$activeTokens = $this->securityContext->getAuthenticationTokens();
        $acc = null;
        foreach ($activeTokens as $token) {
            if ($token->isAuthenticated()) {
                $acc = $token->getAccount();
                break;
            }
        }

        if(is_null($acc))
            return '[Unbekannter Benutzer]';

        switch(strtolower($property)) {
            case 'username':
                return $acc->getAccountIdentifier();

            case 'firstname':
                return $acc->getParty()->getName()->getFirstName();

            case 'lastname':
                return $acc->getParty()->getName()->getLastName();

            case 'fullname':
            default:
                return $acc->getParty()->getName()->getFullName();
        }
	}
}

?>
