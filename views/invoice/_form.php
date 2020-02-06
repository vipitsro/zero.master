<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use app\components\CustomDatePicker\CustomDatePicker;
use app\models\Supplier;
use yii\web\View;
use app\models\Invoice;
use app\models\Tag;
use yii\base\DynamicModel;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Invoice */
/* @var $form yii\widgets\ActiveForm */

$get = Yii::$app->request->get();
?>



<?php
$this->registerJs('
    function supplierUpdate(){
        $.post("/supplier/bankdata?id="+$(this).val(),function( data ) {
            var json = $.parseJSON(data);
            $( "input#invoice-account_prefix" ).val( json.account_prefix );
            $( "input#invoice-account_number" ).val( json.account_number );
            $( "input#invoice-bank_code" ).val( json.bank_code );
            $( "input#invoice-iban" ).val( json.iban );
            $( "input#invoice-swift" ).val( json.swift );
            $( "input#invoice-ks" ).val( json.ks );
            $( "input#invoice-info" ).val( json.info );
        });
    }', View::POS_END, "supplierUpdate");
?>

<div class="invoice-form">

    <?php $form = ActiveForm::begin([ "options" => ["id" => 'invoice-form', 'enctype' => 'multipart/form-data']]); ?>

    <?= $form->errorSummary($model); ?>




    <script>
        var cislo_firmy = "<?= app\models\Settings::find()->where(["setting" => "COMPANY_ID"])->one()->value ?>";
        var typ_dokladu = "0";
        var aktualny_uctovny_rok = "<?php echo (isset($get['year']) ? substr($get['year'],2) : date("y")); ?>";
        var poradove_cislo_dokladu = "000";

        function updateInterneCislo() {
            $.post("./serialnumber?cislo_firmy=" + cislo_firmy +
                    "&typ_dokladu=" + typ_dokladu +
                    "&aktualny_uctovny_rok=" + aktualny_uctovny_rok,
                    function (data) {
                        poradove_cislo_dokladu = data;
                        $("#invoice-internal_number").val(cislo_firmy + typ_dokladu + aktualny_uctovny_rok + poradove_cislo_dokladu);
                    });
        }
    </script>
    <div class='col-md-8'  style='border-right: 1px solid #e0e0e5;'>
        <div class="row">
            <?php
            if (!$model->isNewRecord){
                $model->_typ_dokladu = substr($model->internal_number,1,1);
            }
            ?>
            <div class="col-md-4">
                <label class="control-label" for="invoice-typ_dokladu">Typ dokladu</label>
                <?= Html::radioList("typ_dokladu", $model->_typ_dokladu, array_slice(Invoice::getTypydokladu(),0,3,true), ['itemOptions' => ['disabled' => !$model->isNewRecord]]); ?>               
            </div>
            <div class="col-md-4">
                <label class="control-label" for="invoice-typ_dokladu"> </label>
                <?= Html::radioList("typ_dokladu", $model->_typ_dokladu, array_slice(Invoice::getTypydokladu(),3,2,true), ['itemOptions' => ['disabled' => !$model->isNewRecord]]); ?>               
            </div>
            <div class="col-md-4">
                <?php
                $disabled = [];
                if (!$model->isNewRecord)
                    $disabled['disabled'] = 'true';
                ?>
                <?= $form->field($model, 'internal_number')->textInput($disabled) ?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <?php                
                $supp = [
                    "TOP" => \yii\helpers\ArrayHelper::map($suppliers_best, "id", "name"), 
                    "Iné" => \yii\helpers\ArrayHelper::map($suppliers_other, "id", "name")];
                if (empty($model->_typ_dokladu)){
                    $supp = [];
                }
                echo $form->field($model, 'id_supplier')->dropDownList($supp, [
                    'prompt' => 'Vyber',
                ])
                ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'iban')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'service')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <?php if ($model->date_1 == NULL){
                    $model->date_1 = date("d.m.Y");
                }
                ?>
                <?= $form->field($model, 'date_1')->widget(CustomDatePicker::className(), ['options' => ['class' => 'form-control'], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy', "isRTL" => true]) ?>

                <?= $form->field($model, 'date_2')->widget(CustomDatePicker::className(), ['options' => ['class' => 'form-control'], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy', "isRTL" => true]) ?>

                <?= $form->field($model, 'date_3')->widget(CustomDatePicker::className(), ['options' => ['class' => 'form-control'], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy', "isRTL" => true]) ?>         
                
                <?= $form->field($model, 'vs')->textInput() ?>         
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'price')->input('number', ['step' => '0.01']) ?>

                <?= $form->field($model, 'price_vat')->input('number', ['step' => '0.01']) ?>

                <?= $form->field($model, 'currency')->dropDownList(Invoice::getCurrencyList(), ["readonly" => ($model->isNewRecord ? false : true)]) ?>

                <?php
                $dynModel = new DynamicModel(["suma_s_dph"]);
                $dynModel->addRule("suma_s_dph", "string");
                $dynModel->suma_s_dph = $model->price + $model->price_vat;
                ?>

                <?= $form->field($dynModel, 'suma_s_dph')->textInput(["readonly" => true])->label("Suma s DPH") ?>

            </div>
        </div>
        <?php
        if ($model->file != ""){
            echo "<a class='btn btn-primary' target='_blank' href='". Url::to('@web/uploads/'.$model->file, true)."'>Doklad</a>";
            //echo "&nbsp;&nbsp;&nbsp;<a class='btn btn-warning' href='/invoice/delete-file?id=".$model->id."'>Vymazať</a>";
        }
        
        ?>
        <br/><br/>
        <div class="form-group">
		<div class="input-group input-file" name="Invoice[file]">
    		<input type="text" class="form-control" style='position: static;' placeholder='Vybrať súbor ...' />			
            <span class="input-group-btn">
        		<button class="btn btn-default btn-choose" type="button">Vybrať súbor</button>
    		</span>


		</div>
	</div>
		<!--<?= $form->field($model, 'file')->fileInput() ?>--> 
    </div>

    <div class="col-md-4">
        <div class='row'>
            <div class='col-md-12'>
                <strong>PLATBY</strong>
            </div>
        </div>
        <hr>
        <?php   
        $typy_platby = app\models\MainModel::getTypyPlatby();
        
        echo app\components\DynamicInput\DynamicInput::widget([
            'name' => 'Pridať Platbu',
            'models' => $payments,
            'attributes' => [
                [
                    'title' => '',
                    'name' => 'InvoicePay[id][]',
                    'type' => 'text',
                    'attribute' => "id",
                    'values' => function($model){ return $model->id; },
                    'htmlOptions' => ["class" => 'id-hidden'],
                ],
                [
                    'title' => 'Dátum platby',
                    'name' => 'InvoicePay[date_payment][]',
                    'type' => 'text',
                    'attribute' => 'date_payment',
                    'values' => function($model){ return $model->date_payment; },
                    'htmlOptions' => ["class" => 'inv-date'],
                ],
                [
                    'title' => 'Suma',
                    'name' => 'InvoicePay[price][]',
                    'type' => 'number',
                    'attribute' => 'price',
                    'values' => function($model){ return $model->price; },
                    'htmlOptions' => ["class" => ''],
                ],

                [
                    'title' => 'Status',
                    'name' => 'InvoicePay[status][]',
                    'type' => 'dropdown',
                    'attribute' => 'status',
                    'values' => $typy_platby,
                    'htmlOptions' => ["class" => ''],
                ],
            ]
        ]); 
        ?>
    </div>

    <div class='col-md-6'>
        <div class="form-group">
            <?= Html::button($model->isNewRecord ? Yii::t("main", 'Create') : Yii::t("main", 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'submit-button']) ?>
			<?= "&nbsp;&nbsp;&nbsp;".Html::a(Yii::t('main', 'Back'), \yii\helpers\Url::previous(), ['class' => 'btn btn-success']) ?>
		</div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?if ($model->file != ""):?>
<iframe id="fred" style="border:1px solid #666CCC" title="PDF in an i-Frame" src="<?=Url::to('@web/uploads/'.$model->file, true)?>" frameborder="1" scrolling="auto" height="1100" width="850" ></iframe>
<?endif;?>
<div id="dialog-confirm" title="Sumy" hidden>
  <p>Sumy sa nezhodujú. Chcete pokračovať ?</p>
</div>

<style>
    .id-hidden{
        display: none;
    }
    
    .delete{
        font-size: 20px;
        padding-left: 10px;
        padding-right: 10px;
    }
</style>

<?php
$this->registerJs("
    
	function bs_input_file() {
		$('.input-file').before(
			function() {
				if ( ! $(this).prev().hasClass('input-ghost') ) {
					var element = $(\"<input type='file' class='input-ghost' style='visibility:hidden; height:0'>\");
					element.attr(\"name\",$(this).attr(\"name\"));
					element.change(function(){
						element.next(element).find(\"input\").val((element.val()).split('\\\').pop());
					});
					$(this).find(\"button.btn-choose\").click(function(){
						element.click();
					});
					$(this).find(\"button.btn-reset\").click(function(){
						element.val(null);
						$(this).parents(\".input-file\").find('input').val('');
					});
					$(this).find('input').css(\"cursor\",\"pointer\");
					$(this).find('input').mousedown(function() {
						$(this).parents('.input-file').prev().click();
						return false;
					});
					return element; 
				}
			}
		);
	}
	$(function() {
		bs_input_file();
	});	
		
	
	
	
	$('input[name=\"typ_dokladu\"]').on('change', function(){
        typ_dokladu = $(this).val();
        updateInterneCislo();
    });
    
    $('select[name=\"cislo_firmy\"]').on('change', function(){
        cislo_firmy = $(this).val();
        updateInterneCislo();
    });
    
    $('select[name=\"aktualny_uctovny_rok\"]').on('change', function(){
        aktualny_uctovny_rok = $(this).val();
        updateInterneCislo();
    });
    
    $('.deletePayment').on('click', function(){
        $(this).closest('tr').remove();
    });
    
    $('input[name=\"typ_dokladu\"]').on('click', function(){

        // CURRENCY FORCE EUR ON HOME INVOICE
        if ($(this).val() == 5 || $(this).val() == 7){ 
            $('#invoice-currency option').first().prop('selected', true);
            $('#invoice-currency').attr('readonly', true);
            $('#invoice-currency option:not(:selected)').prop('disabled', true);
        } else {
            $('#invoice-currency').attr('readonly', false);
            $('#invoice-currency option').prop('disabled', false);
        }
            
        // GET SUPPLIERS
        $( 'input#invoice-iban' ).val('');
        var _typ_dokladu = $(\"input[name='typ_dokladu']:checked\").val();
        $.ajax({
            url : '../invoice/get-suppliers',
            method : 'POST',
            data : {
                typ_dokladu : _typ_dokladu
            },
            success : function(res){
                res = JSON.parse(res);
                console.log(res);
                $('select[name=\"Invoice[id_supplier]\"]').html('<option value>Vyber</option><optgroup label=\"BEST\"></optgroup><optgroup label=\"Other\"></optgroup>');
                for (i=0; i<res.suppliers_best.length; i++){
                    var _opt = $('<option></option>').val(res.suppliers_best[i].id).html(res.suppliers_best[i].name);
                    $('select[name=\"Invoice[id_supplier]\"]').find('optgroup[label=\"BEST\"]').append(_opt);
                }
                for (i=0; i<res.suppliers_other.length; i++){
                    var _opt = $('<option></option>').val(res.suppliers_other[i].id).html(res.suppliers_other[i].name);
                    $('select[name=\"Invoice[id_supplier]\"]').find('optgroup[label=\"Other\"]').append(_opt);
                }
            }
        });
    });
    
    $('select[name=\"Invoice[id_supplier]\"]').on('change', function(){
        var _id = $(this).find('option:selected').val();
        var _typ_dokladu = $(\"input[name='typ_dokladu']:checked\").val();
        $.ajax({
            url : '../invoice/bankdata',
            method : 'POST',
            data : {
                id : _id, 
                typ_dokladu : _typ_dokladu
            },
            success : function(res){
                var json = $.parseJSON(res);
                console.log(json);
                $( 'input#invoice-supplier' ).val( json.name );
                $( 'input#invoice-iban' ).val( json.iban );
                $( 'input#invoice-service' ).val( json.service );
            }
        });
    });
    
    $('#invoice-price, #invoice-price_vat').on('input', function(){
        var _price = $('#invoice-price').val();
        var _price_vat = $('#invoice-price_vat').val();
        var _res1 = (parseFloat(_price) + parseFloat(_price_vat)).toFixed(2); // SCITANA
        var _res2 = (parseFloat(_price) + parseFloat(_price*0.2)).toFixed(2); // VYPOCITANA PRI 20% DPH
        $('#dynamicmodel-suma_s_dph').val(_res1);
        if (_res1 == _res2){
            $('#dynamicmodel-suma_s_dph').css('borderColor', '#8f8');
        } else {
            $('#dynamicmodel-suma_s_dph').css('borderColor', '#f88');
        }
    });
    
    $('#invoice-form #submit-button').click(function(event){
        var _price = $('#invoice-price').val();
        var _price_vat = $('#invoice-price_vat').val();
        var _res1 = (parseFloat(_price) + parseFloat(_price_vat)).toFixed(2); // SCITANA
        var _res2 = (parseFloat(_price) + parseFloat(_price*0.2)).toFixed(2); // VYPOCITANA PRI 20% DPH
//        $('#dynamicmodel-suma_s_dph').val(_res1);
        if (_res1 != _res2){
            $( '#dialog-confirm' ).dialog({
                resizable: false,
                height:200,
                open: function(){
                    $('.ui-dialog-titlebar-close').hide();
                },
                buttons: {
                    'Áno': function() {
                        $('#invoice-form').submit();
                        $( this ).dialog( 'close' );
                    },
                    'Nie': function() {

                        $( this ).dialog( 'close' );
                    }
                }
            });
        } else {
            $('#invoice-form').submit();
        }
    });
    
    $('select[name=\"InvoicePay[status][]\"]').each(function(){
        if ($(this).val() == 100){
            $(this).closest('tr').find('input, select').attr('readonly', true);
            $(this).closest('tr').find('td.delete');
        }
    });
    
    
");

if (!$model->isNewRecord){
    $this->registerJs("
    
        $('#invoice-currency').attr('readonly', true);
        $('#invoice-currency option:not(:selected)').prop('disabled', true);
        
    ");
}

$this->registerJs("
    $('.field-invoice-date_1, .field-invoice-date_2, .field-invoice-date_3').on('click', function(){
//        $('#ui-datepicker-div').stop().css('opacity', 1);
        var _w = $('.field-invoice-date_1').width();
        if (_w < 262) return false;
        
        var _old_left = $('#ui-datepicker-div').position().left;
        var _new_left = _old_left + _w - 262;
        $('#ui-datepicker-div').css('left', _new_left);
    });
    

");
