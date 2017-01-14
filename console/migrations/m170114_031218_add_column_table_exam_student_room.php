<?php

use yii\db\Migration;

class m170114_031218_add_column_table_exam_student_room extends Migration
{
    public function up()
    {
        $this->addColumn('exam_student_room','mark_summary','varchar(10)');
        $this->addColumn('exam_student_room','mark_avg','varchar(10)');
        $this->addColumn('exam_student_room','mark_rank','int(5)');
        $this->addColumn('exam_student_room','mark_type','int(2)');
    }

    public function down()
    {
        echo "m170114_031218_add_column_table_exam_student_room cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
