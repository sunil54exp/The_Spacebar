<?php

namespace App\Controller;

use App\CustomEvents\ArticleEvent;
use App\CustomEvents\NewEvent;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Service\MarkdownHelper;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nexy\Slack\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ArticleController extends AbstractController
{
    private $isDebug;
    public function __construct(bool $isDebug)
    {
        $this->isDebug = $isDebug;
    }
    
    /**
     * @Route("/article",name="app_homepage")
     */
    public function homepage(ArticleRepository $repository): Response
    {

        // echo base64_encode(file_get_contents('https://www.ekathimerini.com/wp-content/uploads/2023/09/ID_front.jpg'));

        
        // $articles = $repository->findBy([], ['publishedAt' => 'DESC']);
        $articles = $repository->findAllPublishedOrderedByNewest();
        return $this->render('article/homepage.html.twig', [
            'articles'=>$articles
        ]);
        // return new Response("hello article");
    }

     /**
     * @Route("/news/{slug}",name="article_show")
     */
    public function show(Article $article, SlackClient $slack,CommentRepository $commentRepository,EventDispatcherInterface $eventDispatcher)
    {
        $articleEvent=new NewEvent($article);
//        $eventDispatcher->dispatch(NewEvent::NAME,$articleEvent);

        if ($article->getSlug() === 'khaaaaaan') {
            $slack->sendMessage('Kahn', 'Ah, Kirk, my old friend...');
        }
        
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

     /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart")
     */
    public function toggleArticleHeart(Article $article, LoggerInterface $logger, EntityManagerInterface $em)
    {
        $article->incrementHeartCount();
        $em->flush();
        $logger->info("written into log");
        return $this->json(['hearts' => $article->getHeartCount()]);
    }

     /**
     * @Route("/admin/article/new")
     */
    public function new(EntityManagerInterface $em)
    {
        die('todo');
        $article = new Article();
        $article->setTitle('Why Asteroids Taste Like Bacon')
            ->setSlug('why-asteroids-taste-like-bacon-'.rand(100, 999))
            ->setContent(<<<EOF
Spicy **jalapeno bacon** ipsum dolor amet veniam shank in dolore. Ham hock nisi landjaeger cow,
lorem proident [beef ribs](https://baconipsum.com/) aute enim veniam ut cillum pork chuck picanha. Dolore reprehenderit
labore minim pork belly spare ribs cupim short loin in. Elit exercitation eiusmod dolore cow
**turkey** shank eu pork belly meatball non cupim.
Laboris beef ribs fatback fugiat eiusmod jowl kielbasa alcatra dolore velit ea ball tip. Pariatur
laboris sunt venison, et laborum dolore minim non meatball. Shankle eu flank aliqua shoulder,
capicola biltong frankfurter boudin cupim officia. Exercitation fugiat consectetur ham. Adipisicing
picanha shank et filet mignon pork belly ut ullamco. Irure velit turducken ground round doner incididunt
occaecat lorem meatball prosciutto quis strip steak.
Meatball adipisicing ribeye bacon strip steak eu. Consectetur ham hock pork hamburger enim strip steak
mollit quis officia meatloaf tri-tip swine. Cow ut reprehenderit, buffalo incididunt in filet mignon
strip steak pork belly aliquip capicola officia. Labore deserunt esse chicken lorem shoulder tail consectetur
cow est ribeye adipisicing. Pig hamburger pork belly enim. Do porchetta minim capicola irure pancetta chuck
fugiat.
EOF
        );
        if (rand(1, 10) > 2) {
            $article->setPublishedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
        }

        $article->setAuthor('Sunil')
        ->setHeartCount(rand(5, 100))
        ->setImageFilename('asteroid.jpeg');
        $em->persist($article);
        $em->flush();

        return new Response(sprintf(
            'Hiya! New Article id: #%d slug: %s',
            $article->getId(),
            $article->getSlug()
        ));
    }
}
