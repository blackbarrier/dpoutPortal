<?php

namespace App\Repository;

use App\Entity\Adjunto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


/**
 * @method Adjunto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adjunto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adjunto[]    findAll()
 * @method Adjunto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdjuntoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Adjunto::class);
    }

    public function guardar(Adjunto $adjunto)
    {
       try {
           $this->getEntityManager()->persist($adjunto);
           $this->getEntityManager()->flush();
           return true;
       }
       catch (\Exception $e) {
           return false;
       }
   }

}
