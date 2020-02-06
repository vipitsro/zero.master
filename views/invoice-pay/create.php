<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\InvoicePay */

$this->title = Yii::t('invoice_pay', 'Create Invoice Pay');
$this->params['breadcrumbs'][] = ['label' => Yii::t('invoice_pay', 'Invoice Pays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-pay-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
