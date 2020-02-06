<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InvoiceCart;

/**
 * InvoiceCartSearch represents the model behind the search form about `app\models\InvoiceCart`.
 */
class InvoiceCartSearch extends InvoiceCart
{
    /**
     * @inheritdoc
     */
//    public function rules()
//    {
//        return [
//            [['id', 'id_invoice', 'type'], 'integer'],
//            [['account_number', 'ss', 'kredit_info', 'kredit_info_2', 'debet_vs', 'debet_ss', 'avizo'], 'safe'],
//        ];
//    }

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
        $query = InvoiceCart::find()->
                with(["idInvoice"])->
                select(["invoice_cart.*", "(invoice.price) + (invoice.price_vat) AS suma_s_dph"])->
                join("LEFT JOIN", "invoice", "invoice.id = invoice_cart.id_invoice");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
