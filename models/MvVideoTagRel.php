<?php

namespace microvideo\models;

use Yii;

/**
 * This is the model class for table "mv_video_tag_rel".
 *
 * @property integer $id
 * @property integer $mv_video_id
 * @property integer $mv_tag_id
 */
class MvVideoTagRel extends \microvideo\models\MvVideo
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mv_video_tag_rel';
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
            [['mv_video_id', 'mv_tag_id'], 'required'],
            [['mv_video_id', 'mv_tag_id'], 'integer'],
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
            'mv_tag_id' => 'Mv Tag ID',
        ];
    }
}
