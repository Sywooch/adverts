<?php

use app\modules\core\db\Migration;
use app\modules\core\models\ar\Like;

class m170726_170329_create_likes_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        //$modelNames = array_keys(File::getAttributeLabels('owner_model_name'));
        $modelNames = ['Advert', 'User', 'File'];
        $this->createTable(Like::tableName(), [
            'id'               => 'pk',
            'user_id'          => 'INT(11) NOT NULL',
            'owner_id'         => 'INT(11) NOT NULL',
            'owner_model_name' => 'ENUM("'.implode('","', $modelNames).'") NOT NULL',
            'value'            => 'TINYINT(1) NOT NULL',
        ], $this->tableOptions);

        $this->addForeignKey('FK_like_REFS_user', 'like', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');
        $this->createIndex('UI_owner_id', Like::tableName(), 'owner_id');
        $this->createIndex('UI_owner_model_name_AND_owner_id', Like::tableName(), ['owner_model_name', 'owner_id']);
    }

    /**
    * @inheritdoc
    */
    public function down()
    {
        $this->dropTable('like');
    }
}
