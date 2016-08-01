<?php

namespace microvideo\models;

use Yii;

/**
 * This is the model class for table "mv_tag_rel".
 *
 * @property integer $id
 * @property integer $tag_id
 * @property integer $rel_tag_id
 */
class MvTagRel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mv_tag_rel';
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
            [['tag_id', 'rel_tag_id'], 'required'],
            [['tag_id', 'rel_tag_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tag_id' => 'Tag ID',
            'rel_tag_id' => 'Rel Tag ID',
        ];
    }
}
