<?php
namespace frontend\controllers;

use common\models\Resource;
use common\models\User;
use common\models\UserRes;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\NotFoundHttpException;

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
                        'actions' => ['signup','login'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout','content','delivery','contentone','deliverysend'],
                        'allow' => true,
                        'roles' => ['user'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
            $m=User::find()->where(['username'=>$model->username])->one();
            $m->kol++;
            $m->save();
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

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. ');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
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

    public function actionDelivery(){
        $query=UserRes::find()->where(['id_user'=>Yii::$app->user->id,'request'=>1]);
        $dataProvaider=new ActiveDataProvider([
            'query'=>$query,
        ]);
        return $this->render('delivery',[
            'dataProvaider'=>$dataProvaider,
        ]);
    }

    public function actionDeliverysend($id){
        $model=new UserRes();
        $model->request=0;
        $model->id_res=$id;
        $model->id_user=Yii::$app->user->id;
        $model->save();
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
        $model->kol++;
        $model->save();
        return $this->render('contentone',[
            'model'=>$model,
        ]);
    }

    /*public function actionRole(){
        $role = Yii::$app->authManager->createRole('admin');
        $role->description = 'Администратор';
        Yii::$app->authManager->add($role);

        $userRole = Yii::$app->authManager->getRole('admin');
        Yii::$app->authManager->assign($userRole, 8);

        $role_u = Yii::$app->authManager->createRole('user');
        $role_u->description = 'Пользователь';
        Yii::$app->authManager->add($role_u);

        $userRole_u = Yii::$app->authManager->getRole('user');
        Yii::$app->authManager->assign($userRole_u, 9);

        return 123;
    }*/
}
