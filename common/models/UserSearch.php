<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'role', 'status','type_kh','level','is_send', 'created_at', 'updated_at', 'type', 'site_id', 'dealer_id', 'parent_id'], 'integer'],
            [['username', 'fullname', 'phone_number', 'auth_key', 'password_hash', 'password_reset_token', 'email'], 'safe'],
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
    public function search($params, $childUser = false)
    {
        $query = User::find();
            $query->andWhere(['created_by' => Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'role' => $this->role,
            'status' => $this->status,
            'type_kh' => $this->type_kh,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'type' => $this->type,
            'level' => $this->level,
            'site_id' => $this->site_id,
            'dealer_id' => $this->dealer_id,
            'parent_id' => $this->parent_id,
            'is_send'=>$this->is_send
        ]);

        if ($childUser) {
            $query->andWhere(['is', 'parent_id', null]);
        }

        $query->andFilterWhere(['like', 'lower(username)', strtolower($this->username)])
            ->andFilterWhere(['like', 'lower(fullname)', strtolower($this->fullname)])
            ->andFilterWhere(['like', 'lower(phone_number)', strtolower($this->phone_number)])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email]);
        /** Không lấy những thằng đã xóa */
        $query->andWhere(['<>', 'status', User::STATUS_DELETED]);
        $query->orderBy(['updated_at'=>SORT_DESC]);
        return $dataProvider;
    }
}
