<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->errorSummary($model); ?>
    
    <?= $form->field($model, 'username')->textInput(["readonly" => true]) ?>
    
    <?= $form->field($model, 'name')->textInput() ?>
    
    <?= $form->field($model, 'surname')->textInput() ?>
    
    <?= $form->field($model, 'email')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton( Yii::t("main",'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
    <?php if (!$model->isNewRecord){ ?>
        <?= Html::a(Yii::t("user",'Change password'), ['/user/changepassword?id='.$model->id], ['class'=>'']) ?>
    <?php } ?>

</div>
