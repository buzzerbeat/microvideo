<?php

namespace microvideo\models;

use Yii;

/**
 * This is the model class for table "mv_video_like".
 *
 * @property integer $id
 * @property integer $mv_video_id
 * @property integer $user_id
 * @property integer $like
 * @property integer $time
 */
class MvVideoLike extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mv_video_like';
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
            [['mv_video_id', 'user_id', 'like', 'time'], 'required'],
            [['mv_video_id', 'user_id', 'like', 'time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mv_video_id' => 'Mv Video ID',
            'user_id' => 'User ID',
            'like' => 'Like',
            'time' => 'Time',
        ];
    }
}
