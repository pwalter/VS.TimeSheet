<?php
namespace VS\TimeSheet\Core;

use TYPO3\FLOW3\Annotations as FLOW3;

class Sorter {
	public function sort(array $arr, $field) {
        $count = count($arr);

        for($i=0; $i < $count; $i++) {
            for($j=0; $j < $count-1; $j++) {
                $first = $arr[$j];
                $second = $arr[$j+1];
                if($first[$field] > $second[$field]) {
                    $arr[$j] = $second;
                    $arr[$j+1] = $first;
                } else {
                    $arr[$j] = $first;
                    $arr[$j+1] = $second;
                }
            }
        }

        return $arr;
    }
}

?>