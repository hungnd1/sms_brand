<?php

use yii\db\Migration;

class m170122_100757_add_column_table_mark_type extends Migration
{
    public function up()
    {
        $this->addColumn('mark_type','ip','varchar(50)');
    }

    public function down()
    {
        echo "m170122_100757_add_column_table_mark_type cannot be reverted.\n";

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
