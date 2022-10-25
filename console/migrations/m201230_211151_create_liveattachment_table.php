<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%liveattachment}}`.
 */
class m201230_211151_create_liveattachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%liveattachment}}', [
            'id' => $this->primaryKey(),
            'path' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%liveattachment}}');
    }
}
