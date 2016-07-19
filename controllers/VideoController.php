<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/7/13
 * Time: 18:05
 */

namespace microvideo\controllers;

use common\components\Utility;
use microvideo\models\MvVideo;
use wallpaper\models\WpImage;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\web\Response;

class VideoController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
//        $behaviors[] = [
//            'class' => 'yii\filters\HttpCache',
//            'only' => ['index'],
//            'lastModified' => function ($action, $params) {
//                $q = new \yii\db\Query();
//                return $q->from('random_cache')->max('updated_at');
//            },
//        ];


        return $behaviors;
    }
    public function actionIndex()
    {

        $cat = \Yii::$app->request->get('cat', 0);
        $from = \Yii::$app->request->get('from', '');
        $query =  MvVideo::find()
            ->leftJoin('mv_video_category_rel', '`mv_video_category_rel`.`video_id` = `mv_video`.`id`')
            ->where([
                'status' => MvVideo::STATUS_ACTIVE,
            ]);
        if ($cat) {
            $query->andWhere(['`mv_video_category_rel`.`category_id`' => $cat]);
        }
        if (!empty($from)) {
            $query->andWhere(['like', 'key', $from]);

        }
        return new ActiveDataProvider([
            'query' => $query->orderBy('id desc')
        ]);
    }


    public function actionView($sid)
    {
        return WpImage::findOne(Utility::id($sid));
    }

    public function actionDecode()
    {
        \Yii::$app->response->format = Response::FORMAT_RAW;
        $code = \Yii::$app->request->get("code");
        exec("node " . __DIR__ . "/../../console/tt_video.js '" . $code . "'", $output);
        echo array_shift($output);


    }
}