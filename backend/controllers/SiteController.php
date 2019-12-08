<?php
namespace backend\controllers;

use backend\models\FileUpload;
use common\models\Resource;
use common\models\User;
use common\models\UserRes;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['logout','login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [ 'statisticuser','statisticcontent','content','delivery',
                            'contentcreate','contentupdate','contentdelete','contentone',
                            'deliveryok','deliveryremove'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public  function actionContent(){
        $query=Resource::find();
        $dataProvaider=new ActiveDataProvider([
            'query'=>$query,
        ]);
        return $this->render('content',[
                'dataProvaider'=>$dataProvaider,
            ]);
    }

    public function actionContentcreate(){
        $model=new Resource();
        $m_file=new FileUpload();
        if ($model->load(Yii::$app->request->post()) ) {
            $file=UploadedFile::getInstance($m_file,'file');
            $model->saveFile($m_file->uploadFile($file));
            $model->save();
            return $this->redirect(['content']);
        } else {
            return $this->render('contentcreate', [
                'model' => $model,
                'm_file'=>$m_file,
            ]);
        }
    }

    public function actionContentupdate($id){
        $model=$this->findContent($id);
        $m_file=new FileUpload();
        if ($model->load(Yii::$app->request->post()) ) {
            if(file_exists(\Yii::getAlias('@backend').'/web/resource/'.$model->way))
                unlink(\Yii::getAlias('@backend').'/web/resource/'.$model->way);
            $file=UploadedFile::getInstance($m_file,'file');
            if($file!=NULL)
                $model->saveFile($m_file->uploadFile($file));
            $model->save();
            return $this->redirect(['content']);
        } else {
            return $this->render('contentcreate', [
                'model' => $model,
                'm_file'=>$m_file,
            ]);
        }
    }

    public function actionContentdelete($id)
    {
        $file=$this->findContent($id);var_dump(\Yii::getAlias('@backend/web').'resource/'.$file->way);
       /* if(file_exists(\Yii::getAlias('@backend/web').'resource/'.$file->way))
        {unlink(\Yii::getAlias('@backend/web').'resource/'.$file->way);
            $file->delete();}*/

        return $this->redirect(['content']);
    }

    protected function findContent($id)
    {
        if(($model=Resource::findOne($id))!==null)
        {
            return $model;
        }
        else{
            throw new NotFoundHttpException('The requested page does not exits.');
        }
    }

    public function actionContentone($id){
        $model=$this->findContent($id);
        return $this->render('contentone',[
            'model'=>$model,
        ]);
    }

    public function actionStatisticuser(){
        $query=User::find()->where('id != :id', ['id'=>1]);
        $dataProvaider=new ActiveDataProvider([
            'query'=>$query,
            'sort'=> ['defaultOrder' => ['kol' => SORT_DESC]]
        ]);
        return $this->render('statisticuser',[
            'dataProvaider'=>$dataProvaider,
        ]);
    }

    public function actionStatisticcontent(){
        $query=Resource::find();
        $dataProvaider=new ActiveDataProvider([
            'query'=>$query,
            'sort'=> ['defaultOrder' => ['kol' => SORT_DESC]],
            'pagination'=>false,
        ]);

        $gen=new Query();
        $gen->select(['ganre','SUM(kol)'])->from(['resource'])->groupBy(['ganre'])->orderBy('SUM(kol) DESC');
        $dataProvider=new ActiveDataProvider([
            'query'=>$gen,
        ]);

        return $this->render('statisticcontent',[
            'dataProvaider'=>$dataProvaider,
            'dataProvider'=>$dataProvider
        ]);
    }

    public function actionDelivery(){
        $query=UserRes::find()->where(['request'=>0]);
        $dataProvaider=new ActiveDataProvider([
            'query'=>$query,
        ]);
        return $this->render('delivery',[
            'dataProvaider'=>$dataProvaider,
        ]);
    }

    public function actionDeliveryok($id, $idd){
        $model=UserRes::find()->where(['id_user'=>$id,'id_res'=>$idd])->one();
        $model->request=1;
        $model->save();
        return $this->redirect(['delivery']);
    }

    public function actionDeliveryremove($id, $idd){
        $model=UserRes::find()->where(['id_user'=>$id,'id_res'=>$idd])->one();
        $model->delete();
        return $this->redirect(['delivery']);
    }
}
