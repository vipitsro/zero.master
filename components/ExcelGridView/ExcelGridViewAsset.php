<?php

namespace app\components\ExcelGridView;

class ExcelGridViewAsset extends \yii\web\AssetBundle {

    public $sourcePath = '@app/components/ExcelGridView';

    public $autoGenerate = true;
    
    /**
     * @inheritdoc
     */
    public $js = [
        'ExcelGridView.js'
    ];
    public $depends = [
//        'all'
    ];
}
