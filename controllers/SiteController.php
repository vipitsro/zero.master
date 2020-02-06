<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends MainController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
//        return $this->render('index');
        return $this->redirect('invoice/index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(["/invoice/index"]);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect("site/index");
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    
    public function actionFlushCache(){
        Yii::$app->cache->flush();
    }
    
    public function actionParse($id){
        if ($id == 0){
            $csv = file_get_contents(__DIR__ . "\..\web\csv\PRNL_2014_dodavatelia.csv");
            \app\models\Supplier::parseSuppliers($csv);
        } else if ($id == 1){
            $csv = file_get_contents(__DIR__ . "\..\web\csv\PRNL_2014_FDSK.csv");
            \app\models\Invoice::parseFDSK($csv);
        }
    }
    
    public function actionTest(){
//        \Yii::$app->db->open();
//        \Yii::$app->db->pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
//        
//        $sql_old_data = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_db" . DIRECTORY_SEPARATOR . "migration" . DIRECTORY_SEPARATOR . "old_database.sql");
//        $sql_bank = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_db" . DIRECTORY_SEPARATOR . "migration" . DIRECTORY_SEPARATOR . "bank.sql");
//        $sql_supplier_foreign = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_db" . DIRECTORY_SEPARATOR . "migration" . DIRECTORY_SEPARATOR . "supplier_foreign.sql");
//        
//        $connection = Yii::$app->getDb();
//        
//        $command = $connection->createCommand($sql_old_data . $sql_bank . $sql_supplier_foreign)->query();
//        var_dump($command);
//        $command = $connection->createCommand("");
//        $command->dropColumn("invoice", "account_prefix");
//        $command->query();
//        $command->dropColumn("invoice", "account_number");
//        $command->query();
//        $command->dropColumn("invoice", "bank_code");
//        $command->query();
//        $command->dropColumn("invoice", "swift");
//        $command->query();
//        $command->dropColumn("invoice", "ks");
//        $command->query();
//        $command->dropColumn("invoice", "debet_info");
//        $command->query();
//        $command->dropColumn("invoice", "comment");
//        $command->query();
        
//        exit();
        
        $modelsInvoiceBatch = \app\models\InvoiceBatch::find()->all();
        foreach ($modelsInvoiceBatch as $m){
            $modelInvoicePay = \app\models\InvoicePay::find()->where(["id" => $m->id_invoice_pay])->one();
            if (!$modelInvoicePay){
                $modelInvoicePay = new \app\models\InvoicePay();
            }
            $modelInvoicePay->paid = $m->paid;
            $modelInvoicePay->price = $m->price;
            $modelInvoicePay->id_invoice = $m->id_invoice;
            $modelInvoicePay->status = 100;
            $modelInvoicePay->date_payment = $m->date_1;
            $modelInvoicePay->save();
            $m->id_invoice_pay = $modelInvoicePay->id;
            $m->save();
            var_dump($modelInvoicePay->errors);
            echo "<br>";
        }
    }
}
