<?php
/* @var $this yii\web\View */
/* @var $post frontend\model\Post */
/* @var $currentUser frontend\models\User */
/* @var $comments frontend\modules\post\models\Comment */
/* @var $countComments frontend\modules\post\models\Comment */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;

$this->title = Yii::t('post' ,'Post');
?>
<div class="page-posts no-padding">
    <div class="row">
        <div class="page page-post col-sm-12 col-xs-12 post-82">


            <div class="blog-posts blog-posts-large">

                <div class="row">

                    <!-- feed item -->
                    <article class="post col-sm-12 col-xs-12">                                            
                        <div class="post-meta">
                            <div class="post-title">
                                <img src="<?php echo $post->user->getPicture(); ?>" class="author-image" />
                                <div class="author-name">
                                    <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => $post->user->getNickname()]); ?>">
                                        <?php echo $post->user->username; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="post-type-image">
                            <img src="<?php echo $post->getImage(); ?>" alt="">
                        </div>
                        <div class="post-description">
                            <p><?php echo Html::encode($post->description); ?></p>
                        </div>
                        <div class="post-bottom">
                            <div class="post-likes">
                                <i class="fa fa-lg fa-heart-o"></i>
                                <span class="likes-count"><?php echo $post->countLikes(); ?></span>
                                &nbsp;&nbsp;&nbsp;
                                <a href="#" class="btn btn-default button-like <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "display-none" : ""; ?>" data-id="<?php echo $post->id; ?>">
                                    <?php echo Yii::t('post', 'Like'); ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
                                </a>
                                <a href="#" class="btn btn-default button-unlike <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "" : "display-none"; ?>" data-id="<?php echo $post->id; ?>">
                                    <?php echo Yii::t('post', 'Unlike'); ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
                                </a>
                            </div>
                            <div class="post-comments">
                                <?php echo ($countComments) ? Yii::t('post', '{countComments} Comments', [
                                    'countComments' => $countComments,
                                ]) : Yii::t('post', '0 Comments'); ?>
                            </div>
                            <div class="post-date">
                                <span><?php echo Yii::$app->formatter->asDatetime($post->created_at); ?></span>    
                            </div>
                            <?php if ($currentUser && $currentUser->id != $post->user_id): ?>
                                <div class="post-report">
                                    <?php if (!$post->isReported($currentUser)): ?>
                                        <a href="#" class="btn btn-default button-complain" data-id="<?php echo $post->id; ?>">
                                            <?php echo Yii::t('post', 'Report post'); ?> <i class="fa fa-cog fa-spin fa-fw icon-preloader" style="display:none"></i>
                                        </a>
                                    <?php else: ?>
                                        <p><?php echo Yii::t('post', 'Post has been reported'); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                    <!-- feed item -->

                    <div class="col-sm-12 col-xs-12">
                        <br>
                        <?php if($currentUser && $currentUser->id == $post->user_id): ?>
                            <a href="<?php echo Url::to(['/post/default/delete', 'id' => $post->id]); ?>" class="btn btn-danger">
                                <?php echo Yii::t('post', 'Delete post'); ?>
                            </a>
                        <?php endif; ?>
                        &nbsp;&nbsp;&nbsp;
                        <a href="<?php echo Url::to(['/post/comment/create', 'post_id' => $post->id]); ?>" class="btn btn-default">
                            <?php echo Yii::t('post', 'Create comment'); ?>
                        </a>
                    </div>
                    
                    <?php if ($comments): ?>
                        <div class="col-sm-12 col-xs-12">

                            <div class="comments-post">

                                <div class="single-item-title"></div>
                                <div class="row">
                                    <ul class="comment-list">

                                        <?php foreach ($comments as $item): ?>
                                            <!-- comment item -->
                                            <li class="comment">
                                                <div class="comment-user-image">
                                                    <img src="<?php echo $item->user->getPicture(); ?>"  width="80" height="80">
                                                </div>
                                                <div class="comment-info">
                                                    <h4 class="author">
                                                        <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => $item->user->getNickname()]); ?>">
                                                            <?php echo $item->user->username; ?>
                                                        </a>
                                                        <span>(<?php echo Yii::$app->formatter->asDatetime($item->created_at); ?>)</span>
                                                    </h4>
                                                    <p><?php echo Html::encode($item->text); ?></p>
                                                    <?php if ($currentUser && $currentUser->id == $item->user_id): ?>
                                                        <a href="<?php echo Url::to(['/post/comment/update', 'comment_id' => $item->id, 'post_id' => $post->id]); ?>"><?php echo Yii::t('post', 'Update'); ?></a>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <a href="<?php echo Url::to(['/post/comment/delete', 'comment_id' => $item->id, 'post_id' => $post->id]); ?>"><?php echo Yii::t('post', 'Delete'); ?></a>
                                                        <br>
                                                    <?php elseif ($currentUser && $currentUser->id == $post->user_id): ?>
                                                        <a href="<?php echo Url::to(['/post/comment/delete', 'comment_id' => $item->id, 'post_id' => $post->id]); ?>"><?php echo Yii::t('post', 'Delete'); ?></a>
                                                        <br>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                            <!-- comment item -->
                                        <?php endforeach; ?>


                                    </ul>
                                </div>

                            </div>
                            <!-- comments-post -->
                        
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
]);

$this->registerJsFile('@web/js/complaints.js', [
    'depends' => JqueryAsset::className(),
]);
