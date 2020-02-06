<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Access */

$this->title = 'Create Access';
$this->params['breadcrumbs'][] = ['label' => 'Accesses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
