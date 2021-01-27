<?php
namespace Clases;

use \DateTime;
use \DateTimeZone;

require_once './vendor/autoload.php';


class Date{
    public function Compare($date_one, $date_two){
        $date_one = strtotime($date_one);
        $date_two = strtotime($date_two);

        if($date_one > $date_two){
            return 1;
        }
        else if($date_one < $date_two){
            return -1;
        }
        return 0;
    }

    public function checkDate($date_one){
        if(strlen($date_one) != 10){
            return false;
        }
        return true;
    }

    public function get_now(){
        $date = new DateTime();
        $date->setTimeZone(new DateTimeZone('America/Argentina/Buenos_Aires'));
        $date = $date->format('Y-m-d H:i:s');     //convierte Datetime a string
        return $date;
    }
}
?>