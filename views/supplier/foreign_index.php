<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t("main", 'Suppliers Foreign');
$this->params['breadcrumbs'][] = $this->title;

\yii\helpers\Url::remember();
?>

<div class="supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <div class="row">
        <?php
        $form = ActiveForm::begin([
                    'method' => 'get',
                    'options' => ['data-pjax' => true],
                    'id' => 'supplier-search-form'
        ]);
        ?>

        <div class="col-md-4">
            <?= $form->field($searchModel, 'search_text')->textInput(['onchange' => 'update();']) ?>
        </div>  
        <div class="col-md-4" style="padding-top: 25px;">
            <?= Html::img(Url::to(["img/arrow_down.png"]), ["id" => "advanced-search-button", "width" => 30, "height" => 30]) ?>
            
            <div class="form-group" style="display: inline-block; padding-left: 30px">
                <?= Html::submitButton(Yii::t("main", 'Search'), ['id' => 'refresh', 'class' => 'btn btn-primary']) ?>
            </div>
        </div>  
        <div class="col-md-12 advanced-search" style="height: 1px; overflow: hidden;">
            <div class="col-md-3">
                <?= $form->field($searchModel, 'name')->textInput() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($searchModel, 'iban')->textInput() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($searchModel, 'bic')->textInput() ?>
            </div>
        </div>

        

        <div class="col-md-12">
            <div class="form-group" style="display: inline-block;/*text-align: right;*/">
                <?= Html::a(Yii::t("main", 'Create'), ['foreign-create'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'contentOptions' => [
//                    'width' => 20
//                ],
//                'template' => "{view}"
//            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'width' => 20
                ],
                'buttons' => [
                    'update' => function ($url, $model) {     
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(["foreign-update", "id" => $model->id]), [
                                'title' => Yii::t('yii', 'Create'),
                        ]); 
                    }
                ],
                'template' => "{update}"
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'width' => 20
                ],
                'template' => "{delete}"
            ],
//            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            [
                'attribute' => 'name',
                'contentOptions' => [
                    'style' => 'max-width: 70px;'
                ]
            ],
            'iban',
            [
                'header' => 'BIC/SWIFT',
                'attribute' => 'bic',
                'value' => function($model){
                    return \app\models\MainModel::getBICFromIBAN($model->iban);
                }
            ]
        ]
    ])
    ?>

    <?php Pjax::end(); ?>
</div>

<?php
$this->registerJs('
        function update(){
            document.getElementById("refresh").click();
        }', yii\web\View::POS_END);

$this->registerJs('
        $(document).on("click", "#advanced-search-button", function(){ 
            if ($(".advanced-search").height() == 1){
                $(".advanced-search").css("height", "auto");
                var height = $(".advanced-search").height();
                $(".advanced-search").css("height", 0);
                $(".advanced-search").animate({"height": height}, 500);
            } else {
                $(".advanced-search").animate({"height": 1}, 500);
            }
        });
');
?>