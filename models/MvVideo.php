<?php

namespace microvideo\models;

use common\components\Utility;
use common\models\Video;
use Yii;

/**
 * This is the model class for table "mv_video".
 *
 * @property integer $id
 * @property integer $video_id
 * @property integer $status
 * @property string $title
 * @property string $desc
 * @property string $create_time
 * @property string $update_time
 */
class MvVideo extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const STATUS_MAP = [
        self::STATUS_INACTIVE => "不可用",
        self::STATUS_ACTIVE => "可用",
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mv_video';
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
            [['video_id', 'status', 'create_time', 'update_time'], 'integer'],
            [['video_id'], 'required'],
            [['title', 'desc'], 'string'],
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
            'status' => 'Status',
            'title' => 'Title',
            'desc' => 'Desc',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public function getRels() {
        return $this->hasMany(MvVideoKeywordRel::className(), ['video_id'=>'id']);
    }
    public function getKeywords() {
        return $this->hasMany(MvKeyword::className(), ['id' => 'keyword_id'])
            ->via('rels');
    }
    public function getCatRels() {
        return $this->hasMany(MvVideoCategoryRel::className(), ['video_id'=>'id']);
    }
    public function getCategories() {
        return $this->hasMany(MvCategory::className(), ['id' => 'category_id'])
            ->via('catRels');
    }

    public function getVideoCount() {
        return $this->hasOne(MvVideoCount::className(), ['video_id'=>'id']);
    }
	
    public function getCountPlayed() {
        if ($this->videoCount->played >= 10000) {
            return round($this->videoCount->played/10000, 1) . '万';
        }
        else {
    		return $this->videoCount->played . '';
        }
	}

	public function getCountLike() {
		return $this->videoCount->like . '';
	}

	public function getCountFav() {
		return $this->videoCount->fav;
	}

	public function getCountBury() {
		return $this->videoCount->bury . '';
	}

	public function getCountShare() {
		return $this->videoCount->share;
	}

    public function getIosAlert() {
        $rand = rand(0, 1);
        if ($rand == 0) {
            return [
                'code'=>'asdfasss' . rand(0, 999),
                'message'=>"哈哈哈？",
                'confirm' => '去给好评',
                'refuse' => '不想要',
                'link'=>'https://itunes.apple.com/cn/app/id1128648541?mt=8&at=1000l8vm',
            ]; 
        }
        return array();
    }

    public function getVideo() {
        return $this->hasOne(Video::className(), ['id'=>'video_id']);
    }

    public function getRelationVideos() {
        $cc = MvVideo::find()->limit(10)->orderBy('id DESC')->all();
        return $cc;
    }

    public function getSid() {
        return Utility::sid($this->id);
    }

    public function getElapsedTime() {
        return Utility::time_get_past($this->update_time);
    }

    public function extraFields()
    {
        $fields = [
            'relationVideos',
            'tags'
        ];
        return $fields;
    }

    public function fields()
    {
        $fields = [
            'sid',
            'title',
            'desc',
            'elapsedTime',
            'video',
            'countPlayed',
            'countLike',
            'countBury',
            'iosAlert',
        ];
        return $fields;
    }
    
    public function getTags()
    {
        return $this->hasMany(MvTag::className(), ['id' => 'mv_tag_id'])
        ->viaTable('mv_video_tag_rel', ['mv_video_id' => 'id']);
    }
}
