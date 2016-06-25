<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

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

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSearchscore()
    {
        $this->layout = false;
        $tpl_val = [];
        $score_2016 = Yii::$app->request->post('score');
        $wl = Yii::$app->request->post('wl');
        $model = new \app\models\Yifenyidang();
        //2016成绩start
        if (!$score_obj_2016 = $model->find()->where(['score'=>$score_2016, 'wl'=>$wl, 'year'=>'2016'])->one()) {
            echo '你确定没有输错？';exit;
        }
        $tpl_val['score_2016'] = $score_obj_2016->score;
        $tpl_val['num_2016'] = $score_obj_2016->num;
        $tpl_val['num_total_2016'] = $score_obj_2016->num_total;
        //2016成绩end
        //2015成绩start
        $score_obj_2015 = $model->find()->where(['wl'=>$wl, 'year'=>'2015'])->andWhere(['>', 'num_total',$score_obj_2016->num_total])->one();
        $tpl_val['score_2015'] = $score_obj_2015->score-1;  //与2015年名次相当的成绩的分数
        $tpl_val['num_2015'] = $score_obj_2015->num;
        $tpl_val['num_total_2015'] = $score_obj_2015->num_total;
        //2015成绩end
        return $this->render('search_score', $tpl_val);
    }
}
