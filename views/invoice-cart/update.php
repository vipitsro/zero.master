<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InvoiceCart */

$this->title = Yii::t('invoice_cart', 'Update {modelClass}: ', [
    'modelClass' => 'Invoice Cart',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('invoice_cart', 'Invoice Carts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('invoice_cart', 'Update');
?>
<div class="invoice-cart-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
