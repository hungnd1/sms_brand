<?php

use yii\db\Migration;

class m161231_061913_add_column_status_year_table_contact extends Migration
{
    public function up()
    {
        $this->addColumn('contact','school_year_status','int(2)');
    }

    public function down()
    {
        echo "m161231_061913_add_column_status_year_table_contact cannot be reverted.\n";

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
