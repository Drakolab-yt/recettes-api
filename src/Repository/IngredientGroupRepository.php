<?php

namespace App\Repository;

use App\Entity\IngredientGroup;
use Gedmo\Sortable\Entity\Repository\SortableRepository;

/**
 * @extends \Gedmo\Sortable\Entity\Repository\SortableRepository<IngredientGroup>
 *
 * @method IngredientGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method IngredientGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method IngredientGroup[]    findAll()
 * @method IngredientGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientGroupRepository extends SortableRepository
{
    public function add(IngredientGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(IngredientGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return IngredientGroup[] Returns an array of IngredientGroup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?IngredientGroup
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
