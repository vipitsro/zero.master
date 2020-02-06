<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Access;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Backend Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">

    <p>
        <?= Html::a(Yii::t("main",'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'username',
            [                      // the owner name of the model
                'label' => Yii::t("user",'Rights'),
                'value' => function($model,$key, $index){ return $model->role0->name;},
            ],
            /*[                      // the owner name of the model
                'label' => 'Rights',
                'format' => 'html',
                'value' => function($model,$key, $index){ return Access::getAdminRuleListHTML($model->id);},
            ],*/
            //'auth_key',
            //'password_hash',
            // 'password_reset_token',
            // 'email:email',
            // 'id_access',
            // 'status',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
