<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Supplier */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="supplier-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'iban')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'swift')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ks')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'vs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'typical_service')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t("main",'Create') : Yii::t("main",'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    
    <div class="form-group">
        <?= Html::a(Yii::t('main', 'Back'), \yii\helpers\Url::previous(), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
