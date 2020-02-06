<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Supplier */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="supplier-form">
    <?php 
    if  ($model->isNewRecord && $model->ks == ""){
        $model->ks = "0308";
    }
    ?>

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'iban')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'bic')->textInput(['maxlength' => true, "readonly" => true]) ?>

    <?= $form->field($model, 'ks')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'typical_service')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t("main",'Create') : Yii::t("main",'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    
    <div class="form-group">
        <?= Html::a(Yii::t('main', 'Back'), \yii\helpers\Url::previous(), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
$this->registerJs("
    $('input[name=\"Supplier[iban]\"]').on('input', function(){
        
        function chunk(str, n) {
            var ret = [];
            var i;
            var len;

            for(i = 0, len = str.length; i < len; i += n) {
               ret.push(str.substr(i, n))
            }
            
            console.log(ret);

            return ret;
        };

        var _v_trim = $(this).val().replace(/ /g,'');
        var _v_new = chunk(_v_trim, 4).join(' ');
        
        $(this).val(_v_new);
        
        var _iban = $(this).val();
        $.ajax({
            url : '" . Url::to(["get-bic"]) . "',
            method : 'POST',
            data : {iban : _iban},
            success : function(res){
                res = JSON.parse(res);
                console.log(res);
                $('input[name=\"Supplier[bic]\"]').val(res.bic);
            }
        });
    });
    
");
?>
