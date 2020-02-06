<?php

namespace app\components\CustomDatePicker;

use yii\jui\DatePicker;

class CustomDatePicker extends DatePicker{
    
    public $isRTL = false;
    
    public function init(){
        parent::init();
//        $this->clientOptions['isRTL'] = $this->isRTL;
    }
}

