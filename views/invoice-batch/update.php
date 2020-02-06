<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InvoiceBatch */

$this->title = Yii::t("main",'Invoice') . " - " . Yii::t("main",'Update') . ": " . $model->batch;
$this->params['breadcrumbs'][] = ['label' => Yii::t('invoice_batch', 'Invoice Batches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('invoice_batch', 'Update');
?>
<div class="invoice-batch-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
