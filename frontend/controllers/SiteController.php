<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Cookie;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        
        $limit = Yii::$app->params['feedPostLimit'];
        $feedItems = $currentUser->getFeed($limit);
        
        return $this->render('index', [
            'currentUser' => $currentUser,
            'feedItems' => $feedItems,
        ]);
    }
    
    /**
     * About page
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    
    /**
     * Change language
     */
    public function actionLanguage()
    {
        $supportedLanguage = ['en-US', 'ua-UA'];
        $language = Yii::$app->request->post('language');
        if (isset($language) && in_array($language, $supportedLanguage)) {
            Yii::$app->language = $language;
            $languageCookie = new Cookie([
                'name' => 'language',
                'value' => $language,
                'expire' => time() + 60 * 60 * 24 * 30,
            ]);
            Yii::$app->response->cookies->add($languageCookie);
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
}
