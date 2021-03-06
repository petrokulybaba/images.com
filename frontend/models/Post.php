<?php

namespace frontend\models;

use Yii;
use frontend\models\User;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
 * @property string $filename
 * @property string $description
 * @property int $created_at
 * @property int $complaints
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'filename' => 'Filename',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }
    
    /**
     * @return string
     */
    public function getImage()
    {
        return Yii::$app->storage->getFile($this->filename);;
    }
    
    /**
     * Get author of the post
     * @return User|null
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    /*
     * Like current post by given user
     * @param frontend\models\User $user
     */
    public function like(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $redis->sadd("post:{$this->getId()}:likes", $user->getId());
        $redis->sadd("user:{$user->getId()}:likes", $this->getId());
    }
    
    /*
     * Unlike current post by given user
     * @param frontend\models\User $user
     */
    public function unLike(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $redis->srem("post:{$this->getId()}:likes", $user->getId());
        $redis->srem("user:{$user->getId()}:likes", $this->getId());
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    /*
     * @return mixed
     */
    public function countLikes()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return $redis->scard("post:{$this->getId()}:likes");
    }
    
    /**
     * Check whether given user liked current post
     * @param frontend\models\User $user
     * @return integer
     */
    public function isLikedBy(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return $redis->sismember("post:{$this->getId()}:likes", $user->getId());
    }
    
    /**
     * Add complaint to post from given user
     * @param frontend\models\User $user
     * @return boolean
     */
    public function complain(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key = "post:{$this->getId()}:complaints";
        
        if (!$redis->sismember($key, $user->getId())) {
            $redis->sadd($key, $user->getId());
            $this->complaints++;
            return $this->save(false, ['complaints']);
        }
    }
    
    /**
     * @param \frontend\models\User $user
     * @return boolean
     */
    public function isReported(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return $redis->sismember("post:{$this->getId()}:complaints", $user->getId());
    }
    
    /**
     * Delete likes, comments and complaints of post
     * @return boolean
     */
    public function deleteLikesCommentsComplaints()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $likesKey = "post:{$this->id}:likes";
        $commentsKey = "post:{$this->id}:comments";
        $complaintsKey = "post:{$this->id}:complaints";
        
        if ($redis->del($likesKey) && $redis->del($commentsKey) && $redis->del($complaintsKey)) {
            return true;
        }
        return false; 
    }
}
