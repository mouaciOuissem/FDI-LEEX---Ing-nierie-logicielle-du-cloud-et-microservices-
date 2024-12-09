<?php

namespace App\Repository;

use App\Entity\VideoFormat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VideoFormat>
 *
 * @method VideoFormat|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoFormat|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoFormat[]    findAll()
 * @method VideoFormat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoFormatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoFormat::class);
    }

    public function add(VideoFormat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(VideoFormat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
        
    }

//    /**
//     * @return VideoFormat[] Returns an array of VideoFormat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VideoFormat
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
