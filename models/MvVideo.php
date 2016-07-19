<?php

namespace microvideo\models;

use common\components\Utility;
use common\models\Video;
use Yii;

/**
 * This is the model class for table "mv_video".
 *
 * @property integer $id
 * @property integer $video_id
 * @property integer $status
 * @property string $key
 * @property string $title
 * @property string $desc
 * @property string $source_url
 * @property string $create_time
 * @property string $update_time
 */
class MvVideo extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const STATUS_MAP = [
        self::STATUS_INACTIVE => "不可用",
        self::STATUS_ACTIVE => "可用",
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mv_video';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('mvDb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['video_id', 'status', 'create_time', 'update_time'], 'integer'],
            [['key'], 'required'],
            [['title', 'desc', 'source_url','key'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'video_id' => 'Video ID',
            'status' => 'Status',
            'key' => 'Key',
            'title' => 'Title',
            'desc' => 'Desc',
            'source_url' => 'Source Url',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public function getRels() {
        return $this->hasMany(MvVideoKeywordRel::className(), ['video_id'=>'id']);
    }
    public function getKeywords() {
        return $this->hasMany(MvKeyword::className(), ['id' => 'keyword_id'])
            ->via('rels');
    }
    public function getCatRels() {
        return $this->hasMany(MvVideoCategoryRel::className(), ['video_id'=>'id']);
    }
    public function getCategories() {
        return $this->hasMany(MvCategory::className(), ['id' => 'category_id'])
            ->via('catRels');
    }

    public function getVideo() {
        return $this->hasOne(Video::className(), ['id'=>'video_id']);
    }
    public function getSid() {
        return Utility::sid($this->id);
    }

    public function getElapsedTime() {
        return Utility::time_get_past($this->update_time);
    }

    public function fields()
    {
        $fields = [
            'sid',
            'title',
            'desc',
            'elapsedTime',
            'video',
            'keywords',
            'categories',
        ];
        return $fields;
    }
}
