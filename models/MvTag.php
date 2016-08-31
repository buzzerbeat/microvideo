<?php

namespace microvideo\models;

use Yii;
use common\components\Utility;
use microvideo\models\MvTagRel;

/**
 * This is the model class for table "mv_tag".
 *
 * @property integer $id
 * @property string $name
 */
class MvTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mv_tag';
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
            [['name'], 'string', 'max' => 40],
            [['count'], 'integer']
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
            'count' => 'count'
        ];
    }
    
    public function fields(){
    	$fields = [
    	   'sid',
    	   'name'
    	];
    	
    	return $fields;
    }
    
    public function getSid(){
    	return Utility::sid($this->id);
    }
    
    public function extraFields()
    {
        return ['subTags'];
    }
    
    public function getTags(){
        return $this->hasMany(MvTagRel::className(), ['tag_id'=>'id']);
    }

    public function getBelongTags(){
        return $this->hasMany(MvTagRel::className(), ['rel_tag_id'=>'id']);
    }
   
    public function getSubTags(){
        $ret = [];
        $belongTags = $this->belongTags;
        foreach($belongTags as $tag){
            $ar = MvTag::findOne($tag->tag_id);
            if(empty($ar)){
            	continue;
            }
            $ret[] = $ar;
        }

        $tags = $this->tags;
        foreach($tags as $tag){
            $ar = MvTag::findOne($tag->rel_tag_id);
            if(empty($ar)){
            	continue;
            }
            $ret[] = $ar;
        }
         
        return $ret;
    }

}
