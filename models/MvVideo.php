<?php

namespace microvideo\models;

use backend\models\microvideo\MvTag;
use common\components\Utility;
use common\models\Video;
use common\models\ConfigInfo;
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
    const STATUS_DELETE = 99;

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
            [['video_id', 'status', 'create_time', 'update_time', 'rank','review'], 'integer'],
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
        $vcount = MvVideoCount::findOne(['video_id'=>$this->id]);
        if (empty($vcount)) {
            $vcount = new MvVideoCount;
            $vcount->video_id = $this->id;
            $vcount->save();
        }
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
        $id = '1142521176';
        $minfo = ConfigInfo::getMobileInfo();
        $ids = array(
            'xiguashipin'=>'1142521176',
            'chaokuaishipin'=>'1142177175',
            'feichangshipin3'=>'1147641462',
            'jipinshipin'=>'1147955239',
            'chengrenzhimei'=>'1128746673',
            'kanpianshenqi'=>'1149339048',
            'mmplayer'=>'1153645000',
            'xianfengyingyin'=>'1151488394',
            'zerovideo'=>'1150856335',
            'qplayer'=>'1153369190',
        );
        if (!empty($minfo['app']) && !empty($ids[$minfo['app']])) {
            $id = $ids[$minfo['app']];
        }
        /*if (2 == $this->review && $minfo['app'] == 'kanpianshenqi') {
            $rand = [
                ['name'=>'成人之美', 'id'=>'1128746673'],
                ['name'=>'超快视频', 'id'=>'1142177175'],
                ['name'=>'非常影视', 'id'=>'1147641462'],
                ['name'=>'壁纸真棒', 'id'=>'1129840238'],
                ['name'=>'轻松一刻', 'id'=>'1034156676'],
                ['name'=>'西西视频', 'id'=>'1142521176'],
            ];
            $info = $rand[rand(0, 2)];
            //$info = $rand[5];
            return [
                'code'=>'review2',
                'message'=>"看劲爆大片，请下载" . $info['name'] . "！\n下载完毕后给五星好评，即可解锁全部劲爆功能！",
                //'message'=>"看劲爆大片，请下载" . $info['name'] . "！",
                'confirm' => '去下载',
                'refuse' => '不看了',
                'link'=>'https://itunes.apple.com/cn/app/id' . $info['id'] . '?mt=8&at=1000l8vm',
            ]; 

        }*/
        if (2 == $this->review) {
            $randnum = rand(0, 5);
            if ($randnum <= 5) {
                return [
                    'code'=>'review2',
                    'message'=>"离精彩电影只差一步了！\n给五星好评解锁观看！\n评论需要30字以上！",
                    'confirm' => '去给好评',
                    'refuse' => '不看了',
                    'link'=>'https://itunes.apple.com/cn/app/id' . $id . '?mt=8&at=1000l8vm',
                ]; 
            }
            else {
                $rand = [
                    ['name'=>'壁纸真棒', 'id'=>'1129840238'],
                    ['name'=>'壁纸大全', 'id'=>'1132934170'],
                    ['name'=>'轻松一刻', 'id'=>'1034156676'],
                ];
                $info = $rand[rand(0, 2)];
                return [
                    'code'=>'review2',
                    //'message'=>"看劲爆大片，请下载" . $info['name'] . "！\n下载完毕后给五星好评，即可解锁本软件全部劲爆功能！",
                    'message'=>"看劲爆大片，先请下载" . $info['name'] . "！\n下载完毕后重开本APP，即可解锁本软件全部劲爆功能！",
                    'confirm' => '去下载',
                    'refuse' => '不看了',
                    'link'=>'https://itunes.apple.com/cn/app/id' . $info['id'] . '?mt=8&at=1000l8vm',
                ]; 
            }
        }
        return array();
    }

    public function getVideo() {
        return $this->hasOne(Video::className(), ['id'=>'video_id']);
    }

    public function getRelationVideos() {
        $tags = $this->tags;
        $tagId = [];
        foreach($tags as $tag){
        	$tagId[] = $tag->id;
        }
        $cc = MvVideo::find()
            ->leftJoin('mv_video_tag_rel', '`mv_video_tag_rel`.mv_video_id = `mv_video`.id')
            ->where(['`mv_video_tag_rel`.`mv_tag_id`' => $tagId])
            ->limit(20)
            ->orderBy('rank DESC')
            ->all();
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
            'tags',
            'test1'
        ];
        return $fields;
    }

    public function getTest1() {
        return rand(1, 10);
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
