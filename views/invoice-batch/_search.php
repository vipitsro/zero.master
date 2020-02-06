<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InvoiceBatchSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-batch-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_invoice') ?>

    <?= $form->field($model, 'batch') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'account_number') ?>

    <?php // echo $form->field($model, 'account_prefix_supplier') ?>

    <?php // echo $form->field($model, 'account_number_supplier') ?>

    <?php // echo $form->field($model, 'bank_code_supplier') ?>

    <?php // echo $form->field($model, 'currency') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'date_1') ?>

    <?php // echo $form->field($model, 'ks') ?>

    <?php // echo $form->field($model, 'vs') ?>

    <?php // echo $form->field($model, 'ss') ?>

    <?php // echo $form->field($model, 'kredit_info') ?>

    <?php // echo $form->field($model, 'kredit_info_2') ?>

    <?php // echo $form->field($model, 'debet_vs') ?>

    <?php // echo $form->field($model, 'debet_ss') ?>

    <?php // echo $form->field($model, 'debet_info') ?>

    <?php // echo $form->field($model, 'avizo') ?>

    <?php // echo $form->field($model, 'date_create') ?>

    <?php // echo $form->field($model, 'date_update') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('invoice_batch', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('invoice_batch', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
