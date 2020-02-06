<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice_x_tag".
 *
 * @property integer $id
 * @property integer $id_invoice
 * @property integer $id_tag
 */
class InvoiceXTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice_x_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_invoice', 'id_tag'], 'required'],
            [['id_invoice', 'id_tag'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_invoice' => 'Id Invoice',
            'id_tag' => 'Id Tag',
        ];
    }
}
