<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\Url;
use app\components\CustomDatePicker\CustomDatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Blocky */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blocky-form">

    <?php $form = ActiveForm::begin([ "options" => ["id" => 'blocky-form', 'enctype' => 'multipart/form-data']]); ?>
    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'sumabez')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dph')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sumasdph')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ucel')->textInput() ?>



    <?= $form->field($model, 'datum')->widget(CustomDatePicker::className(), ['options' => ['class' => 'form-control'], 'language' => 'sk', 'dateFormat' => 'dd.MM.yyyy', "isRTL" => true]); ?>

    <!--<?= $form->field($model, 'added')->textInput() ?>-->

    <!--<?= $form->field($model, 'status')->textInput() ?>-->

         <br/>
        <div class="form-group">
		<div class="input-group input-file" name="Blocky[file]">
    		<input type="text" class="form-control" style='position: static;' placeholder='Vybrať súbor ...' />			
            <span class="input-group-btn">
        		<button class="btn btn-default btn-choose" type="button">Vybrať súbor</button>
    		</span>


		</div>
	</div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('supplier_foreign', 'Pridať') : Yii::t('supplier_foreign', 'Upraviť'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php if ($model->file != ""):?>
    <iframe id="fred" style="border:1px solid #666CCC" title="PDF in an i-Frame" src="<?= Url::to('@web/uploads/blocky/' . $model->file, true) ?>" frameborder="1" scrolling="auto" height="1100" width="850" ></iframe>
    <?php endif;?>
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
    
");
