<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $id
 * @property integer $id_supplier
 * @property integer $internal_number
 * @property string $supplier
 * @property string $service
 * @property string $vs
 * @property string $date_1
 * @property string $date_2
 * @property string $date_3
 * @property string $supplier
 * @property double $price
 * @property double $price_vat
 * @property integer $currency
 * @property string $iban
 * @property string $status
 * @property string $created_at 
 * @property string $updated_at 
 *
 * @property Supplier $idSupplier
 */
class Invoice extends \yii\db\ActiveRecord {

    const EUR = 0;
    const USD = 1;
    const CZK = 2;
    const HUF = 3;

    private $_idSupplierName;
    public $_cislo_firmy;
    public $_typ_dokladu;
    public $_aktualny_uctovny_rok;
    public $suma_s_dph;

    public function init() {
        $this->_aktualny_uctovny_rok = date("y");
    }

    //public $idSupplierName;
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'invoice';
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => function() {
                    return date("Y-m-d H:i:s");
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['internal_number', 'iban', 'date_1', 'date_2', 'date_3', 'price', 'price_vat', 'currency'], 'required'],
            //[['_typ_dokladu', '_cislo_firmy'], 'required'],
            [['id_supplier',  'currency'], 'integer'],
            [['date_1', 'date_2', 'date_3', 'created_at', 'updated_at'], 'safe'],
            [['price', 'price_vat'], 'number'],
            [['supplier', 'service', 'file'], 'string', 'max' => 255],
            [['vs'], 'string', 'max' => 10],
            [['iban'], 'string', 'max' => 34],
            [['iban'], 'validateIBAN', 'when' => function($model) {
                return (in_array(substr($model->internal_number, 1,1), [5,7,9]));
            }],
            [['internal_number'], 'string', 'max' => 20],
        ];
    }

    public function validateIBAN($attribute, $params) {
        if (!MainModel::validateIBAN($this->$attribute)) {
            $this->addError($attribute, 'Nevalidný IBAN');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'internal_number' => Yii::t("invoice", 'Internal number'),
            'supplier' => Yii::t("invoice", "Supplier"),
            'service' => Yii::t("invoice", "Typical Service"),
            'vs' => 'Variabilný symbol',
            'date_1' => Yii::t("invoice", 'Date 1'),
            'date_2' => Yii::t("invoice", 'Date 2'),
            'date_3' => Yii::t("invoice", 'Date 3'),
            'id_supplier' => Yii::t("invoice", 'Supplier'),
            'price' => Yii::t("invoice", 'Price'),
            'price_vat' => Yii::t("invoice", 'VAT'),
            'currency' => Yii::t("invoice", 'Currency'),
            'account_prefix' => Yii::t("invoice", 'Account prefix'),
            'account_number' => Yii::t("invoice", 'Account number'),
            'bank_code' => Yii::t("invoice", 'Bank code'),
            'iban' => 'IBAN',
            'swift' => 'SWIFT',
            'ks' => 'KS',
            'debet_info' => Yii::t("invoice", 'Debet information'),
            'status' => Yii::t("invoice", 'Status'),
            'created_at' => Yii::t("invoice", 'Date Create'),
            'updated_at' => Yii::t("invoice", 'Date Update'),
			'file' => Yii::t("invoice", 'Súbor'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSupplier() {
        return $this->hasOne(Supplier::className(), ['id' => 'id_supplier']);
    }
    

    public function getTags() {
        return $this->hasMany(InvoiceXTag::className(), ['id_invoice' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceBatches() {
        return $this->hasMany(InvoiceBatch::className(), ['id_invoice' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceCarts() {
        return $this->hasMany(InvoiceCart::className(), ['id_invoice' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoicePays() {
        return $this->hasMany(InvoicePay::className(), ['id_invoice' => 'id']);
    }

    public function getIdSupplierName() {
        $model = $this->idSupplier;
        if ($model != NULL)
            $this->_idSupplierName = $model->name;
        return $this->_idSupplierName;
    }

    public function setIdSupplierName($name) {
        $this->_idSupplierName = $name;
    }

    public function getCurrencyList() {
        return [
            SELF::EUR => "EUR",
            SELF::USD => "USD",
            SELF::CZK => "CZK",
            SELF::HUF => "HUF",
        ];
    }

    public function getCurrencyId($name) {
        $array = [
            SELF::EUR => "EUR",
            SELF::USD => "USD",
            SELF::CZK => "CZK",
            SELF::HUF => "HUF",
        ];

        foreach ($array as $key => $value) {
            if ($value === $name)
                return $key;
        }

        return null;
    }

    public function getCislafirmy() {
        return [
            "1" => "Linwe/KRAFT",
            "2" => "Printline",
            "3" => "KraftGROUP",
            "4" => "Lwk, s.r.o.",
            "9" => "P78, s.r.o."
        ];
    }

    public function getTypydokladu() {
        /*
          return [
          "51" => "domáce faktúry vystavené (ostré)",
          "52" => "zahraničné faktúry vystavené (ostré)",
          "53" => "domáce faktúry vystavene (zálohové)",
          "54" => "zahraničné faktúry vystavené (zálohové)",
          "55" => "domáce vystavené dobropisy",
          "56" => "zahraničné vystavené dobropisy",
          "91" => "domáce faktúry došlé (ostré)",
          "92" => "zahraničné faktúry došlé (ostré)",
          "93" => "domáce faktúry došlé (zálohové)",
          "94" => "zahraničné faktúry došlé (zálohové)",
          "95" => "domáce došlé dobropisy",
          "96" => "zahraničné došlé dobropisy",
          "97" => "interné doklady",
          "99" => "všetky ostatné platby",
          ];
         */
        return [
            //"1" => "Faktúry vystavené (ostré)",
            //"2" => "Faktúry vystavené (zálohové)",
            //"3" => "Domáce vystavené dobropisy",
            //"4" => "Zahraničné vystavené dobropisy",
            "5" => "Domáce faktúry došlé (ostré)",
            "7" => "Domáce faktúry došlé (zálohové)",
            "9" => "Interné doklady, všetky ostatné platby",
            "6" => "Zahraničné faktúry došlé (ostré)",
            "8" => "Zahraničné faktúry došlé (zálohové)",
        ];
    }

    public static function parseFDSK($csv) {
        Invoice::deleteAll();

        $lines = explode(PHP_EOL, $csv);
        $invoices = array();
        foreach ($lines as $line) {
            $invoices[] = str_getcsv($line, ";");
        }

        $suppliers = Supplier::find()->all();

        foreach ($invoices as $invoice) {
            if ($invoice[0] == null)
                continue;
            // TODO id_supplier
            // $invoice[5]
            $_supplier = 0;
            foreach ($suppliers as $supplier) {
                if (trim($supplier->name) == trim($invoice[5]))
                    $_supplier = $supplier;
            }

            $dph = 20;
            $price_bez_dph = str_replace(" ", "", str_replace(",", ".", $invoice[6]));
            $price_dph = str_replace(" ", "", str_replace(",", ".", $invoice[7]));

            $model = new Invoice();
            $model->internal_number = $invoice[0];
            $model->vs = $invoice[1];
            $model->date_1 = date("Y-m-d", strtotime($invoice[2]));
            $model->date_2 = date("Y-m-d", strtotime($invoice[3]));
            $model->date_3 = strtotime($invoice[4]);
            $model->date_3 += (rand(0, 5) % 5 != 0) ? 60 * 60 * 24 * 365 * 2 : 0;
            $model->date_3 = date("Y-m-d", $model->date_3);
            $model->id_supplier = !empty($_supplier) ? $_supplier->id : 0;
            $model->supplier = $invoice[5];
            $model->price = str_replace(" ", "", str_replace(",", ".", $invoice[6]));
            $model->price_vat = str_replace(" ", "", str_replace(",", ".", $invoice[7]));
            $model->currency = Invoice::getCurrencyId($invoice[9]);
//            $model->date_4 = date("Y-m-d", strtotime($invoice[10]));
            $model->account_prefix = $invoice[12];
            $model->account_number = $invoice[13];
            $model->bank_code = $invoice[14];
            $model->ks = $invoice[15];
            $model->debet_info = $invoice[16];

            $model->save();
            var_dump($model->errors);
        }
    }

}
