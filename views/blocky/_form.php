<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\Url;
use app\components\CustomDatePicker\CustomDatePicker;
use yii\base\DynamicModel;

/* @var $this yii\web\View */
/* @var $model app\models\Blocky */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blocky-form">

    <?php $form = ActiveForm::begin(["options" => ["id" => 'blocky-form', 'enctype' => 'multipart/form-data']]); ?>
    <?= $form->errorSummary($model); ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'sumabez')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'dph')->textInput() ?>
        </div>

    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'sumasdph')->textInput(['maxlength' => true, 'readonly' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'dodavatel')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'ucel')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'datum')->widget(CustomDatePicker::className(), ['options' => ['class' => 'form-control'], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy', "isRTL" => true]); ?>
        </div>


        <div class="col-md-4" >
            <div class="input-group input-file" name="Blocky[file]">
                <label>Súbor</label>
                <input type="text" class="form-control" style='position: static;' placeholder='Vybrať súbor ...' />
                <span class="input-group-btn" style="padding-top:25px;">
                    <button class="btn btn-default btn-choose" type="button">Vybrať súbor</button>
                </span>


            </div>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('supplier_foreign', 'Pridať') : Yii::t('supplier_foreign', 'Upraviť'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'submit-button']) ?>
        <a href="<?= Yii::$app->request->referrer; ?>"><siv class="btn btn-primary">Späť</div></a>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if ($model->file != ""): ?>
        <iframe id="fred" style="border:1px solid #666CCC" title="PDF in an i-Frame" src="<?= Url::to('@web/uploads/blocky/' . $model->file, true) ?>" frameborder="1" scrolling="auto" height="1100" width="850" ></iframe>
    <?php endif; ?>
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
</div>

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

    $('#blocky-sumabez, #blocky-dph').on('input', function(){
        $(this).val($(this).val().replace(',', '.'));
        var _price = $('#blocky-sumabez').val();
        var _price_vat = $('#blocky-dph').val();
        var _res1 = (parseFloat(_price) + parseFloat(_price_vat)).toFixed(2); // SCITANA
        var _res2 = (parseFloat(_price) + parseFloat(_price*0.2)).toFixed(2); // VYPOCITANA PRI 20% DPH
        $('#blocky-sumasdph').val(_res1);
        if (_res1 == _res2){
            $('#blocky-sumasdph').css('borderColor', '#8f8');
        } else {
            $('#blocky-sumasdph').css('borderColor', '#f88');
        }
    });

    $('#blocky-form #submit-button').click(function(event){
        event.preventDefault();
        var _price = $('#blocky-sumabez').val();
        var _price_vat = $('#blocky-dph').val();
        var _res1 = (parseFloat(_price) + parseFloat(_price_vat)).toFixed(2); // SCITANA
        var _res2 = (parseFloat(_price) + parseFloat(_price*0.2)).toFixed(2); // VYPOCITANA PRI 20% DPH
//        $('#blocky-sumasdph').val(_res1);
        if (_res1 != _res2){
            $( '#dialog-confirm' ).dialog({
                resizable: false,
                height:200,
                open: function(){
                    $('.ui-dialog-titlebar-close').hide();
                },
                buttons: {
                    'Áno': function() {
                        $('#blocky-form').submit();
                        $( this ).dialog( 'close' );
                    },
                    'Nie': function() {

                        $( this ).dialog( 'close' );

                    }
                }
            });
        } else {
            $('#blocky-form').submit();
        }
    });



");
