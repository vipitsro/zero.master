<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BlockySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('supplier_foreign', 'Bločky');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blocky-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a(Yii::t('supplier_foreign', 'Pridať'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'options' => ['data-pjax' => true],
                'id' => 'blocky-search-form'
    ]);

    $datum = Yii::$app->request->get()["BlockySearch"]["year"];
    if (is_null($datum)):
        $datum = date("Y");
    endif;
    $mesiac = Yii::$app->request->get()["BlockySearch"]["month"];
    if (is_null($mesiac)):
        $mesiac = "";
    endif;    
    
    ?>
    <div class="form-group">
        <div class="row">
            <div class="col-md-2">
                <?= $form->field($searchModel, 'year')->input("number", ['onchange' => 'update();', 'value' => $datum])->label("Rok") ?>
            </div>
             <div class="col-md-2">
                <?= $form->field($searchModel, 'month')->input("number", ['onchange' => 'update();', 'value' => $mesiac])->label("Mesiac") ?>
            </div>   

        <div class="col-md-2" style="padding-top: 25px;">
            <?= Html::submitButton(Yii::t("main", 'Search'), ['id' => 'refresh', 'class' => 'btn btn-primary']) ?>
            <?= Html::button(Yii::t("main", 'XLS'), ['id' => 'generate-xls', 'class' => 'btn btn-primary', 'data-pjax' => 0]) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'columns' => [
        [
            'label' => '',
            'format' => 'raw',
            'contentOptions' => [
                'style' => 'min-width: 20px;',
                'width' => 20
            ],
            'value' => function ($model) {

                $x = "";
                if ($model->file) {
                    $x = " <a data-pjax='0' target='_blank' href='" . Url::to('@web/uploads/blocky/' . $model->file, true) . "'><span class='glyphicon glyphicon-eye-open'></span></a>";
                }
                return $x;
            },
        ],
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
        /* [
          'attribute' => 'id',
          'label' => 'Číslo',
          'contentOptions' => [
          'style' => 'min-width: 100px;text-align: left;',
          'width' => 100
          ],
          ], */
        [
            'attribute' => 'intnum',
            'label' => 'Interné číslo',
            'contentOptions' => [
                'style' => 'min-width: 100px;text-align: left;',
                'width' => 100
            ],
        ],
        [
            'attribute' => 'sumabez',
            'headerOptions' => [
                'style' => 'text-align: right;'
            ],
            'value' => function($model) {
                return number_format($model->sumabez, 2) . " EUR";
            },
            'contentOptions' => [
                'style' => 'min-width: 100px;text-align: right;',
                'width' => 100
            ],
        ],
        [
            'attribute' => 'dph',
            'headerOptions' => [
                'style' => 'text-align: right;'
            ],
            'value' => function($model) {
                return number_format($model->dph, 2) . " EUR";
            },
            'contentOptions' => [
                'style' => 'min-width: 100px;text-align: right;',
                'width' => 100
            ],
        ],
        [
            'attribute' => 'sumasdph',
            'headerOptions' => [
                'style' => 'text-align: right;'
            ],
            'value' => function($model) {
                return number_format($model->sumasdph, 2) . " EUR";
            },
            'contentOptions' => [
                'style' => 'min-width: 100px;text-align: right;',
                'width' => 100
            ],
        ],
        'dodavatel',
        'ucel',
        // 'file:ntext',
        [
            'attribute' => 'datum',
            'format' => ['date', 'php:d. m. Y'],
            'contentOptions' => [
                'style' => 'min-width: 110px;text-align: right;',
                'width' => 110
            ],
            'headerOptions' => [
                'style' => 'text-align:right',
            ],
        ],
    // 'added',
    // 'status',
    ],
]);
?>
</div>
<?php
$this->registerJs("
    $(document).on('click', '#generate-xls', function(){
        var _url = window.location.href;
        var _get_params_index = _url.indexOf('?');
        var _new_url = '" . \yii\helpers\Url::to(["blocky/xls"]) . "' + ((_get_params_index == -1) ? '' : _url.substring(_get_params_index));
        window.location = _new_url;
    });
");

$this->registerJs('
    function update(){
        document.getElementById("refresh").click();
    }', yii\web\View::POS_END);
?>