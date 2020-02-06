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
    
    <?= $form->field($model, 'username')->textInput() ?>
    
    <?= $form->field($model, 'email')->textInput() ?>
    
    <?php if ($model->isNewRecord){ ?>
        <?= $form->field($model, 'password_hash')->passwordInput(['value'=>'']) ?>
    <?php } ?>
    
    <?= $form->field($model, 'status')->dropDownList(['10'=>Yii::t("main",'Active'), '0'=>Yii::t("main",'Not active')]); ?>
    
    <?= $form->field($model, 'id_access')->dropDownList(\app\models\Access::getList()); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t("main",'Create') : Yii::t("main",'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
    <?php if (!$model->isNewRecord){ ?>
        <?= Html::a(Yii::t("user",'Change password'), ['/user/changepassword?id='.$model->id], ['class'=>'']) ?>
    <?php } ?>

</div>
