<?php

namespace microvideo\models;

use common\components\Utility;
use yii\base\Model;
use common\models\Video;

class VideoForm extends Model
{
    public $key;
    public $status;
    public $url;
    public $site_url;
    public $desc;
    public $length;
    public $regex_setting;
    public $cover_img;
    
    public $width;
    public $height;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['key', 'url', 'cover_img'], 'required'],
            [['add_time', 'pub_time', 'status', 'length', 'width', 'height', 'regex_setting', 'cover_img'], 'integer'],
            [['key', 'url', 'site_url', 'desc'], 'string']
        ];
    }

    public function save()
    {
        $video = Video::findOne(['key' => $this->key]);
        if (!$video) {
            $video = new Video();
            $video->setAttributes([
                'key'=>$this->key,
                'status'=>$this->status,
                'url'=>$this->url,
                'site_url'=>$this->site_url,
                'desc'=>$this->desc,
                'length'=>$this->length,
                'add_time'=>time(),
                'pub_time'=>time(),
                'regex_setting'=>$this->regex_setting,
                'cover_img'=>$this->cover_img,
                'width'=>$this->width,
                'height'=>$this->height,
            ]);
            if (!$video->save()) {
                $this->addErrors($video->getErrors());
                return false;
            }
        }
        return true;
    }
}