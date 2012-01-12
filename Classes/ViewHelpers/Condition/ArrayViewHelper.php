<?php

namespace VS\TimeSheet\ViewHelpers\Condition;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @api
 * @FLOW3\Scope("prototype")
 */
class ArrayViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {
	/**
	 * @var VS\TimeSheet\Core\Helper
	 * @FLOW3\Inject
	 */
	protected $helper;
	
	/**
     * @param $obj
     * @return mixed
     */
	public function render($obj) {
		return count($obj);
	}
}

?>