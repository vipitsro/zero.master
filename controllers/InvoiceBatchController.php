<?php

namespace app\controllers;

use Yii;
use app\models\Invoice;
use app\models\InvoiceBatch;
use app\models\InvoicePay;
use app\models\InvoiceBatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InvoiceBatchController implements the CRUD actions for InvoiceBatch model.
 */
class InvoiceBatchController extends MainController
{

    /**
     * Lists all InvoiceBatch models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceBatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionOneBatch($batch){
        $query = InvoiceBatch::find()->with(["idInvoice", "idInvoice.invoicePays"])->where(["batch" => $batch]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        
        return $this->render("one_batch", [
            "dataProvider" => $dataProvider
        ]);
    }
    
    public function actionUhradit(){
        $post = Yii::$app->request->post();
        $errors = [];
        $success = true;
        
        $models = InvoiceBatch::find()->where(["batch" => $post['batch']])->with(["idInvoicePay"])->all();
        foreach($models as $model){
            $model->paid = 1;
            $model->idInvoicePay->paid = 1;
            $model->idInvoicePay->date_payment = $model->date_1;
            if (!$model->save() || !$model->idInvoicePay->save())
                $success = false;
            if ($model->errors)
                $errors[] = $model->errors;
        }
        
        return json_encode([
            'success' => $success,
            'errors' => $errors,
        ]);
    }
    
    public function actionCreateTBFile($batch_id, $date){
        $date = date("Y-m-d", strtotime($date));
        
        $models = InvoiceBatch::find()->where(["batch" => $batch_id])->all();
        foreach ($models as $model){
            $model->date_1 = $date;
            $model->save();
        }
        
        $company_name = \app\models\Settings::find()->where(["setting" => "COMPANY_NAME"])->one()->value;
        $filename = $company_name."_".date("Ymd")."_".sprintf("%04d",$batch_id).".xml";
        header ("Content-Type: application/xml");
        header ("Content-disposition: attachment; filename=\"".$filename."\"");
        $xml = InvoiceBatch::createXML($batch_id, $date);
        $xml->asXml("xml_sepa/files/".time()."_".$filename);
        echo $xml->asXML();
		exit;
    }

    /**
     * Deletes an existing InvoiceBatch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($batch)
    {
        $invoiceBatchModels = InvoiceBatch::find()->where(["batch" => (int)$batch, "paid" => 0])->all();
        foreach ($invoiceBatchModels as $invoiceBatchModel){
            $invoiceBatchModel->delete();
            $invoiceBatchModel->idInvoicePay->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the InvoiceBatch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InvoiceBatch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InvoiceBatch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
