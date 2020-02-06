<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "invoice_pay".
 *
 * @property integer $id
 * @property integer $id_invoice
 * @property double $price
 * @property string $status
 * @property string $date_create
 * @property string $date_udpate
 * @property string $date_payment
 */
class InvoicePay extends \yii\db\ActiveRecord {

    public $sum_all;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'invoice_pay';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id_invoice', 'price'], 'required'],
            [['id_invoice'], 'integer'],
            [['price'], 'number'],
            [['status'], 'number'],
            [['date_create', 'date_update', 'date_payment'], 'safe']
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => 'date_update',
                'createdAtAttribute' => 'date_create',
                'value' => function() {
                    return date("Y-m-d h:i:s");
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('invoice_pay', 'ID'),
            'id_invoice' => Yii::t('invoice_pay', 'Id Invoice'),
            'price' => Yii::t('invoice_pay', 'Price'),
            'status' => Yii::t('invoice_pay', 'Status'),
            'date_payment' => Yii::t('invoice_pay', 'Date of Payment'),
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
    public function getInvoiceBatches() {
        return $this->hasMany(InvoiceBatch::className(), ['id_invoice_pay' => 'id']);
    }

}
