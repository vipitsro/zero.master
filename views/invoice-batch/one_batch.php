<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoiceBatchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t("main",'Invoice Batch');
$this->params['breadcrumbs'][] = $this->title;

\yii\helpers\Url::remember();
?>
<div class="invoice-batch-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php $get = Yii::$app->request->get(); ?>
        <?= Html::a(Html::button(Yii::t('invoice_batch', 'Generate XML'), ['class' => 'btn btn-success']), Url::to(["invoice-batch/create-t-b-file", "batch_id" => $get['batch']]), ['class' => 'generate-xml']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'rowOptions' => function($model){
            $sum = 0;
            foreach($model->idInvoice->invoicePays as $invoicePay){
                if ($invoicePay->paid == 1)
                    $sum += $invoicePay->price;
            }
            
            $price_full = $model->idInvoice->price + $model->idInvoice->price_vat;
            if (sprintf("%.2f", $price_full) == sprintf("%.2f", $sum)){
                return ['class' => 'uhradene'];
            } else {
                return ['class' => 'na-uhradu'];
            }
        },
        'columns' => [
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'template' => '{update}',
//                'contentOptions' => [
//                    "style" => "position: relative;min-width: 20px;",
//                    'width' => 20
//                ]
//            ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'template' => '{delete}',
//                'contentOptions' => [
//                    "style" => "position: relative;min-width: 20px;",
//                    'width' => 20
//                ]
//            ],
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id_invoice',
                'headerOptions' => ["hidden" => true, "class" => 'invoice-id'],
                'contentOptions' => ["hidden" => true, "class" => 'invoice-id']
            ],
            "idInvoice.internal_number",
            [
                "attribute" => "idInvoice.supplier",
                "header" => Yii::t("invoice_batch", "Supplier name")
            ],
            [
                'attribute' => 'idInvoice.date_3',
                'value' => function($model) {
                    return date("d.m.Y", strtotime($model->idInvoice->date_3));
                },
            ],
            [
                "value" => function($model){
                    $price = $model->idInvoice->price + $model->idInvoice->price_vat;
                    return number_format($price, 2, ".", " ") . " " . \app\models\MainModel::getCurrencyName($model->idInvoice->currency);
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
                    
                    return number_format($model->price, 2, ".", " ") . " " . \app\models\MainModel::getCurrencyName($model->idInvoice->currency);
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

    <div style="text-align: right; font-weight: bold;padding: 8px;">
        <?php
        $to_pay = 0;
        foreach($dataProvider->models as $model){
            $to_pay += $model->price;
        }
        if (isset($model)){
            echo number_format($to_pay,2) . " " . \app\models\MainModel::getCurrencyName($model->idInvoice->currency);
        }
        ?>
    </div>
</div>

<div class="form-group" id='dialog-form' hidden>
    <input type="hidden" autofocus="autofocus" />
    <label for="datum">Dátum:</label>
    <input type="text" class="form-control" id="datum">
</div>

<?php
 
$this->registerJs("
    $(document).on('click','.pay-invoice', function(){
        var value = $(this).closest('tr').find('.suma-na-uhradu').text().trim().replace(/[^0-9\.]/g,'');;
        var div = '<div class=\"pay-invoice-pop\"><input class=\"pay-invoice-pop-input form-control\" type=\"text\" value=\"'+value+'\"><button class=\"pay-invoice-pop-button-uhradit btn\">Uhradiť</button><button class=\"btn pay-invoice-pop-button-storno\">STORNO</button></div>';
        $('.pay-invoice-pop').remove();
        $(this).parent().append(div);
    });
    
    $(document).on('click','.pay-invoice-pop-button-storno', function(){
        $('.pay-invoice-pop').remove();
    });
    
    $(document).on('click','.pay-invoice-pop-button-uhradit', function(){
        
        _id = $(this).closest('tr').find('.invoice-id').text().trim();
        _sum = $('.pay-invoice-pop input').val();
        
        $.ajax({
            url : 'one-batch-pay-one',
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

$this->registerJs("  
    $('#datum').datepicker($.extend({}, $.datepicker.regional['sk'], {'dateFormat':'dd.mm.yy'}, {autoclose: true}));
    $('#datum').datepicker('setDate', -1);
    $('#datum').datepicker('setDate', '+0');

    $(document).on('click', '.generate-xml', function( event ) {
        var _this = $(this);
        
        if (_this.attr('href').indexOf('&date=') == -1){
            event.preventDefault();

            dialog = $( '#dialog-form' ).dialog({
                autoOpen: false,
                height: 200,
                width: 280,
                modal: true,
                buttons: {
                    'OK': function(){
                        var baseUrl = _this.attr('href');
                        _this.attr('href', baseUrl+'&date='+$('#datum').val());
                        window.location = _this.attr('href');
                        _this.attr('href', baseUrl);
                        dialog.dialog( 'close' );            
                    },
                    'Cancel': function() {
                        dialog.dialog( 'close' );
                    }
                },
            });
            dialog.dialog( 'open' );
        }
    });
");