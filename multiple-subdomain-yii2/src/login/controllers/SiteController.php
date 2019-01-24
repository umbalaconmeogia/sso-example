<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public $loginReturnUrlParam = 'loginReturnUrl';

    /**
     * Return loginReturnUrl that is saved in session, also delete it from session.
     * @return string
     */
    private function popLoginReturnUrl()
    {
        $loginUrl = \Yii::$app->session[$this->loginReturnUrlParam];
        unset(\Yii::$app->session[$this->loginReturnUrlParam]);
        return $loginUrl;
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        // If user accesses to site/login directly from browser.
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $loginReturnUrl = isset($_REQUEST['returnUrl']) ? $_REQUEST['returnUrl'] : null;
        if ($loginReturnUrl) { // If login is requested from sub-system.
            // Remember returnUrl into session.
            \Yii::$app->session[$this->loginReturnUrlParam] = $loginReturnUrl;
        } else {
            // Clear loginReturnUrl that saved in session if login from "login" system itself.
            $this->popLoginReturnUrl();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) { // Login successfully
            // Redirect to loginReturnUrl if is requested login from other sub-system.
            $loginReturnUrl = $this->popLoginReturnUrl();
            if ($loginReturnUrl) {
                return $this->redirect($loginReturnUrl);
            } else {
                return $this->goBack();
            }
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
