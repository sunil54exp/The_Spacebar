<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentAdminController extends AbstractController
{
    /**
     * @Route("/admin/comment", name="app_comment_admin")
     */
    public function index(CommentRepository $repository,Request $request, PaginatorInterface $paginator,EntityManagerInterface $em): Response
    {
        // $stmt=$em->getConnection()->prepare("select * from Comment");
        // $stmt->execute();
        // dd($stmt->fetchAll());

        $comments = $repository->findBy([], ['createdAt' => 'DESC']);
        $q = $request->query->get('q');
        $queryBuilder = $repository->getWithSearchQueryBuilder($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        
        return $this->render('comment_admin/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

   
}
