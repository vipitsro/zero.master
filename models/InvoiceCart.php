<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice_cart".
 *
 * @property integer $id
 * @property integer $id_invoice
 *
 * @property Invoice $idInvoice
 */
class InvoiceCart extends \yii\db\ActiveRecord
{
    public $suma_s_dph;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice_cart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['id_invoice', 'account_number', 'account_prefix_supplier', 'account_number_supplier', 'bank_code_supplier', 'currency', 'price', 'date_1', 'ks', 'vs', 'ss', 'kredit_info', 'kredit_info_2', 'debet_vs', 'debet_ss', 'debet_info', 'avizo'], 'required'],
            [['id_invoice'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('invoice_cart', 'ID'),
            'id_invoice' => Yii::t('invoice_cart', 'Invoice'),
            'price' => Yii::t('invoice_cart', 'price'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'id_invoice']);
    }
}
