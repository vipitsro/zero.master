<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoiceCartSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('invoice_cart', 'To pay');
$this->params['breadcrumbs'][] = $this->title;

Yii::$app->params['ACCOUNT_NUMBER'] = app\models\Settings::find()->where(["setting" => "ACCOUNT_NUMBER"])->one()->value;

\yii\helpers\Url::remember();
?>
<div class="invoice-cart-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('invoice_cart', 'Create Invoice Batch'), ['create-batch'], ['class' => 'btn btn-success']) ?>
        <?php // Html::a(Yii::t('invoice_cart', 'Create Invoice Batch and Create TB File'), ['create-batch'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'style' => 'min-width: 20px; position: relative;',
                    'width' => 20
                ],
                'buttons' => [
                    'pay' => function($url, $model, $key){
                        return Html::tag("span", "", ["title" => "Uhradiť sumu", "data-toggle" => "tooltip", "data-delay" => 1000, "data-container" => "body", "class" => "pay-invoice glyphicon glyphicon-usd", "height"=>'20', 'width'=>'20', "style" => "cursor: pointer; margin: 2px; color: #3c8dbc;"]);
                    }
                ],
                'template' => '{pay}', 

            ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'contentOptions' => [
//                    'style' => 'min-width: 20px; position: relative;',
//                    'width' => 20
//                ],
//                'buttons' => [
//                    'my_button' => function ($url, $model, $key) {
//                        return Html::img(Url::to(["img/folder_arrow_back_1.png"]), ["class" => "na-pp", "height"=>'20', 'width'=>'20', "style" => "cursor: pointer"]);
//                    },
//                ],
//                'template' => '{update}', 
//
//            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'style' => 'min-width: 20px; position: relative;',
                    'width' => 20
                ],
                'template' => '{delete}', 

            ],
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'idInvoice.internal_number'
            ],
            [
                'attribute' => 'account_number',
                'value' => function($model){ return \Yii::$app->params['ACCOUNT_NUMBER']; },
                'header' => Yii::t("invoice_cart", "Account number")
            ],
            [
                "attribute" => "idInvoice.supplier",
                "header" => Yii::t("invoice_cart", "Supplier name")
            ],
            [
                'attribute' => 'idInvoice.date_3',
                'value' => function($model) {
                    return date("d.m.Y", strtotime($model->idInvoice->date_3));
                },
            ],
            [
                "value" => function($model){
                    return number_format($model->idInvoice->price + $model->idInvoice->price_vat, 2, ".", " ") . " " . \app\models\MainModel::getCurrencyName($model->idInvoice->currency);
                },
                "header" => Yii::t("invoice_batch", "Price with VAT"),
                'contentOptions' => [
                    'style' => 'min-width: 15px; text-align:right;',
                    'width' => 150
                ],
                'headerOptions' => [
                    'style' => 'min-width: 150px; text-align:right;',
                    'width' => 150
                ],
            ],
            [
                "value" => function($model){
                    return $model->price . " " . \app\models\MainModel::getCurrencyName($model->idInvoice->currency);
                },
                "header" => Yii::t("invoice_batch", "To pay"),
                'contentOptions' => [
                    'class' => 'suma-na-uhradu',
                    'style' => 'min-width: 150px; text-align:right; font-weight: bold;',
                    'width' => 150
                ],
                'headerOptions' => [
                    'style' => 'min-width: 150px; text-align:right',
                    'width' => 150
                ],
            ],
        ],
    ]); ?>
    
    <div style="text-align: right; font-weight: bold;padding: 8px; position: absolute; bottom: 80px; right: 2px;">
        <?php
        $to_pay = 0;
        foreach($dataProvider->models as $model){
            $to_pay += $model->price;
        }
        if (isset($model)) echo $to_pay . " " . \app\models\MainModel::getCurrencyName($model->idInvoice->currency);
        ?>
    </div>
</div>

<style>
    .invoice-cart-index{
        position: relative;
    }
    
    .grid-view{
        padding-bottom: 100px;
    }
</style>

    

<?php
    $this->registerJs("
        $(document).on('click','.pay-invoice', function(){
            var value = $(this).closest('tr').find('.suma-na-uhradu').text().trim().replace(/[^0-9\.]/g,'');           
            var div = '<div class=\"pay-invoice-pop\"><input class=\"pay-invoice-pop-input form-control\" type=\"text\" value=\"'+value+'\"><button class=\"pay-invoice-pop-button-uhradit btn\">Uhradiť</button><button class=\"btn pay-invoice-pop-button-storno\">STORNO</button></div>';
            $('.pay-invoice-pop').remove();
            $(this).parent().append(div);
        });

        $(document).on('click','.pay-invoice-pop-button-storno', function(){
            $('.pay-invoice-pop').remove();
        });

        $(document).on('click','.pay-invoice-pop-button-uhradit', function(){

            _id = $(this).closest('tr').attr('data-key');
            _sum = $('.pay-invoice-pop input').val();

            $.ajax({
                url : '../invoice-cart/change-price',
                data : {
                    id : _id, 
                    sum : _sum
                },
                method : 'POST',
                success : function(res){
                    res = JSON.parse(res);
                    console.log(res);
                    location.reload();
                },
                statusCode : { 
                    404: function(){
                        alert('Site not found');
                    },
                    500: function(){
                        alert('Internal server error');
                    }
                }
            });
        });
    ");
?>
