<?php

namespace VS\TimeSheet\ViewHelpers\Collection;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @api
 * @FLOW3\Scope("prototype")
 */
class FirstViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
     * @param \Doctrine\Common\Collections\Collection $collection
     * @return int
     */
	public function render($collection) {
        if ($collection === NULL) {
            $collection = $this->renderChildren();
        }

		return $collection->getFirst();
	}
}

?>