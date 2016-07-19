<?php

namespace microvideo\models;

use Yii;

/**
 * This is the model class for table "mv_video_category_rel".
 *
 * @property integer $id
 * @property integer $video_id
 * @property integer $category_id
 */
class MvVideoCategoryRel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mv_video_category_rel';
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
            [['video_id', 'category_id'], 'required'],
            [['video_id', 'category_id'], 'integer'],
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
            'category_id' => 'Category ID',
        ];
    }
}
