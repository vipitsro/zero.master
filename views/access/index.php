<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accesses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-index">

    <p>
        <?= Html::a(Yii::t("main",'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                    'attribute' => 'rights',
                    'format' => 'html',
                    //'value' => function($data){ return $data->rights;},
                    'value' => function($model,$key, $index){ return app\models\Access::getAdminRuleListHTML($model->id);},
            ],

            [
                'class' => 'yii\grid\ActionColumn',
            ],
        ],
    ]); ?>

</div>
