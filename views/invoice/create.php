<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Invoice */

$this->title = Yii::t("main",'Invoice') . " - " . Yii::t("main",'Create');
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-create">
    
    <?= $this->render('_form', [
        'model' => $model,
        'payments' => $payments,
        'suppliers_best' => $suppliers_best,
        'suppliers_other' => $suppliers_other,
    ]) ?>

</div>
