<?php

/**
 * Created by PhpStorm.
 * User: vavrinec
 * Date: 4.6.2015
 * Time: 15:29
 */

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\AdminLoginForm;
use yii\filters\VerbFilter;
use app\components\AccessRule;
use app\models\User;

/**
 * Site controller
 */
class MainController extends Controller {

    public function init() {
        Yii::$app->language = "sk";
        // Get settings from database
        $db = (new \yii\db\Query);
        $settings = $db->select("*")->from("settings")->all();

        foreach ($settings as $key => $val) {
            Yii::$app->params['settings'][$val['setting']] = $val['value'];
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                /* 'ruleConfig' => [
                  'class' => AccessRule::className(),
                  ], */
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                    [
                        'actions' => $this->getAccessRules(),
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                /* [
                  'actions' => ['logout', 'index'],
                  'allow' => true,
                  'roles' => ['@'],
                  ], */
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function getAccessRules() {
        if (Yii::$app->user->identity == NULL)
            return array(" ");

        if (Yii::$app->user->identity->id_access == 1)
            return [];

        $rules = ['error'];
        $controllerName = Yii::$app->controller->id;

        $access = \app\models\Access::find()->where('id = :id', ['id' => Yii::$app->user->identity->id_access])->one();

        if (Yii::$app->user->identity->id_access == 1) {
            return [];
        }

        $access->rights = preg_replace("/[^a-z_\-;]/", "", $access->rights);
//            var_dump($access->rights);
//            exit();
        $rights = explode(";", $access->rights);

        foreach ($rights as $right) {
            if (substr($right, 0, strlen($controllerName)) === $controllerName) {
                $help = substr($right, strlen($controllerName));
                if (strpos($help, "_") !== false) {
                    $rules[] = substr($help, 1);
                }
            }
        }

        if (empty($rules)) {
            return array(" ");
        } else {
            return $rules;
        }
    }

    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
        }
        
        if (Yii::$app->controller->action->id == "login" || Yii::$app->controller->action->id == "changepassword")
            return true;
        
        $log = new \app\models\Log();
        $log->id_user = Yii::$app->user->identity->id;
        $log->controller = Yii::$app->controller->id;
        $log->action = Yii::$app->controller->action->id;
        $log->url = \yii\helpers\Url::current();
        $log->post = \yii\helpers\Json::encode( \Yii::$app->request->post());
        $log->created_at = date("Y-m-d H:i:s");
        $log->save();       
        
        return true;
    }

}
