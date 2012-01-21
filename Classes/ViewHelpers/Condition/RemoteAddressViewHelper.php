<?php

namespace VS\TimeSheet\ViewHelpers\Condition;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @api
 * @FLOW3\Scope("prototype")
 */
class RemoteAddressViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * @var array
     */
    protected $settings;

    /**
     * @param array $settings
     */
    public function injectSettings(array $settings) {
        $this->settings = $settings;
    }
	
	/**
     * @param string $is
     * @param string $setting
     * @return mixed
     */
	public function render($is = NULL, $setting = NULL) {
        $ip = $this->getIp();

        if(!is_null($is)) {
            return $ip == $is;
        }

        if(!is_null($setting)) {
            $allow = $this->settings['ViewHelpers']['Condition']['RemoteAddress'][$setting];

            if(is_array($allow)) {
                foreach($allow as $allowedIp) {
                    if($this->validIp($allowedIp) && $ip == $allowedIp) {
                        return true;
                    }

                    if($ip == gethostbyname($allowedIp)) {
                        return true;
                    }
                }
            }
        }
        return false;
	}

    private function validIp($ip) {
        if (!empty($ip) && ip2long($ip)!=-1) {
            $reserved_ips = array (
                array('0.0.0.0','2.255.255.255'),
                array('10.0.0.0','10.255.255.255'),
                array('127.0.0.0','127.255.255.255'),
                array('169.254.0.0','169.254.255.255'),
                array('172.16.0.0','172.31.255.255'),
                array('192.0.2.0','192.0.2.255'),
                array('192.168.0.0','192.168.255.255'),
                array('255.255.255.0','255.255.255.255')
            );


            foreach ($reserved_ips as $r) {
                $min = ip2long($r[0]);
                $max = ip2long($r[1]);

                if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;

            }

            return true;

        } else {
            return false;
        }
    }

    private function getIp() {

        if (isset($_SERVER["HTTP_CLIENT_IP"]) && $this->validIp($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        }

        if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            foreach (explode(",",$_SERVER["HTTP_X_FORWARDED_FOR"]) as $ip) {
                if ($this->validIp(trim($ip))) {
                    return $ip;
                }
            }
        }

        if (isset($_SERVER["HTTP_X_FORWARDED"]) && $this->validIp($_SERVER["HTTP_X_FORWARDED"])) {
            return $_SERVER["HTTP_X_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]) && $this->validIp($_SERVER["HTTP_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_FORWARDED"]) && $this->validIp($_SERVER["HTTP_FORWARDED"])) {
            return $_SERVER["HTTP_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED"]) && $this->validIp($_SERVER["HTTP_X_FORWARDED"])) {
            return $_SERVER["HTTP_X_FORWARDED"];
        } else {
            return $_SERVER["REMOTE_ADDR"];
        }
    }
}

?>