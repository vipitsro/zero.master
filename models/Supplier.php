<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "supplier".
 *
 * @property integer $id
 * @property string $name
 * @property string $iban
 * @property string $bic
 * @property string $ks
 * @property string $address_street 
 * @property string $address_city 
 * @property string $address_country 
 * @property string $bank_name 
 * @property string $bank_street 
 * @property string $bank_city 
 * @property string $bank_country 
 * @property integer $type 
 * @property string $typical_service 
 *
 * @property Invoice[] $invoices
 */
class Supplier extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'supplier';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'iban'], 'required'],
            [['name'], 'string', 'max' => 512],
            [['ks'], 'string', 'max' => 10],
            [['iban'], 'string', 'max' => 34],
            [['iban'], 'validateIBAN', 'when' => function($model) {
                return ($model->type == 0 || $model->type == 2);
            }],
            [['bic'], 'string', 'max' => 16],
            [['typical_service'], 'string', 'max' => 255],
            [['address_street', 'address_city', 'address_country', 'bank_name', 'bank_street', 'bank_city', 'bank_country'], 'string', 'max' => 256],
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
            'id' => Yii::t("supplier", 'ID'),
            'name' => Yii::t("supplier", 'Name'),
            'iban' => Yii::t("supplier", 'IBAN'),
            'bic' => Yii::t("supplier", 'BIC/SWIFT'),
            'ks' => Yii::t("supplier", 'KS'),
            'address_street' => Yii::t('supplier', 'Address Street'),
            'address_city' => Yii::t('supplier', 'Address City'),
            'address_country' => Yii::t('supplier', 'Address Country'),
            'bank_name' => Yii::t('supplier', 'Bank Name'),
            'bank_street' => Yii::t('supplier', 'Bank Street'),
            'bank_city' => Yii::t('supplier', 'Bank City'),
            'bank_country' => Yii::t('supplier', 'Bank Country'),
            'type' => Yii::t('supplier', 'Type'),
            'typical_service' => Yii::t('supplier', 'Typical Service'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices() {
        return $this->hasMany(Invoice::className(), ['id_supplier' => 'id']);
    }

    public function getList() {
        return ArrayHelper::map(Supplier::find()->all(), 'id', 'name');
    }

    public function sortByName($a, $b) {
        return strcmp($a->name, $b->name);
    }

    public static function sort($array) {
        if (usort($array, ["app\models\Supplier", "sortByName"]))
            return $array;
        else
            return [];
    }

}
