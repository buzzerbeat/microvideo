<?php

namespace microvideo\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use microvideo\models\MvTag;
use common\components\Utility;
/**
 * CategoryController implements the CRUD actions for AlbumTag model.
 */
class TagController extends Controller
{
    public $modelClass = 'microvideo\models\Tag';
    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => MvTag::find()
        ]);
    }
    
    public function actionView($sid){
        return MvTag::findOne(Utility::id($sid));
    }

}
