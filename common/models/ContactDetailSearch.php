<?php

namespace common\models;

use common\helpers\TBApplication;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ContactDetailSearch represents the model behind the search form about `common\models\ContactDetail`.
 */
class ContactDetailSearch extends ContactDetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'gender', 'created_by', 'contact_id'], 'integer'],
            [['fullname', 'phone_number', 'address', 'email', 'company', 'notes'], 'safe'],
            [['created_at', 'updated_at', 'birthday'], 'string']
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
    public function search($params, $id)
    {
        $query = ContactDetail::find()->andWhere(['contact_id' => $id])->andWhere(['created_by' => Yii::$app->user->id]);

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
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            'created_by' => $this->created_by,
            'contact_id' => $this->contact_id,
        ]);

        if ($this->birthday != '') {
            $created_at_arr = explode('/', $this->birthday);
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $created_at_arr['2'] . '-' . $created_at_arr['1'] . '-' . $created_at_arr['0'] . ' 00:00:00');
            $create_at = strtotime($date->format('m/d/Y'));
            $create_at_end = $create_at + (60 * 60 * 24);

            $query->andFilterWhere(['>=', 'birthday', $create_at]);
            $query->andFilterWhere(['<=', 'birthday', $create_at_end]);
        }

        if ($this->created_at != '') {
            $created_at_arr = explode('/', $this->created_at);
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $created_at_arr['2'] . '-' . $created_at_arr['1'] . '-' . $created_at_arr['0'] . ' 00:00:00');
            $create_at = strtotime($date->format('m/d/Y'));
            $create_at_end = $create_at + (60 * 60 * 24);

            $query->andFilterWhere(['>=', 'created_at', $create_at]);
            $query->andFilterWhere(['<=', 'created_at', $create_at_end]);
        }

        if ($this->updated_at != '') {
            $created_at_arr = explode('/', $this->updated_at);
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $created_at_arr['2'] . '-' . $created_at_arr['1'] . '-' . $created_at_arr['0'] . ' 00:00:00');
            $updated_at = strtotime($date->format('m/d/Y'));
            $updated_at_end = $updated_at + (60 * 60 * 24);

            $query->andFilterWhere(['>=', 'updated_at', $updated_at]);
            $query->andFilterWhere(['<=', 'updated_at', $updated_at_end]);
        }

        $query->andFilterWhere(['like', 'lower(fullname)', strtolower($this->fullname)])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'lower(email)', strtolower($this->email)])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }

    public function searchBirthday($params)
    {
        $query = ContactDetail::find()->andWhere(['created_by' => Yii::$app->user->id])
            ->andWhere('month(FROM_UNIXTIME(birthday)) = :m', [':m' => date('m')]);

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
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            'created_by' => $this->created_by,
            'contact_id' => $this->contact_id,
        ]);

        if ($this->birthday != '') {
            $created_at_arr = explode('/', $this->birthday);
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $created_at_arr['2'] . '-' . $created_at_arr['1'] . '-' . $created_at_arr['0'] . ' 00:00:00');
            $create_at = strtotime($date->format('m/d/Y'));
            $create_at_end = $create_at + (60 * 60 * 24);

            $query->andFilterWhere(['>=', 'birthday', $create_at]);
            $query->andFilterWhere(['<=', 'birthday', $create_at_end]);
        }

        if ($this->created_at != '') {
            $created_at_arr = explode('/', $this->created_at);
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $created_at_arr['2'] . '-' . $created_at_arr['1'] . '-' . $created_at_arr['0'] . ' 00:00:00');
            $create_at = strtotime($date->format('m/d/Y'));
            $create_at_end = $create_at + (60 * 60 * 24);

            $query->andFilterWhere(['>=', 'created_at', $create_at]);
            $query->andFilterWhere(['<=', 'created_at', $create_at_end]);
        }

        if ($this->updated_at != '') {
            $created_at_arr = explode('/', $this->updated_at);
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $created_at_arr['2'] . '-' . $created_at_arr['1'] . '-' . $created_at_arr['0'] . ' 00:00:00');
            $updated_at = strtotime($date->format('m/d/Y'));
            $updated_at_end = $updated_at + (60 * 60 * 24);

            $query->andFilterWhere(['>=', 'updated_at', $updated_at]);
            $query->andFilterWhere(['<=', 'updated_at', $updated_at_end]);
        }

        $query->andFilterWhere(['like', 'lower(fullname)', strtolower($this->fullname)])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'lower(email)', strtolower($this->email)])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }

    public function searchHistory($params = null)
    {
        if ($params) {
            $query = HistoryContactAsm::find()
                ->innerJoin('history_contact', 'history_contact.id = history_contact_asm.history_contact_id');
            $query->innerJoin('contact_detail', 'contact_detail.id = history_contact_asm.contact_id');
            if ($params->brandname_id) {
                $query->innerJoin('brandname', 'brandname.id = history_contact.brandname_id')
                    ->andWhere(['brandname.id' => $params->brandname_id]);
            }
            if ($params->created_by) {
                $query->andWhere(['history_contact.member_by' => $params->created_by]);
            }
            if ($params->searchphone) {
                $query->andWhere(['like', 'phone_number', $params->searchphone]);
            }
            if ($params->type) {
                $query->andWhere(['history_contact.type' => $params->type]);
            }
            if ($params->fromdate) {
                $created_at_arr = explode('/', $params->fromdate);
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $created_at_arr['2'] . '-' . $created_at_arr['1'] . '-' . $created_at_arr['0'] . ' 00:00:00');
                $updated_at = strtotime($date->format('m/d/Y'));

                $query->andWhere(['>=', 'history_contact.updated_at', $updated_at]);
            }
            if ($params->todate) {
                $created_at_arr_ = explode('/', $params->todate);
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $created_at_arr_['2'] . '-' . $created_at_arr_['1'] . '-' . $created_at_arr_['0'] . ' 00:00:00');
                $updated_at_ = strtotime($date->format('m/d/Y'));
                $create_at_end = $updated_at_ + (60 * 60 * 24);
                $query->andWhere(['<=', 'history_contact.updated_at', $create_at_end]);
            }
            if ($params->status_) {
                if ($params->status_ != HistoryContact::STATUS_ALL) {
                    $query->andWhere(['history_contact_asm.history_contact_status' => $params->status_]);
                }
            }
            if ($params->network) {
                $number_4 = '';
                $number_5 = '';
                for ($i = 0; $i < sizeof($params->network); $i++) {
                    $network_number = Network::findOne(['id' => $params->network[$i]])->number_network;
                    $number = explode(',', $network_number);
                    for ($j = 0; $j < sizeof($number); $j++) {
                        if (strlen($number[$j]) == 4) {
                            $number_4 .= $number[$j] . ',';
                        } else {
                            $number_5 .= $number[$j] . ',';
                        }
                    }
                }
                $number_4 = rtrim($number_4, ',');
                $number_5 = rtrim($number_5, ',');
                $query->orFilterWhere(['in','SUBSTRING(contact_detail.phone_number,1,4)',$number_4]);
                $query->orFilterWhere(['in','SUBSTRING(contact_detail.phone_number,1,5)',$number_5]);
            }
            if ($params->month) {
                $created_at_arr_ = explode('/', $params->month);
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $created_at_arr_['1'] . '-' . $created_at_arr_['0'] . '-' . 1 . ' 00:00:00');
                $updated_at_ = strtotime($date->format('m/d/Y'));
                $create_at_end = $updated_at_ + (60 * 60 * 24 * 30);
                $query->andWhere(['>=', 'history_contact.updated_at', $updated_at_]);
                $query->andWhere(['<=', 'history_contact.updated_at', $create_at_end]);
            }
        } else {
            $query = HistoryContactAsm::find()
                ->innerJoin('history_contact', 'history_contact.id = history_contact_asm.history_contact_id');
            $query->innerJoin('contact_detail', 'contact_detail.id = history_contact_asm.contact_id');
        }

        $query->orderBy(['history_contact_asm.updated_at' => SORT_DESC]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $dataProvider;
    }

    /**
     * @param $contactName
     * @return int|string
     * @internal param $contact_name
     */
    public static function countContactDetailByContactName($contactName)
    {
        $contactId = Contact::findOne(['contact_name' => $contactName]);
        return ContactDetail::find()->where(['contact_id' => $contactId])->count();
    }
}
