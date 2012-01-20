<?php

namespace VS\TimeSheet\ViewHelpers;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @api
 * @FLOW3\Scope("prototype")
 */
class SettingsViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {
	/**
	 * @var VS\TimeSheet\Core\Helper
	 * @FLOW3\Inject
	 */
	protected $helper;
	
	/**
     * @param string $path
     * @param string $as
     * @return mixed
     */
	public function render($path, $as = null) {
		if(is_null($as)){
			return $this->helper->getSettings($path);
		}else{
			$this->templateVariableContainer->add($as, $this->helper->getSettings($path));
			$content = $this->renderChildren();
			$this->templateVariableContainer->remove($as);
			return $content;
		}
	}
}

?>