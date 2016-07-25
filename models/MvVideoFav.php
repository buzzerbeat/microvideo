<?php

namespace microvideo\models;

use Yii;

/**
 * This is the model class for table "mv_video_fav".
 *
 * @property integer $id
 * @property integer $mv_video_id
 * @property integer $user_id
 * @property integer $fav
 * @property integer $time
 */
class MvVideoFav extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mv_video_fav';
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
            [['mv_video_id', 'user_id', 'fav', 'time'], 'required'],
            [['mv_video_id', 'user_id', 'fav', 'time'], 'integer'],
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
            'fav' => 'Fav',
            'time' => 'Time',
        ];
    }
}
