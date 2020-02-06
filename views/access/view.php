<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Access;

/* @var $this yii\web\View */
/* @var $model backend\models\Access */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Accesses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'name',
            [                      // the owner name of the model
                    'attribute' => 'rights',
                    'label' => 'Rights',
                    'format' => 'html',
                    //'value' => function($data){ return $data->rights;},
                    'value' => Access::getAdminRuleListHTML($model->id),
            ],
        ],
    ]) ?>

</div>
