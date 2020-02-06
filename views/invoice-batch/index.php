<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoiceBatchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('invoice_batch', 'Invoice Batches');
$this->params['breadcrumbs'][] = $this->title;

\yii\helpers\Url::remember();
?>
<div class="invoice-batch-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <?php
        
        $form = ActiveForm::begin([
                    'method' => 'get',
                    'options' => ['data-pjax' => true],
                    'id' => 'invoice-search-form'
        ]);
        ?>

        <?php
        /*
        <div class="col-md-4">
            <?= $form->field($searchModel, 'search_text')->textInput(['onchange' => 'update();']) ?>
        </div>  
        
        <div class="col-md-4">
            <?= $form->field($searchModel, 'paid')->dropDownList(["0" => "Všetky", "1" => "Zaplatené", "2" => "Nezaplatené"],['onchange' => 'update();']) ?>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?= Html::submitButton(Yii::t("main", 'Search'), ['id' => 'refresh', 'class' => 'btn btn-primary']) ?>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <?= Html::a(Yii::t("main", 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        */
        ?>
        <?php ActiveForm::end(); ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'rowOptions' => function($model){
            if (sprintf("%.2f",$model->sum_all) != sprintf("%.2f",$model->sum_paid)){
                return ['class' => 'batch-neuhradene'];
            } 
        },
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'buttons' =>[
                    'update' => function($url, $model, $key){ 
                        return Html::a("<span class='glyphicon glyphicon-eye-open'></span>", ["one-batch", "batch" => $model->batch]); 
                    },
                ],
                'contentOptions' => [
                    'style' => 'min-width: 20px;',
                    'width' => 20
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' =>[
                    'delete' => function($url, $model, $key){ 
                        return Html::a("<span class='glyphicon glyphicon-trash'></span>", ["delete", "batch" => $model->batch]); 
                    },
                ],
                'contentOptions' => [
                    'style' => 'min-width: 20px;',
                    'width' => 20
                ],
            ],
//            ['class' => 'yii\grid\SerialColumn'],      
            [
                'header' => "ID",
                'value' => function($model){
                    return sprintf("%04d",$model->batch);
                },
                'headerOptions' => ["style" => "width: 60px;"],
                'contentOptions' => ['class' => 'batch-id', "style" => "width: 60px;"],
            ],       
            [
                'header' => 'XML',
                'value' => function($model){
                    if ($model->paid == 0)
                        return Html::a("XML", \yii\helpers\Url::to(["invoice-batch/create-t-b-file", "batch_id" => $model->batch]), ['class' => 'btn btn-primary']);
                    else 
                        return "";
                },
                'format' => 'raw',
                'contentOptions' => ["style" => "width: 80px;", 'class' => 'generate-xml']
            ],
            [
                'attribute' => 'date_create',
                'value' => function($model){
                    return date("d.m.Y H:i:s", strtotime($model->date_create));
                },
//                'contentOptions' => [
//                    'style' => 'min-width: 150px',
//                    'width' => 150
//                ],
            ],
            [
                "attribute" => 'count_invoices',
                "header" => Yii::t("invoice_batch", "Count Invoices"),
//                'contentOptions' => [
//                    'style' => 'min-width: 90px; text-align:center;',
//                    'width' => 90
//                ],
            ],
                    [
                "attribute" => "sum_all",
                "value" => function($model){
                    return number_format($model->sum_all, 2, ".", " ") . " " . \app\models\MainModel::getCurrencyName($model->idInvoice->currency);
                },
                "header" => Yii::t("invoice_batch", "Price with VAT"),
                'contentOptions' => [
                    'style' => 'min-width: 110px; text-align:right;',
                    'width' => 110
                ],
                'headerOptions' => [
                    'style' => 'text-align:right;',
                ],
            ],
            [
                'header' => 'Uhradiť',
                'value' => function($model){
                    if ($model->paid == 0)
                        return Html::button("UHRADIŤ", ['class' => 'btn btn-primary uhradit']);
                    else
                        return "";
                },
                'format' => 'raw',
//                'contentOptions' => [
//                    'style' => 'min-width: 90px; text-align:right;',
//                    'width' => 90
//                ],
            ],
        ],
    ]); ?>
</div>

<div class="form-group" id='dialog-form' hidden>
    <input type="hidden" autofocus="autofocus" />
    <label for="datum">Dátum:</label>
    <input type="text" class="form-control" id="datum">
</div>

<?php
$this->registerJs('
    function update(){
        document.getElementById("refresh").click();
    }', yii\web\View::POS_END);

$this->registerJs("
    $(document).on('click', '.uhradit', function(){
        var _batch_id = $(this).closest('tr').find('.batch-id').text().trim();
        $.ajax({
            url : './uhradit',
            method : 'POST',
            data : {batch : _batch_id},
            success : function(res){
                res = JSON.parse(res);
                console.log(res);
                location.reload();
            },
            error : function(res){
                try {
                    var json = JSON.parse(res);
                    res = json;
                } catch (e) {}
                console.log(res);
            }
        });
    });
");

$this->registerJs("  
    $('#datum').datepicker($.extend({}, $.datepicker.regional['sk'], {'dateFormat':'dd.mm.yy'}, {autoclose: true}));
    $('#datum').datepicker('setDate', -1);
    $('#datum').datepicker('setDate', '+0');

    $(document).on('click', '.generate-xml a', function( event ) {
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