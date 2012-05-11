<?php
namespace VS\TimeSheet\ViewHelpers;

use TYPO3\FLOW3\Annotations as FLOW3;

class JsonEncodeViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * @return string
     */
	public function render() {
        $value = $this->renderChildren();

        return json_encode($value);
	}
}

?>