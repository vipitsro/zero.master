<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Admin */

//var_dump($modelAdmin);
//var_dump($modelUserData);

$this->title = 'Create Admin';
$this->params['breadcrumbs'][] = ['label' => 'Background Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
