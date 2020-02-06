<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Invoice;

/**
 * InvoiceSearch represents the model behind the search form about `app\models\Invoice`.
 */
class InvoiceSearch extends Invoice {

    public $search_text = "";
    public $paid = "0";  // 0 - vsetky, 1 - na úhrade, 2 - zaplatene, 3 - ostatné 
    public $supp;
    public $year;
    public $radio;
    public $tags;
    public $date_1_from, $date_1_to;
    public $date_2_from, $date_2_to;
    public $date_3_from, $date_3_to;
    public $date_4_from, $date_4_to;
    public $price_vat;

    public function init() {
        parent::init();
        $this->year = date("Y");
    }
    
    /**
     * @inheritdoc
     */
    public function rules() {
        return array_merge(parent::rules(),[
            [['radio'], 'integer'],
            [['date_1_from', 'date_2_from', 'date_3_from', 'date_4_from'], 'string'],
            [['date_1_to', 'date_2_to', 'date_3_to', 'date_4_to'], 'string'],
            [['paid', 'year'], 'number'],
            [['search_text', 'supp'], 'string'],
            [['tags'], 'checkIsArray'],
        ]);
    }

    public function checkIsArray() {
        if (!is_array($this->tags)) {
            $this->addError('Tags', 'Tags is not array!');
        }
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(),[
            'search_text' => 'Text',
            'paid' => 'Stav platby',
            'tags' => 'Tagy',
            'id' => 'ID',
            'internal_number' => 'Interné číslo',
            'vs' => 'VS',
            'date_1_from' => 'Dátum prijatia (od)',
            'date_1_to' => 'Dátum prijatia (do)',
            'date_2_from' => 'Dátum dodania (od)',
            'date_2_to' => 'Dátum dodania (do)',
            'date_3_from' => 'Dátum splatenia (od)',
            'date_3_to' => 'Dátum splatenia (do)',
            'date_4_from' => 'Dátum úhrady (od)',
            'date_4_to' => 'Dátum úhrady (do)',
            'supp' => 'Dodávateľ',
            'year' => 'Rok',
        ]);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchText($params, $pageSize = 50) {
        
        
        $query = Invoice::find()->
                with(["invoiceBatches", 'invoiceCarts']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => $pageSize],
            'sort' => ['defaultOrder'=>['created_at' => SORT_DESC]]
//            'sort' => ['defaultOrder'=>['SUBSTRING(internal_number,1,3)' => SORT_DESC]] // NEFUNGUJE
        ]);

        $dataProvider->sort->attributes['suma_s_dph'] = [
            'asc' => ['suma_s_dph' => SORT_ASC],
            'desc' => ['suma_s_dph' => SORT_DESC],
        ];
        
        $query->joinWith(['idSupplier', 'invoicePays']);
        $query->groupBy("invoice.id");
        $this->load($params);

