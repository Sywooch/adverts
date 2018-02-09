<?php

namespace app\modules\core\models\ar;

use app\modules\adverts\models\ar\Advert;
use app\modules\adverts\models\ar\AdvertTemplet;
use app\modules\core\db\ActiveRecord;
use Yii;
use yii\web\UploadedFile;
use app\modules\core\validators\FilesLimitValidator;

/**
 * This is the model class for table "file".
 *
 * @property integer $owner_id
 * @property string $owner_model_name
 * @property string $file_name
 * @property string $origin_file_name
 * @property string $deleted_at
 * @property string $vk_server
 * @property string $vk_photo
 * @property string $vk_hashs
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
            [['file_name', 'origin_file_name', 'vk_server', 'vk_photo', 'vk_hash'], 'string', 'max' => 128],
            ['owner_id', 'validateFilesLimit'],
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
            'vk_server' => Yii::t('app', 'VK сервер'),
            'vk_photo' => Yii::t('app', 'VK фото'),
            'vk_hash' => Yii::t('app', 'VK хеш'),
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
     * Validates maximum count of related files.
     * @param string $attribute
     */
    public function validateFilesLimit($attribute)
    {
        $count = self::find()->where([
            'owner_id' => $this->owner_id,
            'owner_model_name' => $this->owner_model_name
        ])->count();
        $modelName = ActiveRecord::getFullClassName($this->owner_model_name);;
        $maxCount = $modelName::getMaxFilesCount();

        if ($count >= $maxCount) {
            $this->addError($attribute, Yii::t('app', 'Максимально допустимое количество файлов - {count}', [
                'count' => $maxCount
            ]));
        }
    }

    /**
     * Uploads file and attaches it to the model.
     * @param ActiveRecord $owner
     * @param string $attribute
     * @return File
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
        $file->save() && $uploadedFile->saveAs("{$file->path}/{$file->file_name}");
        return $file;
    }
}
