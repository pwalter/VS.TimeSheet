<?php
namespace VS\TimeSheet\ViewHelpers;

use TYPO3\FLOW3\Annotations as FLOW3;

class IdentityViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var VS\TimeSheet\Core\Helper
	 * @FLOW3\Inject
	 */
	protected $helper;

	/**
	 * Renders the output of this view helper
	 *
	 * @param object $object The persisted object
	 * @return string Identity
	 * @api
	 */
	public function render($object = NULL) {
		if ($object === NULL) {
			$object = $this->renderChildren();
		}
        return $this->helper->getIdentifier($object);
	}
}

?>