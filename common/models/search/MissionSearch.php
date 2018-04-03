<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Mission;

/**
 * MissionSearch represents the model behind the search form of `common\models\Mission`.
 */
class MissionSearch extends Mission
{

    public $created_at_range;
    public $ended_at_range;

    public $organization_text;
    public $duty_text;
    public $member_text;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'country_id', 'region_id', 'organization_id', 'duty_man_id'], 'integer'],
            [['name', 'date_start', 'date_end', 'order', 'target', 'visible', 'created_at_range', 'ended_at_range', 'iogv_id', 'organization_text', 'duty_text', 'member_text'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Mission::find()
                    ->join('LEFT JOIN','mission_employee', 'mission.id = mission_employee.mission_id')
                    ->join('LEFT JOIN','employee', 'mission_employee.employee_id = employee.id')
                    ->join('LEFT JOIN','organization', 'mission.organization_id = organization.id');


        $query->andWhere(['mission.visible' => true]);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            /*
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ]
            */
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'mission.id' => $this->id,
            //'date_start' => $this->date_start,
            //'date_end' => $this->date_end,
            //'country_id' => $this->country_id,
            'region_id' => $this->region_id,
            //'organization_id' => $this->organization_id,
            'duty_man_id' => $this->duty_man_id,
            //'iogv_id' => $this->iogv_id,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'order', $this->order])
            ->andFilterWhere(['ilike', 'target', $this->target])
            ->andFilterWhere(['ilike', 'city', $this->city]);


        if(!empty($this->created_at_range) && strpos($this->created_at_range, '-') !== false) {
            list($from_date_start, $to_date_start) = explode(' - ', $this->created_at_range);
            $query->andFilterWhere(['between', 'mission.date_start', $from_date_start, $to_date_start]);
        }

        if(!empty($this->ended_at_range) && strpos($this->ended_at_range, '-') !== false) {
            list($from_date_end, $to_date_end) = explode(' - ', $this->ended_at_range);
            $query->andFilterWhere(['between', 'mission.date_end', $from_date_end, $to_date_end]);
        }

        if(isset($this->employee_text)){
            $query->andFilterWhere(['ilike', 'employee.fio', $this->employee_text]);
        }

        if(isset($this->organization_text)){
            $query->andFilterWhere(['ilike', 'organization.name', $this->organization_text]);
        }

        if(isset($this->member_text)){
            $query->andFilterWhere(['ilike', 'employee.fio', $this->member_text]);
        }

        $query->orderBy(['mission.created_at' => SORT_DESC]);

        return $dataProvider;
    }
}
