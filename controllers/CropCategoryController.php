<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBasicAuth;
use Yii;

use app\models\CropCategory;

class CropCategoryController extends Controller{

    public $modelClass = 'app\models\CropCategory';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['OPTIONS', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'list-all'  => ['GET'],
                'view'   => ['GET'],
                'create' => ['OPTIONS', 'POST'],
                'create' => ['OPTIONS', 'POST'],
                'update' => ['OPTIONS', 'PUT'],
                'delete' => ['OPTIONS', 'DELETE'],
//                  'delete' => ['DELETE'],
            ]
        ];

        return $behaviors;
    }

    /**
     * @return ActiveDataProvider
     * Get all categories
     */
    public function actionListAll(){

        $data = new ActiveDataProvider([
            'query' => CropCategory::find(),
        ]);

        return $data;
    }

    /**
     *  View the specified resource
     */
    public function actionView() {
        $request = Yii::$app->request;
        $uid = $request->getQueryParam('uid');

        $crop_category = CropCategory::findOne($uid);

        if($crop_category == null){
            return ['status' => 404, 'message' => 'That record does not exist in our DB'];
        }
        return $crop_category;
    }

    /**
     *  Create a new resource
     * @param yii\web\Request $request
     */
    public function actionCreate() {
        //Get request object
        $request = Yii::$app->request;

        // get all parameters
        $params = $request->getQueryParams();

        $crop_category                   = new CropCategory();
        $crop_category->category_name    = $params['name'];
        $crop_category->category_description = $params['description'];
        $status = $crop_category->save();

        $response = $this->createResponse($status);
        return $response;
    }

    /**
     *  Delete the specified resource
     */
    public function actionUpdate() {
        $request = Yii::$app->request;
        $params  = $request->getQueryParams();
        $uid     = $params['uid'];

        $crop_category = CropCategory::findOne($uid);
        $crop_category->category_name        = $params['name'];
        $crop_category->category_description = $params['description'];
        $status = $crop_category->save();

        $response = $this->createResponse($status);
        return $response;
    }


    /**
     *  Delete the specified resource
     */
    public function actionDelete() {
        $request = Yii::$app->request;
        $params  = $request->getQueryParams();
        $uid     = $params['uid'];

        $crop_category = CropCategory::findOne($uid);
        $status = $crop_category->delete();

        $response = $this->createResponse($status);
        return $response;
    }

    public function createResponse($status){
        $response = ['status' => 1, 'message' => 'Success'];

        if ($status != true || $status != 1 ){
            $response['status']  = 0;
            $response['message'] = "There was a problem performing the specified operation";
        }

        return $response;
    }

}