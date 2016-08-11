<?php

namespace microvideo\models;

use Yii;

/**
 * This is the model class for table "mv_comment".
 *
 * @property integer $id
 * @property integer $comment_id
 * @property integer $status
 * @property integer $dig
 * @property integer $is_hot
 */
class MvComment extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    
    const STATUS_MAP = [
        self::STATUS_INACTIVE => "不可用",
        self::STATUS_ACTIVE => "可用"
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mv_comment';
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
            [['comment_id', 'mv_video_id'], 'required'],
            [['comment_id', 'status', 'dig', 'is_hot', 'mv_video_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comment_id' => 'Comment ID',
            'mv_video_id' => 'mvVideo Id',
            'status' => 'Status',
            'dig' => 'Dig',
            'is_hot' => 'Is Hot',
        ];
    }
}
