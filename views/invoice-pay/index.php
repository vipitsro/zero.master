<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoicePaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('invoice_pay', 'Invoice Pays');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-pay-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('invoice_pay', 'Create Invoice Pay'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_invoice',
            'price',
            'comment:ntext',
            'date_create',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
