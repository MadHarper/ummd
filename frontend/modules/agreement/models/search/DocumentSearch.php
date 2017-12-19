<?php

namespace frontend\modules\agreement\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\agreement\models\Document;

/**
 * DocumentSearch represents the model behind the search form of `common\models\Document`.
 */
class DocumentSearch extends Document
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'model_id', 'created_at', 'updated_at'], 'integer'],
            [['model', 'content', 'description', 'origin_name', 'sea_name', 'link'], 'safe'],
            [['visible'], 'boolean'],
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



    public function searchByMasterModel($params, $masterModel)
    {
        $query = Document::find()
            ->andWhere(['model' => get_class($masterModel), 'model_id' => $masterModel->id])
            ->andWhere(['visible' => true]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder'=> [
                    'created_at' => SORT_DESC
                ]
            ]
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
            //'model_id' => $this->model_id,
            //'visible' => $this->visible,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'model', $this->model])
            ->andFilterWhere(['ilike', 'content', $this->content])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'origin_name', $this->origin_name])
            ->andFilterWhere(['ilike', 'sea_name', $this->sea_name])
            ->andFilterWhere(['ilike', 'link', $this->link]);

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
        $query = Document::find();

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
            'model_id' => $this->model_id,
            'visible' => $this->visible,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'model', $this->model])
            ->andFilterWhere(['ilike', 'content', $this->content])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'origin_name', $this->origin_name])
            ->andFilterWhere(['ilike', 'sea_name', $this->sea_name])
            ->andFilterWhere(['ilike', 'link', $this->link]);

        return $dataProvider;
    }
}
