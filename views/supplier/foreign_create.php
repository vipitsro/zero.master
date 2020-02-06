<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Supplier */

$this->title = Yii::t("main",'Suppliers Foreign') . " - " . Yii::t("main",'Create');
$this->params['breadcrumbs'][] = ['label' => 'Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-create">
    
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_foreign_form', [
        'model' => $model,
    ]) ?>

</div>
