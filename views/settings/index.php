<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t("main",'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-index">

<!--    <p>
        <?= Html::a(Yii::t("main",'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->
    
    <?php
        
        foreach ($errors as $error){
            echo "<div>";
            if (!empty($error))
                echo "Chyba: ". var_dump($error);
            echo "</div>";
        }
        
        $form = \yii\widgets\ActiveForm::begin();
        
        foreach ($models as $model){
            echo "<div class='form-group'>";
            echo $model->name;
            echo Html::input("text", "Settings[".$model->setting."]", $model->value, ["class" => 'form-control']);
            echo "<div class='help-block'></div>";
            echo "</div>";
        }
        
        echo Html::submitButton(Yii::t("main",'Save'), ['class' => 'btn btn-success']);
        
        \yii\widgets\ActiveForm::end();
    ?>
    
    <hr>
    
    <?php $form = \yii\widgets\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    
        <?php 
        $files = scandir(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "web" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "logo");
        $image = "";
        foreach ($files as $file){
            if (in_array($file, [".", ".."])){
                continue;
            } else {
                $image = $file;
            }
        }
        ?>
    
        <?= Html::img(yii\helpers\Url::to(["img/logo/".$image]), ["style" => "width: 300px;"])?>
    
        <?= Html::fileInput("image") ?>
    
        <?= Html::submitButton(Yii::t("main",'Upload'), ['class' => 'btn btn-success', "style" => "margin-top: 20px;"]) ?>

    <?php \yii\widgets\ActiveForm::end() ?>

</div>
