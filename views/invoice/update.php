<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Invoice */

$this->title = Yii::t("main",'Invoice') . " - " . Yii::t("main",'Update') . ": ". $model->internal_number;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->internal_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="invoice-update">

    <?= $this->render('_form', [
        'model' => $model,
        'payments' => $payments,
        'suppliers_best' => $suppliers_best,
        'suppliers_other' => $suppliers_other,
    ]) ?>

</div>
