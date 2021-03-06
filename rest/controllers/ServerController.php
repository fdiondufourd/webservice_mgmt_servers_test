<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
//use yii\filters\auth\HttpBasicAuth;


class ServerController extends ActiveController
{
    public $modelClass = 'app\models\Servers';
    
    
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }
}