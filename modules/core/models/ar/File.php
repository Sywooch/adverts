<?php

namespace app\modules\core\models\ar;

use app\modules\adverts\models\ar\Advert;
use app\modules\adverts\models\ar\AdvertTemplet;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "file".
 *
 * @property integer $owner_id
 * @property string $owner_model_name
 * @property string $file_name
 * @property string $origin_file_name
 * @property string $deleted_at
 *
 * @property string $fullName
 * @property string $path
 * @property string $url
 */
class File extends \app\modules\core\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_id'], 'integer'],
            [['owner_model_name', 'file_name', 'origin_file_name'], 'required'],
            [['deleted_at'], 'safe'],
            [['owner_model_name'], 'string', 'max' => 32],
            [['file_name', 'origin_file_name'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'owner_id' => Yii::t('app', 'Owner ID'),
            'owner_model_name' => Yii::t('app', 'Owner Model Name'),
            'file_name' => Yii::t('app', 'File Name'),
            'origin_file_name' => Yii::t('app', 'Origin File Name'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\modules\core\models\aq\FileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\core\models\aq\FileQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function attributeLabelsConfig()
    {
        return [
            'owner_model_name' => [
                Advert::shortClassName() => 'Объявление',
                AdvertTemplet::shortClassName() => 'Шаблон объявления',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        if (is_file($this->fullName)) {
            unlink($this->fullName);
        }
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return "/uploaded/{$this->file_name}";
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return Yii::getAlias('@app/web/uploaded');
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return Yii::getAlias("@app/web/uploaded/{$this->file_name}");
    }

    /**
     * @param $owner
     * @param string $attribute
     * @return null|File
     */
    public static function upload($owner, $attribute = 'files')
    {
        $uploadedFile = UploadedFile::getInstance($owner, $attribute);

        $file = new self([
            'owner_id' => $owner->id,
            'owner_model_name' => $owner::shortClassName(),
            'file_name' => uniqid(time(), true) . '.' . $uploadedFile->extension,
            'origin_file_name' => $uploadedFile->name
        ]);
        if ($uploadedFile->saveAs("{$file->path}/{$file->file_name}") && $file->save()) {
            return $file;
        }

        return null;
    }
}
