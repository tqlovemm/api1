<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\helpers\Response;

class ThreadController extends ActiveController
{
    public $modelClass = 'app\modules\v1\models\Thread';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // token 验证  请按需开启
        // $behaviors['authenticator'] = [
        //     'class' => CompositeAuth::className(),
        //     'authMethods' => [
        //         QueryParamAuth::className(),
        //     ],
        // ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        // 注销系统自带的实现方法
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        return $actions;
    }

    public function actionIndex()
    {
        $modelClass = $this->modelClass;
        $query = $modelClass::find();
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function actionCreate()
    {
        $model = new $this->modelClass();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $images = $model->image_path;
        $Mpath = array();
        $images = explode(',',$images);
        for($i=0;$i<count($images);$i++){

            $pathStr = "uploads/thread/".date("Ymd");
            if ( !file_exists( $pathStr ) ) {
                if ( !mkdir( $pathStr , 0777 , true ) ) {
                    return false;
                }
            }
            $savePath = $pathStr.'/'.time().rand(1,10000).'.jpg';
            file_put_contents($savePath,base64_decode($images[$i]));
            array_push($Mpath,'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].'/'.$savePath);

        }
        $model->image_path = json_encode($Mpath);

        if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }


        Response::show('202','保存成功');

    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }
        return $model;
    }

    public function actionDelete($id)
    {
        if($this->findModel($id)->delete()){

            Response::show('202','删除成功');

        }
    }

    public function actionView($id)
    {
        return $this->findModel($id);

    }

    protected function findModel($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
