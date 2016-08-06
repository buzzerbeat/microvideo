<?php

namespace microvideo\models;


use common\components\Utility;
use yii\base\Model;
use microvideo\models\MvVideo;

class MvVideoForm extends Model
{
    public $video_id;
    public $status;
    public $desc;
    public $title;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['video_id', 'title'], 'required'],
            [['create_time', 'update_time', 'status', 'video_id'], 'integer'],
            [['title', 'desc'], 'string']
        ];
    }

    public function save()
    {
        $mvVideo = MvVideo::findOne(['video_id' => $this->video_id]);
        if (!$mvVideo) {
            $mvVideo = new MvVideo();
            $mvVideo->setAttributes([
                'video_id'=>$this->video_id,
                'status'=>$this->status,
                'create_time'=>time(),
                'update_time'=>time(),
                'desc'=>$this->desc,
                'title'=>$this->title,
            ]);
            if (!$mvVideo->save()) {
                $this->addErrors($mvVideo->getErrors());
                return false;
            }
        }
        return true;
    }
}