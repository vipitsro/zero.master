<?php

namespace app\controllers;

use Yii;
use app\models\Invoice;
use app\models\InvoiceXTag;
use app\models\InvoiceSearch;
use app\models\Supplier;
use app\models\SupplierForeign;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\MainModel;
use yii\web\UploadedFile;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends MainController
{

    /**
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearch();
        $searchModel->radio = 1;
        $searchModel->load(Yii::$app->request->get());
        $dataProvider = $searchModel->searchText(Yii::$app->request->get());
        $supplier_list_home = Yii::$app->cache->get("supplier_list");
        if (!$supplier_list_home){
            $query1 = Supplier::find()->joinWith("invoices")->groupBy("supplier.id")->orderBy("COUNT(invoice.id) DESC")->limit(10);
            $query2 = Supplier::find()->joinWith("invoices")->groupBy("supplier.id")->orderBy("COUNT(invoice.id) DESC")->offset(10);
            $suppliers_best = Supplier::find()->from([$query1])->orderBy("name")->all();
            $suppliers_other = Supplier::find()->from([$query2])->orderBy("name")->all();
            $supplier_list_home = [
                "suppliers_best" => $suppliers_best,
                "suppliers_other" => $suppliers_other,
            ];
            Yii::$app->cache->set("supplier_list", $supplier_list_home, 60);
        }
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'suppliers_best' => $supplier_list_home['suppliers_best'],
            'suppliers_other' => $supplier_list_home['suppliers_other'],
        ]);
    }

    /**
     * Displays a single Invoice model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * delete file from model.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteFile($id)
    {
        $model = $this->findModel($id);
        $x = $model->file;
        $model->file = "";
        if ($model->save()){
            unlink('uploads/'.$x);
        }
        
        $this->redirect(['update', 'id' => $id]);;
    } 	
	
    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Invoice();
        $post = Yii::$app->request->post();
		$get = Yii::$app->request->get();
		
        if ($model->load($post)) {
            $model->date_1 = date("Y-m-d H:i:s", strtotime($model->date_1));
            $model->date_2 = date("Y-m-d H:i:s", strtotime($model->date_2));
            $model->date_3 = date("Y-m-d H:i:s", strtotime($model->date_3));
            $cislo_firmy = \app\models\Settings::find()->where(["setting" => "COMPANY_ID"])->one()->value;
            $model->_typ_dokladu = $post['typ_dokladu'];
            $typ_dokladu = $post['typ_dokladu'];
            $aktualny_uctovny_rok = (isset($get["year"])) ? substr($get["year"],2) : date("y");
            $model->internal_number = 
                    $cislo_firmy.
                    $typ_dokladu.
                    $aktualny_uctovny_rok.
                    $this->actionSerialnumber($cislo_firmy, $typ_dokladu, $aktualny_uctovny_rok);
            
            
            $model->supplier = Supplier::find()->where(["id" => $model->id_supplier])->one()->name;
            
            $model->iban = str_replace(" ", "",$model->iban);
            $model->iban = wordwrap($model->iban , 4 , ' ' , true);
            $model->file = UploadedFile::getInstance($model, 'file');
			
			$filenameToSave = "";
            if ($model->file) {                
                $filenameToSave = $model->file->baseName.'_'.time();
                $model->file->saveAs('uploads/' .$filenameToSave.'.' . $model->file->extension);
                $model->file = $filenameToSave.'.' . $model->file->extension;
            }
            if ($filenameToSave == ""){
                $model->file = "";
            }
			
            if ($model->save()){
                    
                // CUSTOM PAYMENTS
                if (isset($post['InvoicePay']['id'])){
                    foreach ($post['InvoicePay']['id'] as $key => $id){
                        if ($key == count($post['InvoicePay']['id'])-1){
                            continue;
                        }

                        if ($id === ""){
                            $help = new \app\models\InvoicePay();
                        } else {
                            $help = \app\models\InvoicePay::find()->where(["id" => $id])->one();
                        }

                        $help->id_invoice = $model->id;
                        $help->date_payment = date("Y-m-d", strtotime($post['InvoicePay']['date_payment'][$key]));
                        $help->paid = 1;
                        $help->status = $post['InvoicePay']['status'][$key];
                        $help->price = str_replace(",", ".", $post['InvoicePay']['price'][$key]);
                        $help->save();
                    }
                }
                return $this->redirect(['index']);
            }
        } 
        
        $type = -1;
        if (in_array($model->_typ_dokladu, ["5", "7"])){
            $type = 0;
        } else if (in_array($model->_typ_dokladu, ["6", "8"])){
            $type = 1;
        } else if ($model->_typ_dokladu == 9){
            $type = 2;
        } 
        
        $suppliers_best = [];
        $suppliers_other = [];
        
        if ($type != -1){
            $query1 = Supplier::find()->joinWith("invoices")->groupBy("supplier.id")->orderBy("COUNT(invoice.id) DESC")->andWhere(["type" => $type])->limit(10);
            $query2 = Supplier::find()->joinWith("invoices")->groupBy("supplier.id")->orderBy("COUNT(invoice.id) DESC")->andWhere(["type" => $type])->offset(10);
            $suppliers_best = Supplier::find()->from([$query1])->orderBy("name")->all();
            $suppliers_other = Supplier::find()->from([$query2])->orderBy("name")->all();
        }
        
        return $this->render('create', [
            'model' => $model,
            'payments' => [],
            'suppliers_best' => $suppliers_best,
            'suppliers_other' => $suppliers_other,
        ]);
            
    }

    /**
     * Updates an existing Invoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->_typ_dokladu = substr($model->internal_number, 1,1);
        
        $type = -1;
        if (in_array($model->_typ_dokladu, ["5", "7"])){
            $type = 0;
        } else if (in_array($model->_typ_dokladu, ["6", "8"])){
            $type = 1;
        } else if ($model->_typ_dokladu == 9){
            $type = 2;
        } 
        
        $suppliers_best = [];
        $suppliers_other = [];
        
        if ($type != -1){
            $query1 = Supplier::find()->joinWith("invoices")->groupBy("supplier.id")->orderBy("COUNT(invoice.id) DESC")->andWhere(["type" => $type])->limit(10);
            $query2 = Supplier::find()->joinWith("invoices")->groupBy("supplier.id")->orderBy("COUNT(invoice.id) DESC")->andWhere(["type" => $type])->offset(10);
            $suppliers_best = Supplier::find()->from([$query1])->orderBy("name")->all();
            $suppliers_other = Supplier::find()->from([$query2])->orderBy("name")->all();
        }
        
        $payments = \app\models\InvoicePay::find()->where(["id_invoice" => $model->id])->all();
        foreach ($payments as $cp){
            $cp->date_payment = date("d.m.Y", strtotime($cp->date_payment));
        }
        
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $model->date_1 = date("Y-m-d H:i:s", strtotime($model->date_1));
            $model->date_2 = date("Y-m-d H:i:s", strtotime($model->date_2));
            $model->date_3 = date("Y-m-d H:i:s", strtotime($model->date_3));
            $model->supplier = Supplier::find()->where(["id" => $model->id_supplier])->one()->name;
            $model->iban = str_replace(" ", "",$model->iban);
            $model->iban = wordwrap($model->iban , 4 , ' ' , true);
            $model->file = UploadedFile::getInstance($model, 'file');
            
            $filenameToSave = "";
            if ($model->file) {                
                $filenameToSave = $model->file->baseName.'_'.time();
                $model->file->saveAs('uploads/' .$filenameToSave.'.' . $model->file->extension);
                $model->file = $filenameToSave.'.' . $model->file->extension;
            }
            if ($filenameToSave == ""){
                $model->file = $this->findModel($id)->file;
           
            }
            
            if ($model->save()){
                
                // NAJDI VSETKY PLATBY FAKTURY (NIE NA XML)
                $invoicePays_all = \app\models\InvoicePay::find()->where(["AND", ["=", "id_invoice", $model->id], ["<>", "status", 100]])->all();
//                $invoicePays_all = \app\models\InvoicePay::find()->where(["id_invoice" => $model->id])->all();
                // NAJDI VSETKY PLATBY KTORE MAJU OSTAT PRIRADENE K FAKTURE
                $invoicePays_new = [];
                if (isset($post['InvoicePay']['id'])){
                    foreach ($post['InvoicePay']['id'] as $key => $id){
                        if ($key == count($post['InvoicePay']['id'])-1){
                            continue;
                        }
                        
                        if ($id === ""){
                            $help = new \app\models\InvoicePay();
                        } else {
//                            $help = \app\models\InvoicePay::find()->where(["id" => $id])->one();
                            $help = \app\models\InvoicePay::find()->where(["AND", ["=", "id", $id], ["<>", "status", 100]])->one();
                        }
                        if ($help){
                            $help->id_invoice = $model->id;
                            $help->date_payment = date("Y-m-d", strtotime($post['InvoicePay']['date_payment'][$key]));
                            $help->paid = 1;
                            $help->status = $post['InvoicePay']['status'][$key];
                            $help->price = str_replace(",", ".", $post['InvoicePay']['price'][$key]);
                            if ($post['InvoicePay']['status'][$key] != 100){
                                $help->save();
                                $invoicePays_new[] = $help;
                            }
                        }
                    }
                }
                // NAJDI PLATBY KTORE SA TAM NENACHADZAJU
                $ids_deleted = array_diff(\yii\helpers\ArrayHelper::getColumn($invoicePays_all, "id"), \yii\helpers\ArrayHelper::getColumn($invoicePays_new, "id"));
                \app\models\InvoicePay::deleteAll(["IN", "id", $ids_deleted]);
                
                return $this->redirect('index');
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'payments' => $payments,
                    'suppliers_best' => $suppliers_best,
                    'suppliers_other' => $suppliers_other,
                ]);
            }
        } else {
            $model->_cislo_firmy = substr($model->internal_number,0,1);
            $model->_typ_dokladu = substr($model->internal_number,1,1);
            $model->_aktualny_uctovny_rok = substr($model->internal_number,2,2);
            return $this->render('update', [
                'model' => $model,
                'payments' => $payments,
                'suppliers_best' => $suppliers_best,
                'suppliers_other' => $suppliers_other,
            ]);
        }
    }

    /**
     * Deletes an existing Invoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if ($model->invoicePays == NULL){
            $model->delete();
        }

        return $this->redirect(['index']);
    }
    
    public function actionXls(){
        $get = Yii::$app->request->get();
//        echo "<pre>";
//        var_dump($get);
//        echo "</pre>";
        $model = new InvoiceSearch();
        $dataProvider = $model->searchText($get,-1);
        
        
        \moonland\phpexcel\Excel::export([
            'boldHeader' => true,
            'fileName' => 'doklady',
            'models' => $dataProvider->getModels(),
//            'properties' => ["autoSize" => true],
            'columns' => [
//                    'author.name:text:Author Name',
                'internal_number',
                'supplier',
                'iban',
                [
                    'header' => 'BIC',
                    'value' => function($model){
                        return $model->idSupplier->bic;
                    },
                ],
                'vs',
                [
                    "attribute" => "date_1",
                    "value" => function($model){
                        return date("d.m.Y", strtotime($model->date_1));
                    }
                ],
                [
                    "attribute" => "date_2",
                    "value" => function($model){
                        return date("d.m.Y", strtotime($model->date_2));
                    }
                ],					
                'price',
                'price_vat',
                [
                    'header' => 'Suma s DPH',
                    'value' => function($model){
                        return number_format($model->price + $model->price_vat, 2, ".", " ") . " " . \app\models\MainModel::getCurrencyName($model->currency);
                    }
                ],
                [
                    'header' => 'Neuhradené',
                    'value' => function($model){
                        $sum_paid = 0;
                        foreach ($model->invoicePays as $invoicePay){
                            if ($invoicePay->paid == 1) {
                                $sum_paid += $invoicePay->price;
                            }
                        }
                        return number_format($model->price + $model->price_vat - $sum_paid, 2, ".", " ") . " " . \app\models\MainModel::getCurrencyName($model->currency);
                    }
                ],
                [
                    'header' => 'Dátum uhradenia',
                    'value' => function($model){
                        $date_paid = "";
                        foreach ($model->invoicePays as $invoicePay){
                            $date_paid .= date("d.m.Y",strtotime($invoicePay->date_payment))."\n";
                        }
                        if (!empty($date_paid))
                            $date_paid = substr($date_paid, 0, -1);
                        return $date_paid;
                }
                ],
//                [
//                        'attribute' => 'id',
//                        'header' => 'ID',
//                        'format' => 'text',
//                        'value' => function($model) {
//                            return $model->id;
//                        },
//                ],
//                'like_it:text:Reader like this content',
//                'created_at:datetime',
//                [
//                        'attribute' => 'updated_at',
//                        'format' => 'date',
//                ],
            ],
//            'headers' => [
//                'created_at' => 'Date Created Content',
//                'created_at_2' => 'Date Created Content',
//            ],
        ]);
    }

    /**
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            $model->_cislo_firmy = substr($model->internal_number, 0,1);
            $model->_typ_dokladu = substr($model->internal_number, 1,2);
            $model->_aktualny_uctovny_rok = substr($model->internal_number, 3,2);
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionSerialnumber($cislo_firmy, $typ_dokladu, $aktualny_uctovny_rok){
        $model = Invoice::find()->select("internal_number")->where(
                "SUBSTRING(CAST(internal_number as CHAR),1,1) = :cislo_firmy AND ".
                "SUBSTRING(CAST(internal_number as CHAR),2,1) = :typ_dokladu AND ".
                "SUBSTRING(CAST(internal_number as CHAR),3,2) = :aktualny_uctovny_rok",
                ['cislo_firmy' => $cislo_firmy, 'typ_dokladu' => $typ_dokladu, 'aktualny_uctovny_rok' => $aktualny_uctovny_rok]
                )->orderBy("internal_number DESC")->one();
        if ($model != NULL){
            return substr($model->internal_number+1, 4);
        } else {
            return "001";
        }      
    }
    
    public function actionBankdata(){
        $post = Yii::$app->request->post();
        $id = $post["id"];
        $typ_dokladu = $post["typ_dokladu"];
        
        if (in_array($typ_dokladu, ["5", "7"])){
            $model = Supplier::find()->where(["id" => $id, "type" => 0])->one();
        } else if (in_array($typ_dokladu, ["6", "8"])){
            $model = Supplier::find()->where(["id" => $id, "type" => 1])->one();
        } else if (in_array($typ_dokladu, ["9"])){
            $model = Supplier::find()->where(["id" => $id, "type" => 2])->one();
        }
        
        $json['name'] = $model->name;
        $json['iban'] = $model->iban;
        $json['service'] = $model->typical_service;
        
        return \yii\helpers\Json::encode($json);
    }
    
    public function actionGetSuppliers(){
        $post = Yii::$app->request->post();
        
        $typ_dokladu = $post["typ_dokladu"];
        
        $type = -1;
        if (in_array($typ_dokladu, ["5", "7"])){
            $type = 0;
        } else if (in_array($typ_dokladu, ["6", "8"])){
            $type = 1;
        } else if (in_array($typ_dokladu, ["9"])){
            $type = 2;
        } 
        
        $query1 = Supplier::find()->joinWith("invoices")->groupBy("supplier.id")->orderBy("COUNT(invoice.id) DESC")->andWhere(["type" => $type])->limit(10);
        $query2 = Supplier::find()->joinWith("invoices")->groupBy("supplier.id")->orderBy("COUNT(invoice.id) DESC")->andWhere(["type" => $type])->offset(10);
        $suppliers_best = Supplier::find()->from([$query1])->orderBy("name")->all();
        $suppliers_other = Supplier::find()->from([$query2])->orderBy("name")->all();
        
        $json['suppliers_best'] = $suppliers_best;
        $json['suppliers_other'] = $suppliers_other;
        
        return \yii\helpers\Json::encode($json);
    }
    
    public function actionGetInvoiceData(){
        $post = Yii::$app->request->post();
        
        $model = Invoice::find()->with(["invoicePays", "idSupplier"])->where(["id" => $post["id"]])->asArray()->one();
        $model['_typ_dokladu'] = 1;
        $model['suma_s_dph'] = ($model['price']+$model['price_vat']) . " " . MainModel::getCurrencyName($model['currency']);
        $model['date_1'] = date("d.m.Y", strtotime($model['date_1']));
        $model['date_2'] = date("d.m.Y", strtotime($model['date_2']));
        $model['date_3'] = date("d.m.Y", strtotime($model['date_3']));
        $model['price'] = $model['price'] . " " . MainModel::getCurrencyName($model['currency']);
        $model['price_vat'] = $model['price_vat'] . " " . MainModel::getCurrencyName($model['currency']);
        
        foreach ($model['invoicePays'] as $key => $i){
            $model['invoicePays'][$key]["date_payment"] = date("d.m.Y" , strtotime($i["date_payment"]));
            $model['invoicePays'][$key]["price"] = 
                    $model['invoicePays'][$key]["price"] . " " . 
                    MainModel::getCurrencyName($model['currency']) . " | " . 
                    MainModel::getTypPlatby($model['invoicePays'][$key]["status"]);
        }
        
        return json_encode($model);
    }
    
    public function actionSaveInvoicePays(){
        $post = Yii::$app->request->post();
        $errors = [];
        $success = true;
        
        if (isset($post["InvoicePay"])){
            foreach($post["InvoicePay"] as $key => $value){
                if (
                    strlen($post["InvoicePay"][$key]['id_invoice']) === 0 ||
                    strlen($post["InvoicePay"][$key]['date_payment']) === 0 ||
                    strlen($post["InvoicePay"][$key]['price']) === 0 ||
                    strlen($post["InvoicePay"][$key]['status']) === 0
                ){
                    continue;
                }
                $invoicePay = new \app\models\InvoicePay();
                $invoicePay->id_invoice = $post["InvoicePay"][$key]['id_invoice'];
                $invoicePay->date_payment = date("Y-m-d", strtotime($post["InvoicePay"][$key]['date_payment']));
                $invoicePay->price = str_replace(",", ".", $post["InvoicePay"][$key]['price']);
                $invoicePay->status = $post["InvoicePay"][$key]['status'];
                $invoicePay->paid = 1;
                if (!$invoicePay->save()){
                    $errors[] = $invoicePay->errors;
                    $success = false;
                }
            }
        }
        
        return json_encode([
            "success" => $success,
            "errors" => $errors,
        ]);
    }
}
