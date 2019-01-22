<?php

use yii\db\Migration;

/**
 * Handles the creation of table `people`.
 */
class m190122_065955_create_people_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('people', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
        ]);
        $this->batchInsert('people', ['name'], [
            ['Trần Văn A'],
            ['山田 太郎'],
            ['Mohamed Ali'],
            ['John Brown'],
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
