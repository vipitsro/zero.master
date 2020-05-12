<?php

use yii\helpers\Html;
use yii\helpers\url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t("main", 'Invoices');
$this->params['breadcrumbs'][] = $this->title;

\yii\helpers\Url::remember();
?>
<div class="invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    Pjax::begin([
//        "enablePushState" => false,
//        "enableReplaceState" => true,
        "timeout" => 3000,
    ]);
    ?>

    <div class="row">
        <?php
        $form = ActiveForm::begin([
                    'method' => 'get',
                    'options' => ['data-pjax' => true],
                    'id' => 'invoice-search-form'
        ]);
        ?>

        <div class="col-md-3">
            <?= $form->field($searchModel, 'search_text')->textInput(['onchange' => 'update();']) ?>
        </div>  
        <div class="col-md-2">
            <?= $form->field($searchModel, 'paid')->dropDownList(["0" => "Všetky", "1" => "Na úhrade", "2" => "Zaplatené", "3" => "Po splatnosti", "4" => "Neuhradené"], ['onchange' => 'update();']) ?>
        </div>  
        <div class="col-md-2">
            <?php
            $supp = [];
            ?>
            <?=
            $form->field($searchModel, 'supp')->dropDownList(
                    [-1 => "Všetci"] +
                    ["TOP" => \yii\helpers\ArrayHelper::map($suppliers_best, "id", "name"), "OTHER" => \yii\helpers\ArrayHelper::map($suppliers_other, "id", "name")],
                    ['onchange' => 'update();'])
            ?>
        </div>  
        <div class="col-md-2">
            <?= $form->field($searchModel, 'year')->input("number", ['onchange' => 'update();']) ?>
        </div>  
        <div class="col-md-1" style="padding-top: 25px;">
            <?= Html::img(Url::to(["img/arrow_down.png"]), ["id" => "advanced-search-button", "width" => 30, "height" => 30]) ?>
        </div>  
        <div class="col-md-2" style="padding-top: 25px;">
            <div class="form-group">
                <?= Html::submitButton(Yii::t("main", 'Search'), ['id' => 'refresh', 'class' => 'btn btn-primary']) ?>
                <?= Html::button(Yii::t("main", 'XLS'), ['id' => 'generate-xls', 'class' => 'btn btn-primary', 'data-pjax' => 0]) ?>
            </div>
        </div>

        <div class="col-md-12 advanced-search" style="height: 1px; overflow: hidden;">
            <?php
            /* <div class="col-md-4">
              <?= $form->field($searchModel, 'radio')->inline(true)->radioList(["1" => Yii::t("invoice", "At least one tag"), "2" => Yii::t("invoice", "All tags")], ['separator' => '&nbsp&nbsp&nbsp&nbsp'])->label(Yii::t("main", "Choose")) ?>
              <?= $form->field($searchModel, 'tags')->inline(true)->checkboxList(app\models\Tag::getList()) ?>
              </div> */
            ?>
            <div class="col-md-12">
                <div class="form-group invoice-search-date">
                    <div class="col-md-6">
                        <?= $form->field($searchModel, 'date_1_from')->inline(true)->widget(DatePicker::className(), ['options' => ['class' => 'form-control'], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($searchModel, 'date_1_to')->inline(true)->widget(DatePicker::className(), ['options' => ['class' => 'form-control'], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy']) ?>       
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($searchModel, 'date_2_from')->inline(true)->widget(DatePicker::className(), ['options' => ['class' => 'form-control'], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy']) ?>
                    </div>
                    <div class="col-md-6">    
                        <?= $form->field($searchModel, 'date_2_to')->inline(true)->widget(DatePicker::className(), ['options' => ['class' => 'form-control'], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy']) ?>       
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($searchModel, 'date_3_from')->inline(true)->widget(DatePicker::className(), ['options' => ['class' => 'form-control'], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy']) ?>
                    </div>
                    <div class="col-md-6">    
                        <?= $form->field($searchModel, 'date_3_to')->inline(true)->widget(DatePicker::className(), ['options' => ['class' => 'form-control'], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy']) ?>       
                    </div>
                    <!--                    <div class="col-md-6">
                    <?= $form->field($searchModel, 'date_4_from')->inline(true)->widget(DatePicker::className(), ['options' => ['class' => 'form-control'], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy']) ?>
                                        </div>
                                        <div class="col-md-6">                
                    <?= $form->field($searchModel, 'date_4_to')->inline(true)->widget(DatePicker::className(), ['options' => ['class' => 'form-control'], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy']) ?>       
                                        </div>-->
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?php
                $get = Yii::$app->request->get();
                $create_year = ['create'] + (isset($get['InvoiceSearch']['year']) ? ["year" => $get['InvoiceSearch']['year']] : []);
                ?>
                <?= Html::a(Yii::t("main", 'Add'), $create_year, ['class' => 'btn btn-success', 'data-pjax' => 0]) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'rowOptions' => function($model) {
            $sum_paid = 0;
            $not_paid = 0;

            foreach ($model->invoicePays as $invoicePay) {
                if ($invoicePay->paid == 1)
                    $sum_paid += $invoicePay->price;
                else
                    $not_paid += $invoicePay->price;
            }

            if (round($model->price + $model->price_vat, 2) == round($sum_paid, 2)) {
                return ['class' => 'uhradene'];
            } else if ($not_paid > 0) {
                return ['class' => 'nezaplatene-v-baliku'];
            } else if (round($model->price + $model->price_vat, 2) != round($sum_paid, 2) && $sum_paid > 0) {
                return ['class' => 'ciastocne-nezaplatene-v-baliku'];
            } else if (strtotime($model->date_3) < time()) {
                return ['class' => 'po-datume-splatnosti'];
            }
        },
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => function ($model, $key, $index, $column) {
                    $result = [
                        'style' => 'min-width: 20px;',
                        'width' => 20
                    ];
                    if ($model->invoiceCarts) {
                        $result = ['class' => 'in-invoice-cart'];
                    }

                    return $result;
                },
                'buttons' => [
                    'na_uhradu' => function ($url, $model, $key) {
                        return Html::tag("span", "", ["title" => "Pridať do zoznamu na úhradu", "data-toggle" => "tooltip", "data-delay" => 1000, "data-container" => "body", "class" => "na-pp glyphicon glyphicon-download-alt", "height" => '20', 'width' => '20', "style" => "cursor: pointer; margin: 2px; color: #3c8dbc;"]);
                    },
                ],
                'template' => '{na_uhradu}',
            ],
            [
                'label' => '',
                'format' => 'raw',
                'value' => function ($model) {
                    //$url = "/images/products/" . $data['image'];
                    $x = "";
                    if ($model->file) {
                        $x = " <a data-pjax='0' target='_blank' href='" . Url::to('@web/uploads/' . $model->file, true) . "'><span class='glyphicon glyphicon-eye-open'></span></a>";
                    }
                    return $x;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'contentOptions' => [
                    'style' => 'min-width: 20px;',
                    'width' => 20
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'contentOptions' => [
                    'style' => 'min-width: 20px;',
                    'width' => 20
                ],
            ],
            [
                "value" => function($model) {
                    $cislo = substr($model->internal_number, 1, 1);
                    $znak = "";
                    if (in_array($cislo, [7, 8])) {
                        $znak = "<strong>Z </strong>";
                    }
                    if (in_array($cislo, [6, 8])) {
                        $znak .= Html::img(\yii\helpers\Url::to(["img/znak_eu.jpg"]), ["height" => 18]);
                    }
                    if (in_array($cislo, [5, 7])) {
                        $znak .= Html::img(\yii\helpers\Url::to(["img/znak_svk.png"]), ["height" => 18]);
                    }
                    if ($cislo == 9) {
                        $znak .= Html::img(\yii\helpers\Url::to(["img/znak_intern.png"]), ["height" => 18]);
                    }
                    return $znak;
                },
                "headerOptions" => ["width" => 45, "style" => "text-align: right;"],
                "contentOptions" => ["width" => 45, "style" => "text-align: right; font-size: 15px; white-space: nowrap;"],
                "header" => "",
                'format' => 'raw',
            ],
//            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'headerOptions' => ["hidden" => true, "class" => 'invoice-id'],
                'contentOptions' => ["hidden" => true, "class" => 'invoice-id']
            ],
            [
                'attribute' => 'date_1',
                'value' => function($model) {
                    return date("d.m.Y", strtotime($model->date_1));
                },
                'contentOptions' => [
                    'style' => 'max-width: 70px;text-align:center;',
                    'width' => 70,
                    'class' => ''
                ]
            ],
            [
                'attribute' => 'internal_number',
                'value' => function($model) {
                    return wordwrap($model->internal_number, 4, '-', true);
                },
                'contentOptions' => [
                    'style' => 'max-width: 70px;text-align:center;',
                    'width' => 70,
                    'class' => 'internal-number'
                ]
            ],
            [
                'attribute' => 'supplier',
                'contentOptions' => [
                    'style' => 'min-width: 200px;',
//                    'width' => 260
                ]
            ],
            [
                'attribute' => 'vs',
                'contentOptions' => [
                    'style' => 'min-width: 130px;',
                    'width' => 130
                ],
            ],
            [
                'attribute' => 'date_2',
                'value' => function($model) {
                    return date("d.m.Y", strtotime($model->date_2));
                },
                'contentOptions' => [
                    'style' => 'min-width: 130px;text-align:center;',
                    'width' => 130
                ],
            ],
            [
                'attribute' => 'date_3',
                'value' => function($model) {
                    return date("d.m.Y", strtotime($model->date_3));
                },
                'contentOptions' => [
                    'style' => 'min-width: 130px;text-align:center;',
                    'width' => 130
                ],
            ],
            [
                'attribute' => 'price',
                'value' => function($model) {
                    return number_format($model->price, 2, ".", " ") . " " . \app\models\MainModel::getCurrencyName($model->currency);
                },
                'headerOptions' => [
                    'style' => 'text-align: right;'
                ],
                'contentOptions' => [
                    'style' => 'min-width: 100px; text-align: right;',
                    'width' => 100
                ]
            ],
            [
                'attribute' => 'price_vat',
                'value' => function($model) {
                    return $model->price_vat . " " . \app\models\MainModel::getCurrencyName($model->currency);
                },
                'headerOptions' => [
                    'style' => 'text-align: right;'
                ],
                'contentOptions' => [
                    'style' => 'min-width: 100px;text-align: right;',
                    'width' => 100
                ],
                'header' => Yii::t("invoice", "VAT")
            ],
            [
                'attribute' => 'suma_s_dph',
                'value' => function($model) {
                    return number_format($model->price + $model->price_vat, 2, ".", " ") . " " . \app\models\MainModel::getCurrencyName($model->currency);
                },
                'contentOptions' => [
                    'style' => 'min-width: 110px; text-align:right',
                    'width' => 110,
                ],
                'headerOptions' => [
                    'style' => 'text-align:right; font-weight: bold;',
                ],
                'header' => Yii::t("invoice", "Price with VAT"),
            ],
            [
                "value" => function($model) {
                    $sum_paid = 0;
//                    foreach ($model->invoiceBatches as $invoiceBatch){
//                        if ($invoiceBatch->paid == 1) {
//                            $sum_paid += $invoiceBatch->price;
//                        }
//                    }

                    foreach ($model->invoicePays as $invoicePay) {
                        if ($invoicePay->paid == 1) {
                            $sum_paid += $invoicePay->price;
                        }
                    }
                    return number_format($model->price + $model->price_vat - $sum_paid, 2, ".", " ") . " " . \app\models\MainModel::getCurrencyName($model->currency);
                },
                "header" => Yii::t("invoice", "To pay"),
                'contentOptions' => [
                    'class' => 'suma-na-uhradu',
                    'style' => 'min-width: 110px; text-align:right; font-weight: bold;',
                    'width' => 110
                ],
                'headerOptions' => [
                    'style' => 'text-align:right',
                ],
            ],
            [
                "value" => function($model) {
                    $date_paid = "";
//                    foreach ($model->invoiceBatches as $invoiceBatch){
//                        if ($invoiceBatch->paid == 1)
//                            $date_paid .= date("d.m.Y",strtotime($invoiceBatch->date_1))."<br>";
//                    }
                    foreach ($model->invoicePays as $invoicePay) {
                        $date_paid .= date("d.m.Y", strtotime($invoicePay->date_payment)) . "<br>";
                    }
                    return $date_paid;
                },
                "header" => Yii::t("invoice_batch", "Date 1"),
                'contentOptions' => [
                    'style' => 'min-width: 110px; text-align:right; font-weight: bold;',
                    'width' => 110
                ],
                'headerOptions' => [
                    'style' => 'text-align:right',
                ],
                'format' => 'raw',
            ],
            [
                'header' => 'Uhradiť',
                'value' => function($model) {

                    $sum_paid = 0;
                    $not_paid = 0;

                    foreach ($model->invoicePays as $invoicePay) {
                        if ($invoicePay->paid == 1)
                            $sum_paid += $invoicePay->price;
                        else
                            $not_paid += $invoicePay->price;
                    }

                    if (round($model->price + $model->price_vat, 2) <> round($sum_paid, 2)) {
                        return Html::button("UHRADIŤ", ['class' => 'btn btn-primary uhradit']);
                    } else {
                        return "";
                    }
                },
                'format' => 'raw',
                'contentOptions' => [
                    'style' => 'min-width: 90px; text-align: center; padding: 2px;',
                    'width' => 90
                ],
            ],
        //'account_prefix',
        //'account_number',
        //'bank_code',
        //'iban',
        //'swift',
        //'vs',
        //'ks',
        //'debet_info',
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>

<style>
    .invoice-input-row{
        border-bottom: #eee 1px solid;
        padding:2px;
        font-size: 12px;
    }

    .invoice-input-name{
        font-weight: bold;
    }

    .hababa{
        max-width: 100%;
    }
    .add-row, .delete-row{
        cursor: pointer;
        text-align: center;
        font-weight: bold;
    }

    .dialog-form-nove-platby th{
        text-align: center;
        border-radius: 3px;
    }
</style>

<div class="form-group" id='dialog-form' hidden>
    <input type="hidden" autofocus="autofocus" />

    <?php
    $array = [
        "_typ_dokladu" => "Typ dokladu",
        "internal_number" => "Interné číslo",
        "supplier" => "Dodávateľ",
        "iban" => "IBAN",
        "date_1" => "Dátum prijatia",
        "date_2" => "Dátum dodania",
        "date_3" => "Dátum splatnosti",
        "vs" => "Variabilný symbol",
        "price" => "Suma",
        "price_vat" => "DPH",
//        "currency" => "Mena",
        "suma_s_dph" => "Suma s DPH",
    ];
    ?>

    <div class='dialog-form-udaje'>
        <?php
        foreach ($array as $key => $a) {
            ?>
            <div class="invoice-input-row">
                <div class="invoice-input-name col-md-5">
                    <?= $a . ": " ?>
                </div>
                <div class="invoice-input-value col-md-7" id='<?= 'invoice-input-' . $key ?>'>

                </div>
                <div class='clearfix'></div>
            </div>
            <?php
        }
        ?>
    </div>
    <hr>
    <div class='dialog-form-platby'>

    </div>

    <hr>
    <!--    <div class="invoice-input-row">
            <div class="invoice-input-name col-md-5">
    <?= "Dátum: " ?>
            </div>
            <div class="invoice-input-value col-md-7" id='<?= 'invoice-input-' . $key ?>'>
    
            </div>
            <div class='clearfix'></div>
        </div>-->

    <div class='dialog-form-nove-platby'>
        <table style='width: 100%;'>
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Suma</th>
                    <th>Typ</th>
                    <th width='25' class='add-row'><img width='20' height='20' src='<?= \yii\helpers\Url::to(["img/plus.png"]) ?>'></th>
                </tr>
            </thead>
            <tbody>
                <tr class='row-template' hidden>
                    <td><input class="form-control" name='InvoicePay[date_payment][]' value=''></td>
                    <td><input class="form-control" name='InvoicePay[price][]' value='' type='number'></td>
                    <td>
                        <select class="form-control" name='InvoicePay[status][]'>
                            <option value="" disabled selected>Vyber</option>
                            <?php
                            $typy_platby = app\models\MainModel::getTypyPlatby();
                            unset($typy_platby[100]);
                            foreach ($typy_platby as $key => $value) {
                                echo "<option value=" . $key . " >" . $value . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td width='25' class='delete-row'>X</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<?php
$this->registerJs('
    function update(){
        document.getElementById("refresh").click();
    }', yii\web\View::POS_END);

$this->registerJs('
    $(document).on("click", "#advanced-search-button", function(){ 
        if ($(".advanced-search").height() == 1){
            $(".advanced-search").css("height", "auto");
            var height = $(".advanced-search").height();
            $(".advanced-search").css("height", 0);
            $(".advanced-search").animate({"height": height}, 500);
        } else {
            $(".advanced-search").animate({"height": 1}, 500);
        }
    });
');

$this->registerJs('
    $(document).on("click", ".na-pp", function(){ 
        var _this = $(this);
        var _internal_number = $(this).closest("tr").find(".internal-number").text().trim();
       _internal_number = _internal_number.replace("-", "");
        $.ajax({
            method : "post",
            url : "../invoice-cart/add",
            data : { internal_number: _internal_number},
            success : function(res){
                res = JSON.parse(res);
                if (res.success == 1){
                    _this.closest("td").css({ backgroundColor : "#8f8" });

                } else {
                    _this.css({border: "0 solid #f00"}).animate({borderWidth: 3}, 500, function () {
                        _this.animate({borderWidth: 0}, 500);
                        console.log(res);
                    });
                }
            },
            statusCode : { 
                403: function(){
                    swal({
                        title: "Nedostatočné oprávnenie",
                    });
                },
                404: function(){
                    alert("Site not found");
                },
                500: function(){
                    alert("Internal server error");
                }
            }
        });
    });
');


$this->registerJs("
    $(document).on('click', '.uhradit', function( event ) {
        var _this = $(this);
        var _data_id = _this.closest('tr').attr('data-key');

        $.ajax({
            url : './get-invoice-data',
            method : 'POST',
            data : {id : _data_id},
            success : function(res){
                res = JSON.parse(res);
                console.log(res);
                
                var d = $( '#dialog-form' );
                d.find('#invoice-input-'+'_typ_dokladu').text(res._typ_dokladu);
                d.find('#invoice-input-'+'internal_number').text(res.internal_number);
                d.find('#invoice-input-'+'supplier').text(res.idSupplier.name);
                d.find('#invoice-input-'+'iban').text(res.iban);
                d.find('#invoice-input-'+'date_1').text(res.date_1);
                d.find('#invoice-input-'+'date_2').text(res.date_2);
                d.find('#invoice-input-'+'date_3').text(res.date_3);
                d.find('#invoice-input-'+'vs').text(res.vs);
                d.find('#invoice-input-'+'price').text(res.price);
                d.find('#invoice-input-'+'price_vat').text(res.price_vat);
//                d.find('#invoice-input-'+'currency').text(res.currency);
                d.find('#invoice-input-'+'suma_s_dph').text(res.suma_s_dph);
                
                // PRIDAT PLATBY DO DIALOGU
                for (i=0; i< res.invoicePays.length; i++){
                    var clone = $('.invoice-input-row').first().clone();
                    clone.addClass('invoice-input-row-dynamic');
                
                    var _date = res.invoicePays[i].date_payment;
                    var _price = res.invoicePays[i].price;
                    clone.find('.invoice-input-name').text(_date);
                    clone.find('.invoice-input-value').text(_price);
                    $('.dialog-form-platby').append(clone);
                }
                
                // RESET NOVE PLATBY
                d.find('.dialog-form-nove-platby tbody tr').not('.row-template').remove();
                var _table = d.find('table');
                var _template = _table.find('.row-template').clone();
                _template.removeClass('row-template');
                _template.show();
                _template.find('input[name=\"InvoicePay[date_payment][]\"]').datepicker();
                _template.find('input[name=\"InvoicePay[date_payment][]\"]').datepicker('setDate', new Date());
                var _na_uhradu = _this.closest('tr').find('.suma-na-uhradu').text().replace(/[^\d\.,]/g,'');
                console.log(_na_uhradu);
                _template.find('input[name=\"InvoicePay[price][]\"]').val(_na_uhradu);
                _table.append(_template);
                
                var _max_w = $(window).width();
                var _max_h = $(window).height();

                dialog = $( '#dialog-form' ).dialog({
                    autoOpen: false,
//                    height: 500,
                    width: 600,
                    maxWidth: _max_w,
                    maxHeight: _max_h,
                    modal: true,
                    dialogClass: 'hababa',
                    buttons: {
                        'Uložiť': function(){
                        
                            var _data = [];
                            d.find('.dialog-form-nove-platby tbody tr').not('.row-template').each(function(){
                                var _i1 = $(this).find('input[name=\"InvoicePay[date_payment][]\"]').val();
                                var _i2 = $(this).find('input[name=\"InvoicePay[price][]\"]').val();
                                var _i3 = $(this).find('select[name=\"InvoicePay[status][]\"]').val();
                                _data.push({id_invoice : _data_id, date_payment : _i1, price : _i2, status: _i3});
                            });
                            
                            $.ajax({
                                url : './save-invoice-pays',
                                method : 'POST',
                                data : {
                                    InvoicePay : _data
                                },
                                success : function(res){
                                    res = JSON.parse(res);
                                    console.log(res);
                                    location.reload();
                                }
                            });     
                        },
                        'Storno': function() {
                            $('.invoice-input-row-dynamic').remove();
                            dialog.dialog( 'close' );
                        }
                    },
                });
                dialog.dialog( 'open' );
            }
        });
    });
");

$this->registerJs("
    $('.add-row').on('click', function(){
        var _table = $(this).closest('table');
        var _template = _table.find('.row-template').clone();
        
        _template.removeClass('row-template');
        _template.show();
        _template.find('input[name=\"InvoicePay[date_payment][]\"]').datepicker();
        _table.append(_template);
    });
    
    $(document).on('click', '.delete-row', function(){
        $(this).closest('tr').remove();
    });
");

$this->registerJs("
    $(document).on('click', '#generate-xls', function(){
        var _url = window.location.href;
        var _get_params_index = _url.indexOf('?');
        var _new_url = '" . \yii\helpers\Url::to(["invoice/xls"]) . "' + ((_get_params_index == -1) ? '' : _url.substring(_get_params_index));
        window.location = _new_url;
    });
");
