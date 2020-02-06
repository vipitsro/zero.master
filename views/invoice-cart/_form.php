<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\base\DynamicModel;
use app\models\Invoice;
use app\models\Supplier;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\InvoiceCart */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-cart-form">

    <?php $form = ActiveForm::begin(); ?>
  
    <div class='row'>
        <div class='col-md-6'>

            <?= $form->field($model, 'account_number')->textInput(['maxlength' => true, "readonly" => true]) ?>

            <?= $form->field($model, 'ss')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'kredit_info')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'kredit_info_2')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'debet_vs')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'debet_ss')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'avizo')->textInput(['maxlength' => true]) ?>

        </div>

        <div class='col-md-6'>

            <?= $form->field($model->idInvoice, 'internal_number')->textInput(["readonly" => true]) ?>

            <?= $form->field($model->idInvoice, 'id_supplier')->dropDownList(ArrayHelper::map(Supplier::find()->all(), "id", "name"), [
                        'prompt' => 'Choose',
                        'onchange' => '$.post("../supplier/bankdata?id="+$(this).val(),function( data ) {
                                    var json = $.parseJSON(data);
                                    console.log(json);
                                    $( "input#invoice-supplier" ).val( json.name );
                                    $( "input#invoice-account_prefix" ).val( json.account_prefix );
                                    $( "input#invoice-account_number" ).val( json.account_number );
                                    $( "input#invoice-bank_code" ).val( json.bank_code );
                                    $( "input#invoice-iban" ).val( json.iban );
                                    $( "input#invoice-swift" ).val( json.swift );
                                    $( "input#invoice-ks" ).val( json.ks );
                                    $( "input#invoice-info" ).val( json.info );
                                });', "readonly" => true]) ?>
            
            <?= $form->field($model->idInvoice, 'date_1')->widget(DatePicker::className(), ['options' => ['class' => 'form-control', "readonly" => true], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy']) ?>
            
            <?= $form->field($model->idInvoice, 'date_2')->widget(DatePicker::className(), ['options' => ['class' => 'form-control', "readonly" => true], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy']) ?>
            
            <?= $form->field($model->idInvoice, 'date_3')->widget(DatePicker::className(), ['options' => ['class' => 'form-control', "readonly" => true], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy']) ?>
            
            <?= $form->field($model->idInvoice, 'price')->textInput(["readonly" => true]) ?>

            <?= $form->field($model->idInvoice, 'price_vat')->textInput(["readonly" => true]) ?>
            
            <?php 
            $dynModel = new DynamicModel(["suma_s_dph"]);
            $dynModel->addRule("suma_s_dph", "string");
            
            $dynModel->suma_s_dph = $model->idInvoice->price + $model->idInvoice->price_vat;
            echo $form->field($dynModel, "suma_s_dph")->textInput(["readonly" => true]);
            ?>
            
            <?= $form->field($model->idInvoice, 'currency')->dropDownList(Invoice::getCurrencyList(), ["readonly" => true]) ?>

            <?= $form->field($model->idInvoice, 'iban')->textInput(['maxlength' => true, "readonly" => true]) ?>

            <?= $form->field($model->idInvoice, 'swift')->textInput(['maxlength' => true, "readonly" => true]) ?>

            <?= $form->field($model->idInvoice, 'vs')->textInput(['maxlength' => true, "readonly" => true]) ?>

            <?= $form->field($model->idInvoice, 'ks')->textInput(['maxlength' => true, "readonly" => true]) ?>

            <?= $form->field($model->idInvoice, 'debet_info')->textInput(['maxlength' => true, "readonly" => true]) ?>
            
        </div>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('invoice_cart', 'Create') : Yii::t('invoice_cart', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <div class="form-group">
        <?= Html::a(Yii::t('main', 'Back'), \yii\helpers\Url::previous(), ['class' => 'btn btn-success']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("
    $('#invoice-price, #invoice-dph').on('input', function(){
        var _price = $('#invoice-price').val();
        var _dph = $('#invoice-dph').val();
        var _res = parseFloat(_price) + parseFloat(_price*_dph/100);
        $('#dynamicmodel-suma_s_dph').val(_res);
    });
");