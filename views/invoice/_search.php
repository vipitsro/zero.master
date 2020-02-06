<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'internal_number') ?>

    <?= $form->field($model, 'vs') ?>

    <?= $form->field($model, 'date_1') ?>

    <?= $form->field($model, 'date_2') ?>

    <?php // echo $form->field($model, 'date_3') ?>

    <?php // echo $form->field($model, 'date_4') ?>

    <?php // echo $form->field($model, 'id_supplier') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'dph') ?>

    <?php // echo $form->field($model, 'currency') ?>

    <?php // echo $form->field($model, 'account_prefix') ?>

    <?php // echo $form->field($model, 'account_number') ?>

    <?php // echo $form->field($model, 'bank_code') ?>

    <?php // echo $form->field($model, 'iban') ?>

    <?php // echo $form->field($model, 'swift') ?>

    <?php // echo $form->field($model, 'ks') ?>

    <?php // echo $form->field($model, 'info') ?>

    <?php // echo $form->field($model, 'tags') ?>

    <?php // echo $form->field($model, 'date_create') ?>

    <?php // echo $form->field($model, 'date_update') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
