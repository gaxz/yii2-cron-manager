<?php

namespace gaxz\crontab\migrations;

use yii\db\Migration;

/**
 * Class m201014_143840_add_name_and_description_to_cron_task
 */
class m201014_143840_add_name_and_description_to_cron_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%cron_task}}', 'name', $this->string(50)->after('id'));
        $this->addColumn('{{%cron_task}}', 'description', $this->string()->after('name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%cron_task}}', 'description');
        $this->dropColumn('{{%cron_task}}', 'name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201014_143840_add_name_and_description_to_cron_task cannot be reverted.\n";

        return false;
    }
    */
}
