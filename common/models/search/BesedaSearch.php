<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Beseda;

/**
 * BesedaSearch represents the model behind the search form of `common\models\Beseda`.
 */
class BesedaSearch extends Beseda
{

    public $created_at_range;
    public $control_date_range;
    public $report_date_range;
    public $agreements;
    public $members;
    public $orgs;
    public $country;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'status', 'iogv_id'], 'integer'],
            [['theme', 'target', 'date_start', 'date_start_time', 'report_date',
                'control_date', 'notes', 'created_at_range', 'questions', 'address', 'iniciator_id', 'control_date_range',
                'report_date', 'report_overdue', 'agreements', 'members', 'orgs', 'country'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Beseda::find()
            ->join('LEFT JOIN','organization t1', 't1.id = beseda.iniciator_id')
            ->join('LEFT JOIN','beseda_agreement', 'beseda.id = beseda_agreement.beseda_id')
            ->join('LEFT JOIN','agreement', 'agreement.id = beseda_agreement.agreement_id')
            ->join('LEFT JOIN','beseda_employee', 'beseda_employee.beseda_id = beseda.id')
            ->join('LEFT JOIN','employee', 'beseda_employee.employee_id = employee.id')
            ->join('LEFT JOIN','organization t2', 'employee.organization_id = t2.id')
            ->join('LEFT JOIN','country', 't2.country_id = country.id')
            ->distinct();


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
            //'id' => $this->id,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
            //'date_start' => $this->date_start,
            'date_start_time' => $this->date_start_time,
            //'iniciator_id' => $this->iniciator_id,
            //'report_date' => $this->report_date,
            //'control_date' => $this->control_date,
            'beseda.status' => $this->status,
            'beseda.report_overdue' => $this->report_overdue,
            //'iogv_id' => $this->iogv_id,
        ]);


        if(!empty($this->created_at_range) && strpos($this->created_at_range, '-') !== false) {
            list($from_date_start, $to_date_start) = explode(' - ', $this->created_at_range);
            $query->andFilterWhere(['between', 'beseda.date_start', $from_date_start, $to_date_start]);
        }

        if(!empty($this->control_date_range) && strpos($this->control_date_range, '-') !== false) {
            list($from_control_start, $to_control_end) = explode(' - ', $this->control_date_range);
            $query->andFilterWhere(['between', 'beseda.control_date', $from_control_start, $to_control_end]);
        }

        if(!empty($this->report_date_range) && strpos($this->report_date_range, '-') !== false) {
            list($from_report_start, $to_report_end) = explode(' - ', $this->report_date_range);
            $query->andFilterWhere(['between', 'beseda.report_date', $from_report_start, $to_report_end]);
        }


        if(!empty($this->agreements)){
            $query->andFilterWhere(['ilike', 'agreement.name', $this->agreements]);
        }

        if(!empty($this->members)){
            $query->andFilterWhere(['ilike', 'employee.fio', $this->members]);
        }

        if(!empty($this->orgs)){
            $query->andFilterWhere(['ilike', 't2.name', $this->orgs]);
        }

        if(!empty($this->country)){
            $query->andFilterWhere(['ilike', 'country.name', $this->country]);
        }


        $query->andFilterWhere(['ilike', 'beseda.theme', $this->theme])
            ->andFilterWhere(['ilike', 'beseda.target', $this->target])
            ->andFilterWhere(['ilike', 'beseda.notes', $this->notes])
            ->andFilterWhere(['ilike', 'beseda.questions', $this->questions])
            ->andFilterWhere(['ilike', 'beseda.address', $this->address])
            ->andFilterWhere(['ilike', 't1.name', $this->iniciator_id]);

        return $dataProvider;
    }
}
