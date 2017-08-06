<?php

use app\modules\core\db\Migration;
use app\modules\core\models\ar\Comment;

class m170726_184345_create_comments_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        //$modelNames = array_keys(File::getAttributeLabels('owner_model_name'));
        $modelNames = ['Advert', 'User', 'File'];
        $this->createTable(Comment::tableName(), [
            'id'                    => 'pk',
            'user_id'               => 'INT(11) NOT NULL',
            'owner_id'              => 'INT(11) NOT NULL',
            'owner_model_name'      => 'ENUM("'.implode('","', $modelNames).'") NOT NULL',
            'text'                  => 'TEXT NOT NULL',
            'created_at'            => 'timestamp',
            'updated_at'            => 'timestamp',
        ], $this->tableOptions);
        $this->addForeignKey('FK_comment_refs_user', 'comment', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');
        $this->createIndex('UI_owner_id', Comment::tableName(), 'owner_id');
        $this->createIndex('UI_owner_model_name_AND_owner_id', Comment::tableName(), ['owner_model_name', 'owner_id']);
    }

    /**
    * @inheritdoc
    */
    public function down()
    {
        $this->dropTable('comment');
    }
}
