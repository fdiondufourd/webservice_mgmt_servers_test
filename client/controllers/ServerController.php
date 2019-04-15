<?php

namespace app\controllers;

use Yii;
use app\models\Servers;
//use app\models\ServersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\helpers\Json;

use yii\filters\AccessControl;


/**
 * ServerController implements the CRUD actions for Servers model.
 */
class ServerController extends Controller
{
  //  public $api_key = 'fred-access-token';
    
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
               // 'only' => ['logout'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }
    
    private function call_api_webservice($method, $url, $data = FALSE)
    {
        $supported_methods = ['POST', 'GET', 'PATCH', 'DELETE'];
        if ( ! $method || ! $url || ! in_array($method, $supported_methods))
            return FALSE;
        
        $client = new Client(['baseUrl' => 'https://mgmtservers.dev/']);
        
        $response = $client->createRequest()
            ->setUrl($url)
            ->setMethod($method)
            ->setData($data)
            ->addHeaders(['content-type' => 'application/json'])
            ->addHeaders(['Authorization' => 'Bearer ' . Yii::$app->user->identity->accessToken])
            ->send();
         $data = Json::decode($response->content);
        
        if (isset($data['name']) && $data['name'] == 'Unauthorized')
            return ['status' => 'error', 'data' => $data['message']];
        
        return ['status' => 'authorized', 'data' => $data];
    }

    /**
     * Lists all Servers models.
     * @return mixed
     * @throws ForbiddenHttpException if the user not authorized
     */
    public function actionIndex()
    {
        $response = $this->call_api_webservice('GET', 'servers');
        
        if ($response['status'] == 'error')
            throw new \yii\web\ForbiddenHttpException($response['data']);

            //throw new NotFoundHttpException($response['data']);

        $data = $response['data'];
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'key' => 'id',
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Servers model.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the user not authorized
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
     /**
     * Remove special characters, replace spaces by underscores and lower case
     * NOTE : this function should be in a helper or something
     * @param string $friendly_name
     * @return string
     */
     private function create_internal_name($friendly_name) {
        $friendly_name = str_replace(' ', '_', $friendly_name); 
        $friendly_name = preg_replace('/[^A-Za-z0-9\_]/', '', $friendly_name); 
    
        $internal_name = preg_replace('/_+/', '_', $friendly_name);
        
        return strtolower($internal_name);
    }

    /**
     * Creates a new Servers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Servers();
                
        if ($model->load(Yii::$app->request->post())) 
        {
            $clean_data = ArrayHelper::toArray($model);
            $clean_data['internal_name'] = $this->create_internal_name($clean_data['friendly_name']) . '.seedboxtest.' . strtotime('now');
            $response = $this->call_api_webservice('POST', 'servers', $clean_data);
             
            if ($response['status'] == 'error')
                throw new \yii\web\ForbiddenHttpException($response['data']);

            $data = $response['data'];

             if ( ! isset($data['id']))
                return $this->render('create', [
                    'model' => $model,
                ]);
            else
                return $this->redirect(['view', 'id' => $data['id']]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Servers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the user not authorized
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
             
        if ($model->load(Yii::$app->request->post())) {
            $clean_data = ArrayHelper::toArray($model);
    
             $response = $this->call_api_webservice('PATCH', 'servers/' . $id, $clean_data);
             
             if ($response['status'] == 'error')
                throw new \yii\web\ForbiddenHttpException($response['data']);

             $data = $response['data'];

             if ( ! isset($data['id']))
                return $this->render('update', [
                    'model' => $model,
                ]);
            else
                return $this->redirect(['view', 'id' => $data['id']]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Servers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the user not authorized
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $response = $this->call_api_webservice('DELETE', 'servers/' . $id);
        
        if ($response['status'] == 'error')
            throw new \yii\web\ForbiddenHttpException($response['data']);

        $data = $response['data'];
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the Servers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Servers the loaded model
     * @throws ForbiddenHttpException if the user not authorized
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $response = $this->call_api_webservice('GET', 'servers/' . $id);

        if ($response['status'] == 'error')
            throw new \yii\web\ForbiddenHttpException($response['data']);

        $data = $response['data'];
        
        $one_server = new Servers();
        $one_server->attributes = $data;
        
        if (isset($data['id'])) {
            return $one_server;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
