<?php

use yii\db\Migration;

/**
 * Handles the creation of table `people`.
 */
class m190122_065857_create_people_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('people', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('people');
    }
}
