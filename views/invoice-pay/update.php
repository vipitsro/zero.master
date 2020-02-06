<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InvoicePay */

$this->title = Yii::t('invoice_pay', 'Update {modelClass}: ', [
    'modelClass' => 'Invoice Pay',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('invoice_pay', 'Invoice Pays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('invoice_pay', 'Update');
?>
<div class="invoice-pay-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
