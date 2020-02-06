<?php

namespace app\controllers;

use Yii;
use app\models\Invoice;
use app\models\InvoiceBatch;
use app\models\InvoiceCart;
use app\models\InvoiceCartSearch;
use app\models\InvoicePay;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InvoiceCartController implements the CRUD actions for InvoiceCart model.
 */
class InvoiceCartController extends MainController
{
    /**
     * Lists all InvoiceCart models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceCartSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing InvoiceCart model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->idInvoice->status = 0;
        $model->idInvoice->save();
        $model->delete();

        return $this->redirect(['index']);
    }
    
    public function actionAdd(){
        $post = Yii::$app->request->post();
               
        $invoiceCart = new InvoiceCart();
        $invoice = Invoice::find()->where(["internal_number" => $post['internal_number']])->one(); 
        
        // CHECK IF IN INVOICE CART OR ALREADY PAID
        $m1 = InvoiceCart::find()->where(["id_invoice" => $invoice->id])->one();
        $m2 = InvoicePay::find()->
                select("SUM(price) AS sum_all")->
                where(["id_invoice" => $invoice->id])->
                one();
        
        if ($m1 || ($m2 && (double)$m2->sum_all === (double)($invoice->price + $invoice->price_vat))){
            return json_encode([
                'success' => false,
                'errors' => 'Already in InvoiceCart or Paid'
            ]);
        }
        
        // SUM PAID
        $paid = 0;
        $invoicePays = $invoice->invoicePays;
        foreach ($invoicePays as $invoicePay){
            $paid += $invoicePay->price;
        }
        
        $invoiceCart->id_invoice = $invoice->id;
        $invoiceCart->price = $invoice->price + $invoice->price_vat - $paid;
        
        $save = $invoiceCart->save(false);

        return json_encode([
            "success" => $save,
            "errors" => $invoiceCart->errors
        ]);
    }
    
    public function createBatch(){
        $invoiceCartModels = InvoiceCart::find()->with(["idInvoice"])->all();
        
        $lastInvoiceBatchModel = \app\models\InvoiceBatch::find()->orderBy("batch DESC, date_create DESC")->one();
        $lastGroup = isset($lastInvoiceBatchModel->batch) ? $lastInvoiceBatchModel->batch : 0;
        
        $error = false;
        
        foreach($invoiceCartModels as $invoiceCartModel){
            $invoiceBatchModel = new InvoiceBatch();
            $invoiceBatchModel->id_invoice = $invoiceCartModel->id_invoice;
            $invoiceBatchModel->price = $invoiceCartModel->price;
            $invoiceBatchModel->paid = 0;
            $invoiceBatchModel->date_1 = date("Y-m-d");
            $invoiceBatchModel->batch = $lastGroup+1;
            
            $invoicePayModel = new InvoicePay();
            $invoicePayModel->id_invoice = $invoiceCartModel->id_invoice;
            $invoicePayModel->paid = 0;
            $invoicePayModel->price = $invoiceCartModel->price;
            $invoicePayModel->date_payment = date("Y-m-d");
            $invoicePayModel->status = 100;
            
            $save2 = $invoicePayModel->save();
            
            $invoiceBatchModel->id_invoice_pay = $invoicePayModel->id;
            
            $save1 = $invoiceBatchModel->save();
            
            if($save1 && $save2){
                $invoiceCartModel->delete();
            } else {
                $error = true;
            }
        }
        
        $result["error"] = $error;
        $result["batch_id"] = $lastGroup+1;
        
        return $result;
    }
    
    public function actionCreateBatch(){
        $result = $this->createBatch();
        if ($result['error']){
            return $this->redirect(["index"]);
        } else {
            return $this->redirect(["invoice-batch/one-batch", "batch" => $result['batch_id']]);
        }
    }
    
    public function actionChangePrice(){
        $post = \Yii::$app->request->post();
        
        if (!isset($post['id'])){
            return json_encode([
                "success" => false,
                "errors" => "Nevybrané žiadne položky"
            ]);
        }
        
        $invoiceCartModel = InvoiceCart::find()->where(["id" => $post['id']])->one();
        if ($invoiceCartModel){
            $invoiceCartModel->price = str_replace(",", ".", $post['sum']);
            $success = $invoiceCartModel->save();
            $errors = $invoiceCartModel->errors;
        } else {
            $success = false;
            $errors = ["ID nenajdené"];
        }
        
        return json_encode([
            "success" => $success,
            "errors" => $errors
        ]);
    }

    /**
     * Finds the InvoiceCart model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InvoiceCart the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InvoiceCart::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
