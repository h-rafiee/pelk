<?php

namespace App\Payments;


class Payment {

    public $setting = null;
    public $redirect = null;
    public $amount = 0 ;

    public function setSetting($setting){
        $this->setting = $setting;
    }

    public function setRedirect($redirect){
        $this->redirect = $redirect;
    }

    public function setAmount($amount , $toman = false){
        $this->amount = $amount*(($toman == true) ? 1 : 10 );
    }
}