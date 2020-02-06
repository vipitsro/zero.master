<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('main', 'Banks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('main', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}', 
                'contentOptions' => [
                    'style' => 'min-width: 20px;',
                    'width' => 20
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}', 
                'contentOptions' => [
                    'style' => 'min-width: 20px;',
                    'width' => 20
                ],
            ],

//            'id',
            'name',
            'code',
            'bic',
        ],
    ]); ?>

</div>
