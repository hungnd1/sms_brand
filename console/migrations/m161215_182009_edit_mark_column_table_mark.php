<?php

use yii\db\Migration;

class m161215_182009_edit_mark_column_table_mark extends Migration
{
    public function up()
    {
        $this->dropColumn('mark','type');
        $this->dropColumn('mark','part');
        $this->dropColumn('mark','mark');
        $this->addColumn('mark', 'marks','varchar(255)');
    }

    public function down()
    {
        echo "m161215_182009_edit_mark_column_table_mark cannot be reverted.\n";

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
