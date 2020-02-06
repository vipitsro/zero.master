<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = 'About';

$this->beginBlock('content-header'); ?>
About <small>static page</small>
<?php $this->endBlock(); ?>

<div class="site-about">
    <div>
        <span class="pay-invoice glyphicon glyphicon-usd" width="20" height="20" title="" data-toggle="tooltip" data-delay="1000" data-container="body" style="cursor: pointer; margin: 2px; color: #3c8dbc;" data-original-title="Uhradiť sumu"></span>
        - zmena ceny na zaplatenie
    </div>
    <div>
        <span class="pay-invoice glyphicon glyphicon-download-alt" width="20" height="20" title="" data-toggle="tooltip" data-delay="1000" data-container="body" style="cursor: pointer; margin: 2px; color: #3c8dbc;" data-original-title="Uhradiť sumu"></span>
        - pridat do zoznamu na úhradu
    </div>
    <!--<div>
        <span class="pay-invoice glyphicon glyphicon-list" width="20" height="20" title="" data-toggle="tooltip" data-delay="1000" data-container="body" style="cursor: pointer; margin: 2px; color: #3c8dbc;" data-original-title="Uhradiť sumu"></span>
        - ak je v batchi, tak presun do batchu
    </div>-->
    <div>
        <span class="pay-invoice glyphicon glyphicon-pencil" width="20" height="20" title="" data-toggle="tooltip" data-delay="1000" data-container="body" style="cursor: pointer; margin: 2px; color: #3c8dbc;" data-original-title="Uhradiť sumu"></span>
        - úprava
    </div>
    <div>
        <span class="pay-invoice glyphicon glyphicon-trash" width="20" height="20" title="" data-toggle="tooltip" data-delay="1000" data-container="body" style="cursor: pointer; margin: 2px; color: #3c8dbc;" data-original-title="Uhradiť sumu"></span>
        - vymazanie
    </div>
</div>