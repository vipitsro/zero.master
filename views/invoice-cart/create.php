<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\InvoiceCart */

$this->title = Yii::t('invoice_cart', 'Create Invoice Cart');
$this->params['breadcrumbs'][] = ['label' => Yii::t('invoice_cart', 'Invoice Carts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-cart-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
