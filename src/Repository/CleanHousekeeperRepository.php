<?php

namespace App\Repository;

use App\Entity\CleanHousekeeper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CleanHousekeeper>
 *
 * @method CleanHousekeeper|null find($id, $lockMode = null, $lockVersion = null)
 * @method CleanHousekeeper|null findOneBy(array $criteria, array $orderBy = null)
 * @method CleanHousekeeper[]    findAll()
 * @method CleanHousekeeper[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CleanHousekeeperRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CleanHousekeeper::class);
    }

    public function save(CleanHousekeeper $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CleanHousekeeper $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CleanHousekeeper[] Returns an array of CleanHousekeeper objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CleanHousekeeper
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
