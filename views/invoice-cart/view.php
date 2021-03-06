<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InvoiceCart */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('invoice_cart', 'Invoice Carts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-cart-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('invoice_cart', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('invoice_cart', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('invoice_cart', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_invoice',
            'account_number',
            'ss',
            'kredit_info',
            'kredit_info_2',
            'debet_vs',
            'debet_ss',
            'avizo',
        ],
    ]) ?>

</div>