//        if (!$this->validate()) {
//            return $dataProvider;
//        }
        if ($this->supp == -1)
            $this->supp = null;

        $date_1_from = $this->convertStrToDate("d.m.y", "Y-m-d", $this->date_1_from, "down");
        $date_1_to = $this->convertStrToDate("d.m.y", "Y-m-d", $this->date_1_to, "up");
        $date_2_from = $this->convertStrToDate("d.m.y", "Y-m-d", $this->date_2_from, "down");
        $date_2_to = $this->convertStrToDate("d.m.y", "Y-m-d", $this->date_2_to, "up");
        $date_3_from = $this->convertStrToDate("d.m.y", "Y-m-d", $this->date_3_from, "down");
        $date_3_to = $this->convertStrToDate("d.m.y", "Y-m-d", $this->date_3_to, "up");
        $date_4_from = $this->convertStrToDate("d.m.y", "Y-m-d", $this->date_4_from, "down");
        $date_4_to = $this->convertStrToDate("d.m.y", "Y-m-d", $this->date_4_to, "up");

        $query->andWhere([
            'and',
            ['or',
                ['like', 'invoice.internal_number', $this->search_text],
                ['like', 'invoice.supplier', $this->search_text],
                ['like', 'supplier.name', $this->search_text],
                ['like', 'DATE_FORMAT(invoice.date_1, "%d.%m.%y")', $this->search_text],
                ['like', 'DATE_FORMAT(invoice.date_2, "%d.%m.%y")', $this->search_text],
                ['like', 'DATE_FORMAT(invoice.date_3, "%d.%m.%y")', $this->search_text],
                ['like', 'invoice.price', $this->search_text],
                ['like', 'invoice.price_vat', $this->search_text],
                ['like', 'invoice.currency', $this->search_text],
                ['like', 'invoice.vs', $this->search_text],
                ['like', 'invoice.iban', $this->search_text],
            ],
            [">=", 'invoice.date_1', $date_1_from],
            ['<=', 'invoice.date_1', $date_1_to],
            [">=", 'invoice.date_2', $date_2_from],
            ['<=', 'invoice.date_2', $date_2_to],
            [">=", 'invoice.date_3', $date_3_from],
            ['<=', 'invoice.date_3', $date_3_to],
            ['like', 'SUBSTRING(invoice.internal_number,3,2)', substr($this->year,2)],
        ]);
        $query->andFilterWhere(['=', 'invoice.id_supplier', $this->supp]);
        
        // NA UHRADE
        if ($this->paid === "1"){
            $query->andHaving("SUM(invoice_pay.paid) <> COUNT(invoice_pay.paid)");
        // ZAPLATENE
        } else if ($this->paid === "2"){
            $query->andHaving("ROUND(SUM(invoice_pay.price),2) = ROUND(invoice.price + invoice.price_vat,2) AND SUM(invoice_pay.paid) = COUNT(invoice_pay.paid)");
        // PO SPLATNOSTI
        } else if ($this->paid === "3"){
            $query->andWhere(["<", "invoice.date_3", date("Y-m-d")]);
            $query->andHaving("(ROUND(SUM(invoice_pay.price),2) IS NULL AND invoice.price <> 0) OR ROUND(SUM(invoice_pay.price),2) <> ROUND(invoice.price + invoice.price_vat,2)");
        // NEUHRADENE
        } else if ($this->paid === "4"){
            $query->andHaving("(ROUND(SUM(invoice_pay.price),2) IS NULL AND invoice.price <> 0) OR ROUND(SUM(invoice_pay.price),2) <> ROUND(invoice.price + invoice.price_vat,2) OR SUM(invoice_pay.paid) <> COUNT(invoice_pay.paid)");
        }

        return $dataProvider;
    }

    public function search($params) {
        $query = Invoice::find()->select("*, price+price_vat as suma_s_dph");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['idSupplier', 'tags']);

        $this->load($params);
        $get = Yii::$app->request->get();

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['idSupplier']);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'internal_number' => $this->internal_number,
            'date_1' => $this->date_1,
            'date_2' => $this->date_2,
            'date_3' => $this->date_3,
            'date_4' => $this->date_4,
            'price' => $this->price,
            'price_vat' => $this->price_vat,
            'currency' => $this->currency,
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
        ]);

        $query->andFilterWhere(['like', 'vs', $this->vs])
                ->andFilterWhere(['like', 'iban', $this->iban])
                ->andFilterWhere(['like', 'swift', $this->swift])
                ->andFilterWhere(['like', 'ks', $this->ks])
                ->andFilterWhere(['like', 'info', $this->info]);

        $dataProvider->sort->attributes['suma_s_dph'] = [
            'asc' => ['suma_s_dph' => SORT_ASC],
            'desc' => ['suma_s_dph' => SORT_DESC],
        ];

        return $dataProvider;
    }

    public function convertStrToDate($oldFormat, $newFormat, $date, $upDown) {
//	    return $date;
        if (empty($date)) {
            if ($upDown == "up") {
                return date("Y-m-d", strtotime("2038-01-01"));
            } else if ($upDown == "down") {
                return date("Y-m-d", strtotime("1970-01-01"));
            }
        }

//        $new_date = date_create_from_format($oldFormat, $date);
        $new_date = strtotime($date);

        return date($newFormat, $new_date);
    }

}
