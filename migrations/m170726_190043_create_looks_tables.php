<?php

use app\modules\core\db\Migration;
use app\modules\core\models\ar\Look;

class m170726_190043_create_looks_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        //$modelNames = array_keys(File::getAttributeLabels('owner_model_name'));
        $modelNames = ['Advert', 'User'];
        $this->createTable(Look::tableName(), [
            'id'                    => 'pk',
            'user_id'               => 'INT(11) NOT NULL',
            'owner_id'              => 'INT(11) NOT NULL',
            'owner_model_name'      => 'ENUM("'.implode('","', $modelNames).'") NOT NULL',
            'value'                 => 'INT(5)',
        ], $this->tableOptions);
        $this->addForeignKey('FK_look_REFS_user', 'look', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');
        $this->createIndex('UI_owner_id', Look::tableName(), 'owner_id');
        $this->createIndex('UI_owner_model_name_AND_owner_id', Look::tableName(), ['owner_model_name', 'owner_id']);
    }

    /**
    * @inheritdoc
    */
    public function down()
    {
        $this->dropTable('look');
    }
}
