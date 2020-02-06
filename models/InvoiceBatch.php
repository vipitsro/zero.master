<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "invoice_batch".
 *
 * @property integer $id
 * @property integer $id_invoice
 * @property integer $id_invoice_pay
 * @property integer $batch
 * @property double $price
 * @property integer $paid
 * @property string $date_1
 * @property string $date_create
 * @property string $date_update
 *
 * @property Invoice $idInvoice
 */
class InvoiceBatch extends \yii\db\ActiveRecord {

    public $count_invoices;
    public $sum_paid;
    public $sum_all;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'invoice_batch';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['id_invoice', 'batch', 'paid', 'account_number', 'account_prefix_supplier', 'account_number_supplier', 'bank_code_supplier', 'currency', 'price', 'date_1', 'ks', 'vs', 'ss', 'kredit_info', 'kredit_info_2', 'debet_vs', 'debet_ss', 'debet_info', 'avizo', 'date_create', 'date_update'], 'required'],
            [['id_invoice', 'id_invoice_pay', 'batch', 'paid'], 'integer'],
            [['price'], 'double'],
            [['date_create', 'date_update', 'date_1'], 'safe'],
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
                'value' => function() {
                    return date("Y-m-d H:i:s");
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('invoice_batch', 'ID'),
            'id_invoice' => Yii::t('invoice_batch', 'Id Invoice'),
            'batch' => Yii::t('invoice_batch', 'Batch'),
            'paid' => Yii::t('invoice_batch', 'Paid'),
            'paid' => Yii::t('invoice_batch', 'Price'),
            'date_1' => Yii::t('invoice_batch', 'Date 1'),
            'date_create' => Yii::t('invoice_batch', 'Date create'),
            'date_update' => Yii::t('invoice_batch', 'Date update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdInvoice() {
        return $this->hasOne(Invoice::className(), ['id' => 'id_invoice']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdInvoicePay() {
        return $this->hasOne(InvoicePay::className(), ['id' => 'id_invoice_pay']);
    }

    public static function sxml_append($to, $from) {
        $toDom = dom_import_simplexml($to);
        $fromDom = dom_import_simplexml($from);
        $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
    }

    public static function createXML($batch_id, $date) {
        /* @var $models InvoiceBatch[] */
        $models = InvoiceBatch::find()->where(["batch" => $batch_id])->all();

        $xmlPath = __DIR__ . "/../web/xml_sepa/template.xml";
        $xml = simplexml_load_file($xmlPath);

//        var_dump($xmlPath);
//        var_dump($xml);
//        $date = date("Y-m-d");
        $time = date("H:i:s");
        $sum = 0;
        $company_name = Settings::find()->where(["setting" => "COMPANY_NAME"])->one()->value;
        foreach ($models as $model) {
            $sum += $model->price;
        }

        $xml->CstmrCdtTrfInitn->GrpHdr->MsgId = $batch_id;
        $xml->CstmrCdtTrfInitn->GrpHdr->CreDtTm = $date . "T" . $time . ".000";                                                                       // CREATION DATETIME
        $xml->CstmrCdtTrfInitn->GrpHdr->NbOfTxs = "1";                                                                                          // NUMBER OF TRANSACTIONS
        $xml->CstmrCdtTrfInitn->GrpHdr->CtrlSum = sprintf("%0.2f", $sum);                                                                        // CONTROL SUM
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Nm = Diacritics::remove_accents($company_name);                                               // INITIATING PARTY
        $xml->CstmrCdtTrfInitn->PmtInf->PmtInfId = '"PMTID-"' . $date . "T" . $time . ".000";                                                           // PAYMENT INFORMATION IDENTIFICATION
        $xml->CstmrCdtTrfInitn->PmtInf->PmtMtd = "TRF";                                                                                         // PAYMENT METHOD
        $xml->CstmrCdtTrfInitn->PmtInf->ReqdExctnDt = $date;                                                                                    // REQUESTED EXECUTION DATE !!!
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Nm = Diacritics::remove_accents($company_name);    // NAME !!!
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->IBAN = Settings::find()->where(["setting" => "ACCOUNT_NUMBER"])->one()->value;            // ACCOUNT NUMBER!!!



        foreach ($models as $key => $model) {
            //COPY NODE
            if ($key != 0) {
                InvoiceBatch::sxml_append($xml->CstmrCdtTrfInitn->PmtInf, $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[0]);
            }
            
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$key]->PmtId->EndToEndId = "/VS" . $model->idInvoice->vs . "/SS" . $model->idInvoice->internal_number . "/KS" . $model->idInvoice->idSupplier->ks;    // END TO END IDENTIFICATION
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$key]->Amt->InstdAmt = $model->price;                                                                   // INSTRUCTED AMOUNT !!!
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$key]->Amt->InstdAmt->addAttribute("Ccy", MainModel::getCurrencyName($model->idInvoice->currency));     // INSTRUCTED AMOUNT !!!
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$key]->ChrgBr = "SLEV";           // 

            $iban = str_replace(" ", "", $model->idInvoice->iban);
            $bic = isset($model->idInvoice->idSupplier->bic) ? $model->idInvoice->idSupplier->bic : "";

            if ($bic === NULL || $bic === "") {
                $bic = MainModel::getBICFromIBAN($iban);
            }
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$key]->CdtrAgt->FinInstnId->BIC = $bic;
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$key]->Cdtr->Nm = Diacritics::alphanumeric($model->idInvoice->idSupplier->name);                                                                   //Diacritics::remove_accents($model->idInvoice->idSupplier->name);                                                  // NAME 
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$key]->CdtrAcct->Id->IBAN = $iban;                                                      // IBAN !!!
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$key]->RmtInf->Ustrd = "";                                                              // UNSTRUCTED
        }
        return $xml;
    }

}
