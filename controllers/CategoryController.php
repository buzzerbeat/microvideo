<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/7/13
 * Time: 19:04
 */

namespace microvideo\controllers;
use yii\rest\ActiveController;

class CategoryController extends ActiveController
{
    public $modelClass = 'microvideo\models\MvCategory';
}