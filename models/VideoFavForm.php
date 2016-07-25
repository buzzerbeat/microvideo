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

class VideoFavForm extends Model
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

    public function fav()
    {
        $this->userId = \Yii::$app->user->identity->id;
        $mvFav = MvVideoFav::find()->where([
            'mv_video_id' => $this->getId(),
            'user_id' => $this->userId,
            'fav' => 1,
        ])->one();
        if (!$mvFav) {
            $mvFav = new MvVideoFav();
            $mvFav->mv_video_id = $this->getId();
            $mvFav->user_id = $this->userId;
            $mvFav->fav = 1;
            $mvFav->time = time();
            if (!$mvFav->save()) {
                $this->addErrors($mvFav->getErrors());
                return false;
            }
            $vCount = MvVideoCount::findOne(['video_id'=>$this->getId()]);
            if (!$vCount) {
                $vCount = new MvVideoCount();
                $vCount->video_id = $this->getId();
                $vCount->fav = 1;
                if (!$vCount->save()) {
                    $this->addErrors($vCount->getErrors());
                    return false;
                }
            } else {
                if (!$vCount->updateCounters(['fav' => 1])) {
                    $this->addErrors($vCount->getErrors());
                    return false;
                }
            }
        }
        return true;
    }
}