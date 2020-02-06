<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Access

/* @var $this yii\web\View */
/* @var $model backend\models\Access */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="access-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php //$form->field($model, 'rights')->textarea(['rows' => 6]); ?>
    
    
    <?php 
    $ruleList = Access::getRuleList(); 
    $myrules = Access::getAdminRuleList($model->id);
    
    ?>
    <?php foreach($ruleList as $key => $value){ ?>
    <div class="form-group">
        <label class="control-label" for="access-rights"><?php echo $key ?></label><br>
        <div class="form-control">
            <?php foreach($value as $key2 => $value2){ ?>
                <?php if (strpos($myrules, $key2.";") !== false){
                    echo Html::checkBox($key2,true,['label'=>$value2]);
                } else { 
                    echo Html::checkBox($key2,false,['label'=>$value2]);
                } ?>
            <?php } ?>
        </div>
    </div>
    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t("main",'Create') : Yii::t("main",'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
