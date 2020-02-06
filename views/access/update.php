<?php

use yii\helpers\Html;

//var_dump($post);

/* @var $this yii\web\View */
/* @var $model backend\models\Access */

$this->title = 'Update Access: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Accesses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="access-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
