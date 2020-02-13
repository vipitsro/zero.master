<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">


    <?php // Html::a('<span class="logo-mini">PRLN</span><span class="logo-lg">' . Yii::$app->params['settings']['SITE_NAME'] . '</span>', ['/site/index'], ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?>/img/user.png" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?php if (!Yii::$app->user->isGuest) echo Yii::$app->user->identity->username; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $directoryAsset ?>/img/user.png" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?=Yii::$app->user->identity->username?>
<!--                                <small>Member since Nov. 2012</small>-->
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
<!--                            <div class="col-xs-4 text-center">-->
<!--                                <a href="#">Followers</a>-->
<!--                            </div>-->
<!--                            <div class="col-xs-4 text-center">-->
<!--                                <a href="#">Sales</a>-->
<!--                            </div>-->
<!--                            <div class="col-xs-4 text-center">-->
<!--                                <a href="#">Friends</a>-->
<!--                            </div>-->
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?= Url::to(["/user/profile"]) ?>" class="btn btn-default btn-flat">Profile</a>
	                            <?php
//                                    echo Html::a(
//		                            'Sign out',
//		                            ['/user/logout'],
//		                            ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
//	                            ); 
                                    ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Logout',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
