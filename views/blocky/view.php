<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Blocky */

$this->title = "Bloček číslo " . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('supplier_foreign', 'Bločky'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blocky-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('supplier_foreign', 'Upraviť'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('supplier_foreign', 'Vymazať'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('supplier_foreign', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'sumabez',
            'dph',
            'sumasdph',
            'ucel',
            [
                'attribute' => 'datum',
                'format' => ['date', 'php:d. m. Y']
            ],
            [
                'attribute' => 'file',
                'label' => 'File',
                'format' => 'raw',
                'value' => '<iframe id="fred" style="border:1px solid #666CCC" title="PDF in an i-Frame" src="' . Url::to('@web/uploads/blocky/' . $model->file, true) . '" frameborder="1" scrolling="auto" height="850" width="850" ></iframe>'
            ],
        //  'added',
        // 'status',
        ],
    ])
    ?>

</div>
