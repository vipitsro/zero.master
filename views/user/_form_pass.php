<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->errorSummary($model); ?>
    
    <?= $form->field($model, 'password_hash')->passwordInput(['value'=>'']) ?>
    
    <?= $form->field($model, 'password_hash_check')->passwordInput(['value'=>'']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t("user",'Change password'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("
    $('input[name=\"User[password_hash]\"], input[name=\"User[password_hash_check]\"]').on('input', function(){
        var _pas1 = $('input[name=\"User[password_hash_check]\"]');
        var _pas2 = $('input[name=\"User[password_hash]\"]');
        if (_pas1.val() !== _pas2.val() || _pas1.val().length < 8){
            _pas1.css('color', 'red');
            _pas2.css('color', 'red');
        } else {
            _pas1.css('color', 'green');
            _pas2.css('color', 'green');
        }
    }); 
");