<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\mssql\PDO;

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
            if ($params->status_ != null) {
                if ($params->status_ != HistoryContact::STATUS_ALL) {
                    $query->andWhere(['history_contact_asm.history_contact_status' => $params->status_]);
                }
            }
            if ($params->network != null) {
                $number_4 = array();
                $number_5 = array();
                for ($i = 0; $i < sizeof($params->network); $i++) {
                    $network_number = Network::findOne(['id' => $params->network[$i]])->number_network;
                    $number = explode(',', $network_number);
                    for ($j = 0; $j < sizeof($number); $j++) {
                        if (strlen($number[$j]) == 4) {
                            array_push($number_4, $number[$j]);
                        } else {
                            array_push($number_5, $number[$j]);
                        }
                    }
                }
                $query->andFilterWhere(['or', ['IN', 'SUBSTRING(contact_detail.phone_number,1,4)', $number_4],
                    ['IN', 'SUBSTRING(contact_detail.phone_number,1,5)', $number_5]]);
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

    public function comment($params = null)
    {
        if ($params) {
            $sql = "select distinct * from (
              SELECT contact_detail.*,comment.content as comment,comment.content_bonus as comment_bonus from contact_detail
              left join comment on comment.id_contact_detail = contact_detail.id where comment.is_month = 0 ";
            if ($params->fromdate) {
                $created_at_arr = explode('/', $params->fromdate);
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $created_at_arr['2'] . '-' . $created_at_arr['1'] . '-' . $created_at_arr['0'] . ' 00:00:00');
                $updated_at = strtotime($date->format('m/d/Y'));
                $create_at_end = $updated_at + (60 * 60 * 24);
                $sql .= " and comment.created_at >= ".$updated_at." AND comment.created_at <= ".$create_at_end;
            }
            $sql .= " union all
               select contact_detail.*,'' as comment,'' as comment_bonus from contact_detail ";
            $sql .= " ) a where a.created_by = ". Yii::$app->user->id;
            if($params->fullname){
                $sql .= " and a.fullname like %".$params->fullname."%";
            }
            if ($params->contact_id) {
                $sql .= " and a.contact_id = ".$params->contact_id;
            }else{
                $sql .= " and a.contact_id = ".-1;
            }
            $sql .= " group by a.id";
            if($params->fromdate && $params->contact_id) {
                $created_at_arr = explode('/', $params->fromdate);
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $created_at_arr['2'] . '-' . $created_at_arr['1'] . '-' . $created_at_arr['0'] . ' 00:00:00');
                $updated_at = strtotime($date->format('m/d/Y'));
                $command = Yii::$app->db->createCommand($sql);
                $datareader = $command->query();
                foreach ($datareader as $val) {
                    $comment = Comment::findOne(['id_contact_detail'=>$val['id'],'created_at'=>$updated_at,'is_month'=>Comment::NOT_MONTH]);
                    if($comment){
                        $comment->updated_at = time();
                        $comment->save(false);
                    }else{
                        $comment_ = new Comment();
                        $comment_->id_contact_detail = $val['id'];
                        $comment_->created_at = $updated_at;
                        $comment_->updated_at = time();
                        $comment_->is_month = Comment::NOT_MONTH;
                        $comment_->save(false);
                    }
                }
            }
            $query = ContactDetail::findBySql($sql);
        } else {
            $query = ContactDetail::find()->andWhere(['created_by' => -1]);
        }

        // add conditions that should always apply here


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }


    public function commentMonth($params = null)
    {
        if ($params) {
            $sql = "select distinct * from (
              SELECT contact_detail.*,comment.content as comment,comment.content_bonus as comment_bonus from contact_detail
              left join comment on comment.id_contact_detail = contact_detail.id where comment.is_month = 1 ";
            if ($params->fromdate) {
                $created_at_arr = explode('/', $params->fromdate);
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $created_at_arr['1'] . '-' . $created_at_arr['0'] . '-' . 1 . ' 00:00:00');
                $updated_at = strtotime($date->format('m/d/Y'));
                $create_at_end = $updated_at + (60 * 60 * 24 * 30);
                $sql .= " and comment.created_at >= ".$updated_at." AND comment.created_at <= ".$create_at_end;
            }
            $sql .= " union all
               select contact_detail.*,'' as comment,'' as comment_bonus from contact_detail ";
            $sql .= " ) a where a.created_by = ". Yii::$app->user->id;
            if($params->fullname){
                $sql .= " and a.fullname like %".$params->fullname."%";
            }
            if ($params->contact_id) {
                $sql .= " and a.contact_id = ".$params->contact_id;
            }else{
                $sql .= " and a.contact_id = ".-1;
            }
            $sql .= " group by a.id";
            if($params->fromdate && $params->contact_id) {
                $created_at_arr = explode('/', $params->fromdate);
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $created_at_arr['1'] . '-' . $created_at_arr['0'] . '-' . 1 . ' 00:00:00');
                $updated_at = strtotime($date->format('m/d/Y'));
                $command = Yii::$app->db->createCommand($sql);
                $datareader = $command->query();
                foreach ($datareader as $val) {
                    $comment = Comment::findOne(['id_contact_detail'=>$val['id'],'created_at'=>$updated_at,'is_month'=>Comment::IS_MONTH]);
                    if($comment){
                        $comment->updated_at = time();
                        $comment->save(false);
                    }else{
                        $comment_ = new Comment();
                        $comment_->id_contact_detail = $val['id'];
                        $comment_->created_at = $updated_at;
                        $comment_->updated_at = time();
                        $comment_->is_month = Comment::IS_MONTH;
                        $comment_->save(false);
                    }
                }
            }
            $query = ContactDetail::findBySql($sql);
        } else {
            $query = ContactDetail::find()->andWhere(['created_by' => -1]);
        }

        // add conditions that should always apply here


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}
