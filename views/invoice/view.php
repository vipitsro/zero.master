<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Invoice */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-view">

    <p>
        <?= Html::a(Yii::t("main", 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t("main", 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <div class="col-md-12 invoice-detail">
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?= $model->getAttributeLabel("id_supplier") ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?= $model->supplier?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?= $model->getAttributeLabel("account_prefix") ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?= $model->account_prefix ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?= $model->getAttributeLabel("account_number") ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?= $model->account_number ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?= $model->getAttributeLabel("bank_code") ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?= $model->bank_code ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?= $model->getAttributeLabel("iban") ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?= $model->iban ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?= $model->getAttributeLabel("swift") ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?= $model->swift ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?= $model->getAttributeLabel("ks") ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?= $model->ks ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?= $model->getAttributeLabel("vs") ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?= $model->vs ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?= $model->getAttributeLabel("debet_info") ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?= $model->debet_info ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?= $model->getAttributeLabel("internal_number") ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?= $model->internal_number ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?= $model->getAttributeLabel("price") ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?= number_format($model->price, 2, ".", " ") . " " . app\models\MainModel::getCurrencyName($model->currency) ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?= $model->getAttributeLabel("dph") ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?= number_format($model->price_vat, 2, ".", " ") . " " . app\models\MainModel::getCurrencyName($model->currency) ?>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov">Cena s DPH</div>
                    <div class="col-md-8 invoice-hodnota">
                        <?php
                        $currencyList = $model->getCurrencyList();
                        echo number_format($model->price + $model->price_vat, 2, ".", " ") . " " . $currencyList[$model->currency]
                        ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?= $model->getAttributeLabel("date_1") ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?= date("d.m.Y", strtotime($model->date_1)) ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?= $model->getAttributeLabel("date_2") ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?= date("d.m.Y", strtotime($model->date_2)) ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?= $model->getAttributeLabel("date_3") ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?= date("d.m.Y", strtotime($model->date_3)) ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4 invoice-nazov"><?php echo (($model->invoiceBatches != null) ? $model->invoiceBatches[0]->getAttributeLabel('date_1') : ""); ?></div>
                    <div class="col-md-8 invoice-hodnota">
                        <?php 
                        foreach ($model->invoiceBatches as $m){
                            echo date("d.m.Y", strtotime($m->date_1));
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .invoice-detail{
        background-color: #fff;
        padding-top: 30px;
        padding-bottom: 30px;
        /*border: #fff 1px solid;*/
    }

    .invoice-nazov{
        font-weight: bold;
    }

    .invoice-nazov, .invoice-hodnota{
        min-height: 30px;
        line-height: 30px;
        /*border-top: #ccc 1px solid;*/
        border-bottom: #ccc 1px solid;
    }
</style>