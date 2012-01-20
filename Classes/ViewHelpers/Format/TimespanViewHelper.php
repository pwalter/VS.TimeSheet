<?php
namespace VS\TimeSheet\ViewHelpers\Format;

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */
use TYPO3\FLOW3\Annotations as FLOW3;

class TimespanViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
	 * @var VS\TimeSheet\Core\Helper
	 * @FLOW3\Inject
	 */
	protected $helper;

    /**
     * @param string $format
     * @return
     */
	public function render($format = NULL) {
        $value = (int)$this->renderChildren();
        return $this->helper->formatMinutesToTimespan($value, $format);
	}
}
?>