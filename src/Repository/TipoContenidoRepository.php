<?php

namespace App\Repository;

use App\Entity\TipoContenido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;



/**
 * @method TipoContenido|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoContenido|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoContenido[]    findAll()
 * @method TipoContenido[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoContenidoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TipoContenido::class);
    }
    
   
   
  
}
