<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\HistoryContact;

/**
 * HistoryContactSearch represents the model behind the search form about `common\models\HistoryContact`.
 */
class HistoryContactSearch extends HistoryContact
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'brandname_id', 'template_id', 'member_by'], 'integer'],
            [['content', 'campain_name'], 'safe'],
            [[ 'created_at', 'updated_at', 'send_schedule'],'string']
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
        $query = HistoryContact::find()->andWhere(['member_by'=>Yii::$app->user->id]);

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
            'type' => $this->type,
            'brandname_id' => $this->brandname_id,
            'template_id' => $this->template_id,
            'member_by' => $this->member_by,
        ]);

        if($this->send_schedule != ''){
            $created_at_arr = explode('/',$this->send_schedule);
            $date = \DateTime::createFromFormat('Y-m-d H:i:s',$created_at_arr['2'].'-'.$created_at_arr['1'].'-'.$created_at_arr['0'].' 00:00:00');
            $create_at     = strtotime($date->format('m/d/Y'));
            $create_at_end = $create_at + (60 * 60 * 24);

            $query->andFilterWhere(['>=', 'send_schedule', $create_at]);
            $query->andFilterWhere(['<=', 'send_schedule', $create_at_end]);
        }

        if($this->created_at != ''){
            $created_at_arr = explode('/',$this->created_at);
            $date = \DateTime::createFromFormat('Y-m-d H:i:s',$created_at_arr['2'].'-'.$created_at_arr['1'].'-'.$created_at_arr['0'].' 00:00:00');
            $create_at     = strtotime($date->format('m/d/Y'));
            $create_at_end = $create_at + (60 * 60 * 24);

            $query->andFilterWhere(['>=', 'created_at', $create_at]);
            $query->andFilterWhere(['<=', 'created_at', $create_at_end]);
        }

        if($this->updated_at != ''){
            $created_at_arr = explode('/',$this->updated_at);
            $date = \DateTime::createFromFormat('Y-m-d H:i:s',$created_at_arr['2'].'-'.$created_at_arr['1'].'-'.$created_at_arr['0'].' 00:00:00');
            $updated_at     = strtotime($date->format('m/d/Y'));
            $updated_at_end = $updated_at + (60 * 60 * 24);

            $query->andFilterWhere(['>=', 'updated_at', $updated_at]);
            $query->andFilterWhere(['<=', 'updated_at', $updated_at_end]);
        }

        $query->andFilterWhere(['like', 'lower(content)', strtolower($this->content)])
            ->andFilterWhere(['like', 'lower(campain_name)', strtolower($this->campain_name)]);

        return $dataProvider;
    }
}
