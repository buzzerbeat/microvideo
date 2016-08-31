<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/7/13
 * Time: 18:05
 */

namespace microvideo\controllers;

use common\components\Utility;
use common\models\Video;
use microvideo\models\MvVideo;
use microvideo\models\VideoFavForm;
use microvideo\models\VideoLikeForm;
use wallpaper\models\WpImage;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
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
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['fav',  'like', 'fav-list', 'like-list'],
        ];

        return $behaviors;
    }
    public function actionIndex()
    {

        //$cat = \Yii::$app->request->get('cat', 0);
        $tagSid = \yii::$app->request->get('tag', '');
        $from = \Yii::$app->request->get('from', '');
        $status = \Yii::$app->request->get('status', 0);
        $query =  MvVideo::find()
            //->leftJoin('mv_video_category_rel', '`mv_video_category_rel`.`video_id` = `mv_video`.`id`')
            ->where([
                'status' => empty($status) ? MvVideo::STATUS_ACTIVE : $status,
            ]);
        /* if ($cat) {
            $query->andWhere(['`mv_video_category_rel`.`category_id`' => $cat]);
        } */
        $query->orderBy('id desc');
        if(!empty($tagSid)){
        	$query->leftJoin('mv_video_tag_rel', '`mv_video_tag_rel`.`mv_video_id` = `mv_video`.`id`')
        	   ->andWhere(['`mv_video_tag_rel`.`mv_tag_id`' => Utility::id($tagSid)]);
        }
        else {
            $query->andWhere('create_time > ' . (time() - 86400*3));
            $query->orderBy('rank desc');
        }
        if (!empty($from)) {
            $query->andWhere(['like', 'key', $from]);

        }
        return new ActiveDataProvider([
            'query' => $query->orderBy('id desc')
        ]);
    }

    public function actionFavList() {
        $user = \Yii::$app->user->identity;
        $query =  MvVideo::find()
            ->leftJoin('mv_video_fav', '`mv_video_fav`.`mv_video_id` = `mv_video`.`id`')
            ->where([
                '`mv_video_fav`.`user_id`' => $user->id,
            ]);
        return new ActiveDataProvider([
            'query' => $query->orderBy('`mv_video_fav`.`time` desc')
        ]);
    }

    public function actionLikeList() {
        $user = \Yii::$app->user->identity;
        $query =  MvVideo::find()
            ->leftJoin('mv_video_like', '`mv_video_like`.`mv_video_id` = `mv_video`.`id`')
            ->where([
                '`mv_video_like`.`user_id`' => $user->id,
            ]);
        return new ActiveDataProvider([
            'query' => $query->orderBy('`mv_video_like`.`time` desc')
        ]);
    }

    public function actionView($sid)
    {
        return MvVideo::findOne(Utility::id($sid));
    }

    public function actionDecode()
    {
        \Yii::$app->response->format = Response::FORMAT_RAW;
		$code = \Yii::$app->request->post("code");
		$code = str_replace("\n", '', $code);
        exec("node " . __DIR__ . "/../../console/tt_video.js '" . $code . "'", $output);
        echo array_shift($output);

    }


    public function actionLike()
    {
        $likeForm = new VideoLikeForm();
        if ($likeForm->load(Yii::$app->getRequest()->post(), '') && $likeForm->like()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode(",", $likeForm->getFirstErrors())];
    }

    public function actionFav()
    {
        $favForm = new VideoFavForm();
        if ($favForm->load(Yii::$app->getRequest()->post(), '') && $favForm->fav()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode(",", $favForm->getFirstErrors())];

    }
}
