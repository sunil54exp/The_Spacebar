<?php

namespace App\CustomEvents;

use App\Entity\Article;
use Symfony\Component\EventDispatcher\Event;

class NewEvent extends Event
{
    const NAME = "new.custom.event";

    private $article;
    public function __construct(Article $article)
    {
        $this->article=$article;
    }

    public function getNewArticle()
    {
        return $this->article;
    }
}
