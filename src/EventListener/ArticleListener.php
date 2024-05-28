<?php
    namespace App\EventListener;

use App\CustomEvents\ArticleEvent;

    class ArticleListener
    {
        public function articleOpened(ArticleEvent $articleEvent)
        {
            $article=$articleEvent->getArticle();
            dump($article);
            die();
        }
    }