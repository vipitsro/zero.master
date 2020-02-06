<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InvoiceBatch;

/**
 * InvoiceBatchSearch represents the model behind the search form about `app\models\InvoiceBatch`.
 */
class InvoiceBatchSearch extends InvoiceBatch
{
    
    public $search_text = "";
    public $_paid = "";
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_invoice', 'batch', 'paid'], 'integer'],
            [['date_create', 'date_update', 'search_text', '_paid'], 'safe'],
        ];
    }
    
    public function attributeLabels() {
        return array_merge(parent::attributeLabels(),[
            'search_text' => 'Text',
            'paid' => "Stav platby",
            'id' => 'ID',
            'internal_number' => 'Interné číslo',
            'date_1_from' => 'Dátum prijatia (od)',
            'date_1_to' => 'Dátum prijatia (do)',
            'date_2_from' => 'Dátum dodania (od)',
            'date_2_to' => 'Dátum dodania (do)',
            'date_3_from' => 'Dátum splatenia (od)',
            'date_3_to' => 'Dátum splatenia (do)',
            'date_4_from' => 'Dátum úhrady (od)',
            'date_4_to' => 'Dátum úhrady (do)',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = InvoiceBatch::find()->
                select("invoice_batch.*, count(DISTINCT invoice.id) as count_invoices, SUM(invoice_batch.price) AS sum_all, SUM(CASE WHEN invoice_batch.paid = 1 THEN invoice_batch.price ELSE 0 END) AS sum_paid")->
                join("LEFT JOIN", "invoice", "invoice_batch.id_invoice = invoice.id")->
                join("LEFT JOIN", "supplier", "invoice.id_supplier = supplier.id")->
                groupBy("batch");
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder'=>['date_create' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
//             $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'invoice_batch.id' => $this->id,
            'invoice_batch.id_invoice' => $this->id_invoice,
            'invoice_batch.batch' => $this->batch,
            'invoice_batch.paid' => $this->paid,
            'invoice_batch.date_create' => $this->date_create,
            'invoice_batch.date_update' => $this->date_update,
        ]);
        
        $query->andFilterWhere(
            ["OR",
                ['like', 'invoice.internal_number', $this->search_text],
                ['like', 'supplier.name', $this->search_text],
            ]
        );
        
        if ($this->_paid === "1"){
            $query->andHaving("SUM(DISTINCTROW invoice_pay.price) = ROUND(SUM(DISTINCTROW invoice.price + invoice.price_vat),2)");
        } else if ($this->_paid === "2"){
            $query->andHaving(
                    "SUM(DISTINCTROW invoice_pay.price) <> ROUND(SUM(DISTINCTROW invoice.price + invoice.price_vat,2)" .
                    " OR " .
                    "SUM(CASE WHEN invoice_pay.price IS NULL THEN 0 ELSE 1 END) = 0");
        } 

        return $dataProvider;
    }
}
