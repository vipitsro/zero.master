<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BlockySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('supplier_foreign', 'Bločky');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blocky-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('supplier_foreign', 'Pridať'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\ActionColumn'],

             [
                 'attribute' => 'id',
                 'label' => 'Číslo'
             ],
            'sumabez',
            'dph',
            'sumasdph',
            'ucel',
            // 'file:ntext',
             [
                 'attribute' => 'datum',
                 'format' => ['date', 'php:d. m. Y']
             ],
            // 'added',
            // 'status',

            
        ],
    ]); ?>
</div>
