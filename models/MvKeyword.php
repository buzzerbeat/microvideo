<?php

namespace microvideo\models;

use common\components\Utility;
use Yii;

/**
 * This is the model class for table "mv_keyword".
 *
 * @property integer $id
 * @property string $name
 * @property integer $rank
 */
class MvKeyword extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mv_keyword';
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
            [['name'], 'required'],
            [['rank'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'rank' => 'Rank',
        ];
    }

    public function getSid() {
        return Utility::sid($this->id);
    }

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['id'], $fields['rank']);
        $fields[] = 'sid';
        return $fields;
    }
}
