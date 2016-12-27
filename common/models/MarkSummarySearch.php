<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MarkSummary;

/**
 * MarkSummarySearch represents the model behind the search form about `common\models\MarkSummary`.
 */
class MarkSummarySearch extends MarkSummary
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'student_id', 'class_id', 'semester', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['marks', 'description'], 'safe'],
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
        $query = MarkSummary::find();

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
            'student_id' => $this->student_id,
            'class_id' => $this->class_id,
            'semester' => $this->semester,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'marks', $this->marks])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
