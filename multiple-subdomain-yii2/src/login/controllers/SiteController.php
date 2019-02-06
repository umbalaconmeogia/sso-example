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

        // Process URL parameter "returnUrl"
        $returnUrl = isset($_REQUEST['returnUrl']) ? $_REQUEST['returnUrl'] : null;
        if ($returnUrl) { // If login is requested from sub-system.
            // Remember returnUrl into session.
            \Yii::$app->session[$this->loginReturnUrlParam] = $returnUrl;
        } else {
            // Clear loginReturnUrl that saved in session if login from "login" system itself.
            $this->popLoginReturnUrl();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) { // Login successfully
            // Redirect to returnUrl if is requested login from other sub-system.
            $returnUrl = $this->popLoginReturnUrl();
            if ($returnUrl) {
                return $this->redirect($returnUrl);
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
        // Process URL parameter "returnUrl"
        $returnUrl = isset($_REQUEST['returnUrl']) ? $_REQUEST['returnUrl'] : null;

        Yii::$app->user->logout();

        // Clear session id key of all sub-domains in cookie.
        foreach ($_COOKIE as $key => $value) {
            if (strpos($key, \Yii::$app->params['sso']['sessIdPrefix']) !== FALSE) {
                echo "$key, " . \Yii::$app->params['sso']['domain'] . "<br />";
                setcookie($key, $value, time() - 3600, null, \Yii::$app->params['sso']['domain']);
            }
        };

        // Redirect to returnUrl if is requested login from other sub-system.
        if ($returnUrl) {
            return $this->redirect($returnUrl);
        } else {
            return $this->goHome();
        }
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

    public function actionTestSession()
    {
        $key = 'testSession';
        $setValue = Yii::$app->request->post($key);
        if ($setValue) {
            Yii::$app->session[$key] = $setValue;
        }

        return $this->render('testSession');
    }
}
