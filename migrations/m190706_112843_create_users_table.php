<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m190706_112843_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id'          => $this->primaryKey(),
            'username'    => $this->string(100)->notNull(),
            'password'    => $this->string(100)->notNull(),
            'authKey'     => $this->string(191)->notNull(),
            'accessToken' => $this->string(191)->notNull()

        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
