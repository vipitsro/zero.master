<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Blocky */

$this->title = Yii::t('supplier_foreign', 'Pokladničný doklad');
$this->params['breadcrumbs'][] = ['label' => Yii::t('supplier_foreign', 'Blockies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blocky-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
