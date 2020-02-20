<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BlockySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blocky-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sumabez') ?>

    <?= $form->field($model, 'dph') ?>

    <?= $form->field($model, 'sumasdph') ?>

    <?= $form->field($model, 'ucel') ?>

    <?php // echo $form->field($model, 'file') ?>

    <?php // echo $form->field($model, 'datum') ?>

    <?php // echo $form->field($model, 'added') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('supplier_foreign', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('supplier_foreign', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
