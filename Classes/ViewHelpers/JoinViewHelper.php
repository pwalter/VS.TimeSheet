<?php
namespace VS\TimeSheet\ViewHelpers;

use TYPO3\FLOW3\Annotations as FLOW3;

class JoinViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * @param \Doctrine\Common\Collections\Collection|null $collection
     * @param $property
     * @param string $glue
     * @return string
     */
	public function render(\Doctrine\Common\Collections\Collection $collection = NULL, $property, $glue = ', ') {
        $property = 'get'.ucfirst($property);

        $str = '';
        $max = $collection->count();
        for($i = 0; $i < $max; $i++) {
            $str .= $collection->get($i)->$property();
            if($i != $max - 1) {
                $str .= $glue;
            }
        }

        return $str;
	}
}

?>