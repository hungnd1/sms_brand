<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Brandname;

/**
 * BrandnameSearch represents the model behind the search form about `common\models\Brandname`.
 */
class BrandnameSearch extends Brandname
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_by'], 'integer'],
            [['brandname', 'brand_username', 'brand_password', 'brand_hash_token'], 'safe'],
            [['created_at','updated_at','expired_at'],'string']
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
        $query = Brandname::find()->andWhere(['created_by'=>Yii::$app->user->id]);

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
            'status' => $this->status,
            'created_by' => $this->created_by,
        ]);

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

        if($this->expired_at != ''){
            $created_at_arr = explode('/',$this->expired_at);
            $date = \DateTime::createFromFormat('Y-m-d H:i:s',$created_at_arr['2'].'-'.$created_at_arr['1'].'-'.$created_at_arr['0'].' 00:00:00');
            $updated_at     = strtotime($date->format('m/d/Y'));
            $updated_at_end = $updated_at + (60 * 60 * 24);

            $query->andFilterWhere(['>=', 'expired_at', $updated_at]);
            $query->andFilterWhere(['<=', 'expired_at', $updated_at_end]);
        }

        $query->andFilterWhere(['like', 'lower(brandname)', strtolower($this->brandname)]);
        $query->orderBy(['updated_at'=>SORT_DESC]);
        return $dataProvider;
    }
}
