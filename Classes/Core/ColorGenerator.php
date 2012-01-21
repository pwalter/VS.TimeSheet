<?php
namespace VS\TimeSheet\Core;

use TYPO3\FLOW3\Annotations as FLOW3;

class ColorGenerator {
    public static function random() {
        mt_srand((double)microtime()*1000000);
        $c = '#';
        while(strlen($c)<6){
            $c .= sprintf("%02X", mt_rand(0, 255));
        }
        return $c;
    }

    public static function randomLight() {
        mt_srand((double)microtime()*1000000);
        $c = '#';

        for($i = 0; $i < 3; $i++)
            $c .= sprintf("%02X", mt_rand(175, 255));
        return $c;
    }

    public static function randomLightGray() {
        mt_srand((double)microtime()*1000000);
        $c = sprintf("%02X", mt_rand(175, 240));
        return '#'.$c.$c.$c;
    }
}

?>