<?php

namespace App\Repository;

use App\Entity\Organismo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Estados|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estados|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estados[]    findAll()
 * @method Estados[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganismoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Organismo::class);
    }
}
