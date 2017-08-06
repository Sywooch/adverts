<?php

use app\modules\core\db\Migration;
use app\modules\core\models\ar\Bookmark;

class m170726_185320_create_bookmarks_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        //$modelNames = array_keys(File::getAttributeLabels('owner_model_name'));
        $modelNames = ['Advert', 'User', 'File'];
        $this->createTable(Bookmark::tableName(), [
            'id'                    => 'pk',
            'user_id'               => 'INT(11) NOT NULL',
            'owner_id'              => 'INT(11) NOT NULL',
            'owner_model_name'      => 'ENUM("'.implode('","', $modelNames).'") NOT NULL',
        ], $this->tableOptions);
        $this->addForeignKey('FK_bookmark_refs_user', 'bookmark', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');
        $this->createIndex('UI_owner_id', Bookmark::tableName(), 'owner_id');
        $this->createIndex('UI_owner_model_name_AND_owner_id', Bookmark::tableName(), ['owner_model_name', 'owner_id']);
    }

    /**
    * @inheritdoc
    */
    public function down()
    {
        $this->dropTable('bookmark');
    }
}
