<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "access".
 *
 * @property integer $id
 * @property string $name
 * @property string $rights
 *
 * @property Admin[] $admins
 */
class Access extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'access';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'rights'], 'required'],
            [['rights'], 'string'],
            [['name'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t("access",'Name'),
            'rights' => Yii::t("access",'Rights'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmins()
    {
        return $this->hasMany(Admin::className(), ['role' => 'id']);
    }
    
    public function getList(){
        return ArrayHelper::map(Access::find()->all(), 'id', 'name');
    }
    
    public function getRuleList(){
        return [
            Yii::t("main",'Site') => [
                'site_index' => Yii::t("main",'Index'),
                'site_login' => Yii::t("main",'Login'),
                'site_logout' => Yii::t("main",'Logout'),
                'site_err' => Yii::t("main",'Error'),
            ],
            
            Yii::t("main",'Roles') => [
                'access_index' => Yii::t("main",'Index'),
                'access_create' => Yii::t("main",'Create'),
                'access_update' => Yii::t("main",'Update'),            
                'access_view' => Yii::t("main",'View'),            
                'access_delete' => Yii::t("main",'Delete'), 
            ],
            
            Yii::t("main",'Users') => [
                'user_index' => Yii::t("main",'Index'),
                'user_create' => Yii::t("main",'Create'),
                'user_update;user_changepassword' => Yii::t("main",'Update'),            
                'user_view' => Yii::t("main",'View'),            
                'user_delete' => Yii::t("main",'Delete'), 
            ],
            
            Yii::t("main",'Invoices') => [
                'invoice_index' => Yii::t("main",'Index'),
                'invoice_create;invoice_serialnumber;supplier_bankdata;invoice_get-invoice-data;invoice_get-invoice-data;invoice_save-invoice-pays;invoice_get-suppliers;invoice_bankdata' => Yii::t("main",'Create'),
                'invoice_update;invoice_serialnumber;supplier_bankdata;invoice_get-invoice-data;invoice_get-invoice-data;invoice_save-invoice-pays;invoice_get-suppliers;invoice_bankdata' => Yii::t("main",'Update'),    
                'invoice-cart_add' => Yii::t("main",'Pridať na úhradu'),                 
                'invoice_delete' => Yii::t("main",'Delete'), 
            ],
            
            Yii::t("main",'To pay') => [
                'invoice-cart_index' => Yii::t("main",'Index'),           
                'invoice-cart_delete' => Yii::t("main",'Delete'), 
                'invoice-cart_change-price' => Yii::t("main",'Zmeniť sumu'), 
            ],
            
            Yii::t("main",'Batches') => [
                'invoice-batch_index;invoice-batch_one-batch' => Yii::t("main",'Index'),
                'invoice-cart_create' => Yii::t("main",'Create'),          
                'invoice-batch_uhradit' => Yii::t("main",'Uhradiť'),            
                'invoice-batch_delete' => Yii::t("main",'Delete'), 
                'invoice-batch_create-t-b-file' => Yii::t("main",'Create XML'), 
            ],
            
            Yii::t("main",'Suppliers') => [
                'supplier_index-other;supplier_index-home;supplier_index-foreign' => Yii::t("main",'Index'),
                'supplier_other-create;supplier_home-create;supplier_foreign-create;supplier_get-bic' => Yii::t("main",'Create'),
                'supplier_other-update;supplier_home_update;supplier_foreign-create;supplier_get-bic' => Yii::t("main",'Update'),                
                'supplier_delete' => Yii::t("main",'Delete'), 
            ],
            
            Yii::t("main",'Settings') => [
                'setting_index' => Yii::t("main",'Index'),
            ],
        ];   
    }
    
    public function getAdminRuleList($id){
        $model = Access::findOne(['id' => $id]);
        if ($model != NULL){
            return $model->rights;
        } else
            return NULL;
    }
    
    
    public function getAdminRuleListHTML($id){
        $model = Access::findOne(['id' => $id]);
        if ($model != NULL){
            $rights = Access::getAdminRuleList($id);
            $html = "<table class='prava'>";
            $rulelist = Access::getRuleList();
            foreach($rulelist as $key => $value){
                $html .= "<tr>";
                $html .= "<td style='text-align: left;'>".$key.": </td><td width=10></td><td>";
                foreach($value as $key2 => $value2){
                    if (strpos($rights,$key2)){
                        $html .= $value2.", ";
                    }   
                }
                $html .= "</td></tr>";
            }
            $html .= "</table>";
            return $html;
        } else
            return "";
    }
}
