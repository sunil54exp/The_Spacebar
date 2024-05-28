<?php
namespace App\CustomEvents;

use App\Entity\Article;
use Symfony\Component\EventDispatcher\Event;

class ArticleEvent extends Event
{
    public const NAME='article.opened';
    protected $article;
    public function __construct(Article $article)
    {
        $this->article=$article;
    }

    public function getArticle()
    {
        return $this->article;
    }
}