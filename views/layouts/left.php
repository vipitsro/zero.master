<?php

use yii\bootstrap\Nav;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <?php 
        $files = scandir(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "web" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "logo");
        $image = "";
        foreach ($files as $file){
            if (in_array($file, [".", ".."])){
                continue;
            } else {
                $image = $file;
            }
        }
        ?>
        <div>
            <?= yii\helpers\Html::img(\yii\helpers\Url::to(["img/logo/".$image]), ["style" => "width: 100%;"]) ?>
        </div>
        
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?php if (!Yii::$app->user->isGuest) echo Yii::$app->user->identity->username; ?> </p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
<!--        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="<?= Yii::t("main","Search...") ?>"/>
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>-->
        <!-- /.search form -->

        <?=
        Nav::widget(
                [
                    'encodeLabels' => false,
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => [
                        '<li class="header">' . $all_settings[array_search('SITE_NAME', array_column($all_settings, 'setting'))]['value'] . " - " . $all_settings[array_search('COMPANY_NAME', array_column($all_settings, 'setting'))]['value'] . '</li>',
                        ['label' => '<i class="glyphicon glyphicon-list-alt"></i><span>'.Yii::t("main", "Invoices").'</span>', 'url' => ['/invoice/index']],
                        ['label' => '<i class="glyphicon glyphicon-list-alt"></i><span>'.Yii::t("main", "Bločky").'</span>', 'url' => ['/blocky/index']],
                        ['label' => '<i class="glyphicon glyphicon-download-alt"></i><span>'.Yii::t("main", "To pay").'</span>', 'url' => ['/invoice-cart/index']],
                        ['label' => '<i class="glyphicon glyphicon-book"></i><span>'.Yii::t("main", "Batches").'</span>', 'url' => ['/invoice-batch/index']],
//                        ['label' => '<i class="glyphicon glyphicon-user"></i><span>'.Yii::t("main", "Suppliers").'</span>', 'url' => ['/supplier/index']],
//                        ['label' => '<i class="glyphicon glyphicon-lock"></i><span>'.Yii::t("main","Tags").'</span>', 'url' => ['/tag/index']],
//                        [
//                            'label' => '<i class="glyphicon glyphicon-lock"></i><span>Sing in</span>', //for basic
//                            'url' => ['/site/login'],
//                            'visible' => Yii::$app->user->isGuest
//                        ],
                    ],
                ]
        );
        ?>

        <ul class="sidebar-menu">
            <li class="treeview">
                <a href="#">
                    <i class="glyphicon glyphicon-user"></i> <span><?= Yii::t("main", "Suppliers") ?></span>
                    <i class="fa fa-angle-double-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= \yii\helpers\Url::to(['/supplier/home']) ?>">
                            <span class="glyphicon glyphicon-home"></span> 
                            <?= Yii::t("main", "Home") ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= \yii\helpers\Url::to(['/supplier/foreign']) ?>">
                            <span class="glyphicon glyphicon-globe"></span> 
                            <?= Yii::t("main", "Foreign") ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= \yii\helpers\Url::to(['/supplier/other']) ?>">
                            <span class="glyphicon glyphicon-question-sign"></span> 
                            <?= Yii::t("main", "Other") ?>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-wrench"></i> <span><?= Yii::t("main", "Settings") ?></span>
                    <i class="fa fa-angle-double-down pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= \yii\helpers\Url::to(['/settings/index']) ?>">
                            <span class="fa fa-cog"></span> 
                            <?= Yii::t("main", "Main settings") ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= \yii\helpers\Url::to(['/bank/index']) ?>">
                            <span class="fa fa-usd"></span> 
                            <?= Yii::t("main", "Banks") ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= \yii\helpers\Url::to(['/user/index']) ?>">
                            <span class="fa fa-user"></span> 
                            <?= Yii::t("main", "Users") ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= \yii\helpers\Url::to(['/access/index']) ?>">
                            <span class="fa fa-user-secret"></span> 
                            <?= Yii::t("main", "Roles") ?>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        
        <?php
        echo '<p style="color:silver;padding: 10px;">Current PHP version: ' . phpversion().'</p>';
        /*
        <ul class="sidebar-menu">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i> <span>Same tools</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= \yii\helpers\Url::to(['/gii']) ?>">
                            <span class="fa fa-file-code-o"></span> 
                            Gii
                        </a>
                    </li>
                    <li>
                        <a href="<?= \yii\helpers\Url::to(['/debug']) ?>">
                            <span class="fa fa-dashboard"></span> 
                            Debug
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-circle-o"></i> 
                            Level One 
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-circle-o"></i> Level Two <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
        */
        ?>

    </section>

</aside>
