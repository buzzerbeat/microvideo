<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/7/25
 * Time: 15:23
 */

namespace microvideo\models;


use common\components\Utility;
use yii\base\Model;

class VideoLikeForm extends Model
{
    public $sid;
    private $userId;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['sid'], 'required'],
        ];
    }

    public function getId() {
        return Utility::id($this->sid);
    }

    public function like()
    {
        $this->userId = \Yii::$app->user->identity->id;
        $mvLike = MvVideoLike::find()->where([
            'mv_video_id' => $this->getId(),
            'user_id' => $this->userId,
            'like' => 1,
        ])->one();
        if (!$mvLike) {
            $mvLike = new MvVideoLike();
            $mvLike->mv_video_id = $this->getId();
            $mvLike->user_id = $this->userId;
            $mvLike->like = 1;
            $mvLike->time = time();
            if (!$mvLike->save()) {
                $this->addErrors($mvLike->getErrors());
                return false;
            }

            $vCount = MvVideoCount::findOne(['video_id'=>$this->getId()]);
            if (!$vCount) {
                $vCount = new MvVideoCount();
                $vCount->video_id = $this->getId();
                $vCount->like = 1;
                if (!$vCount->save()) {
                    $this->addErrors($vCount->getErrors());
                    return false;
                }
            } else {
                if (!$vCount->updateCounters(['like' => 1])) {
                    $this->addErrors($vCount->getErrors());
                    return false;
                }
            }
        }
        return true;
    }
}