<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Agreement;

/**
 * AgreementSearch represents the model behind the search form of `common\models\Agreement`.
 */
class AgreementSearch extends Agreement
{

    public $created_at_range;
    public $ended_at_range;
    public $organization;
    public $employee;
    public $country;




    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'organization', 'employee', 'country'], 'integer'],
            [['name', 'date_start', 'date_end', 'desc', 'created_at_range', 'ended_at_range', 'iogv_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function attributeLabels()
    {
        /*
        return [
            'organization' => 'Организации',
        ];
        */
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
        //$query = Agreement::find()->joinWith(['sideAgrs t1']);
        $query = Agreement::find()
            ->join('LEFT JOIN','side_agr', 'side_agr.agreement_id = agreement.id')
            ->join('LEFT JOIN','organization', 'organization.id = side_agr.org_id')
            ->join('LEFT JOIN','country', 'country.id = organization.country_id');


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'agreement.id' => $this->id,
            'agreement.status' => $this->status,
            //'date_start' => $this->date_start,
            //'date_end' => $this->date_end,
            'agreement.iogv_id' => $this->iogv_id,
            'agreement.created_at' => $this->created_at,
            'agreement.updated_at' => $this->updated_at,
        ]);

        if(isset($this->organization)){
            $query->andFilterWhere([
                'side_agr.org_id' => $this->organization,
            ]);
        }

        if(isset($this->country)){
            $query->andFilterWhere([
                'country.id' => $this->country,
            ]);
        }

        if(isset($this->employee)){
            $query->andFilterWhere([
                'side_agr.employee_id' => $this->employee,
            ]);
        }

        $query->andFilterWhere(['ilike', 'agreement.name', $this->name])
            ->andFilterWhere(['ilike', 'agreement.desc', $this->desc]);

        if(!empty($this->created_at_range) && strpos($this->created_at_range, '-') !== false) {
            list($from_date_start, $to_date_start) = explode(' - ', $this->created_at_range);
            $query->andFilterWhere(['between', 'agreement.date_start', $from_date_start, $to_date_start]);
        }

        if(!empty($this->ended_at_range) && strpos($this->ended_at_range, '-') !== false) {
            list($from_date_end, $to_date_end) = explode(' - ', $this->ended_at_range);
            $query->andFilterWhere(['between', 'agreement.date_end', $from_date_end, $to_date_end]);
        }

        return $dataProvider;
    }
}
