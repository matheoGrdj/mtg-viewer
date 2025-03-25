<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Card>
 *
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    public function getAllUuids(): array
    {
        $result =  $this->createQueryBuilder('c')
            ->select('c.uuid')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
        return array_column($result, 'uuid');
    }

    public function findAllPaginated(int $page = 1, int $limit = 100): Paginator
    {
        $query = $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($query);
    }

    public function findAllPaginatedBySetCode(int $page = 1, int $limit = 100, ?string $setCode = null): Paginator
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.setCode = :setcode')
            ->setParameter('setcode', $setCode)
            ->getQuery()
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($query);
    }

    public function findByName($name)
    {
        return $this->createQueryBuilder('c')
            ->where('c.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->getQuery()
            ->setMaxResults(20)
            ->getResult();
    }

    public function findBySetCode($setCode)
    {
        return $this->createQueryBuilder('c')
            ->where('c.setCode = :setcode')
            ->setParameter('setcode', $setCode)
            ->getQuery()
            ->setMaxResults(20)
            ->getResult();
    }

    public function findByNameAndSetCode(string $name, ?string $setCode = null)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->setMaxResults(20)
            ->orderBy('c.name', 'ASC');

        if ($setCode) {
            $qb->andWhere('c.setCode = :setCode')
                ->setParameter('setCode', $setCode);
        }

        return $qb->getQuery()->getResult();
    }
}
