<?php

namespace microvideo\models;

use Yii;

/**
 * This is the model class for table "mv_video_count".
 *
 * @property integer $video_id
 * @property integer $like
 * @property integer $dig
 * @property integer $played
 * @property integer $bury
 * @property integer $share
 */
class MvVideoCount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mv_video_count';
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
            [['like', 'dig', 'played', 'bury', 'share'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'video_id' => 'Video ID',
            'like' => 'Like',
            'dig' => 'Dig',
            'played' => 'Played',
            'bury' => 'Bury',
            'share' => 'Share',
        ];
    }
}
