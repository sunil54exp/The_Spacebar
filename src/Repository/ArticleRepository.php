<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    private function addIsPublishedQueryBuilder(ORMQueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)
            ->andWhere('a.publishedAt IS NOT NULL');
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Article $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Article $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findAllPublishedOrderedByNewest()
    {
        return $this->addIsPublishedQueryBuilder()
            ->leftJoin('a.tags', 't')
            ->addSelect('t')
            ->orderBy('a.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->createQueryBuilder('a');
    }

    public static function createNonDeletedCriteria(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('isDeleted', false))
            ->orderBy(['createdAt' => 'DESC']);
    }
}
