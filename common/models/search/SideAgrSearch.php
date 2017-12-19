<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SideAgr;

/**
 * SideAgrSearch represents the model behind the search form of `common\models\SideAgr`.
 */
class SideAgrSearch extends SideAgr
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'agreement_id', 'org_id', 'employee_id'], 'integer'],
            [['desc', 'subdivision'], 'safe'],
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
    public function searchByAgreement($params, $agreementId)
    {
        $query = SideAgr::find();

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
            'id' => $this->id,
            'agreement_id' => $agreementId,
            'org_id' => $this->org_id,
            'employee_id' => $this->employee_id,
        ]);

        $query->andFilterWhere(['ilike', 'desc', $this->desc])
            ->andFilterWhere(['ilike', 'subdivision', $this->subdivision]);

        return $dataProvider;
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
        $query = SideAgr::find();

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
            'id' => $this->id,
            'agreement_id' => $this->agreement_id,
            'org_id' => $this->org_id,
            'employee_id' => $this->employee_id,
        ]);

        $query->andFilterWhere(['ilike', 'desc', $this->desc])
            ->andFilterWhere(['ilike', 'subdivision', $this->subdivision]);

        return $dataProvider;
    }



}
