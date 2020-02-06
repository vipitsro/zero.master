<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\InvoiceBatch */

$this->title = Yii::t("main",'Invoice Batch') . " - " . Yii::t("main",'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('invoice_batch', 'Invoice Batches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-batch-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
