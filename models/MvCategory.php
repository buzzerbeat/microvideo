<?php

namespace microvideo\models;

use common\components\Utility;
use Yii;

/**
 * This is the model class for table "mv_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $keyword
 * @property integer $rank
 */
class MvCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mv_category';
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
            [['name', 'keyword'], 'required'],
            [['rank'], 'integer'],
            [['name', 'keyword'], 'string', 'max' => 255],
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
            'keyword' => 'Keyword',
            'rank' => 'Rank',
        ];
    }

    public function getSid() {
        return Utility::sid($this->id);
    }

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['rank'], $fields['keyword']);
        $fields[] = 'sid';
        return $fields;
    }
}
