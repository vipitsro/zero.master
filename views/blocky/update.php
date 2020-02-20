<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Blocky */

$this->title = Yii::t('supplier_foreign', 'Upraviť Bloček čislo: ', []) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('supplier_foreign', 'Blockies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('supplier_foreign', 'Update');
?>
<div class="blocky-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
