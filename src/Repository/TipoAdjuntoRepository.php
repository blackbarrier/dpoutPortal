<?php

namespace App\Repository;

use App\Entity\TipoAdjunto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @method TipoAdjunto|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoAdjunto|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoAdjunto[]    findAll()
 * @method TipoAdjunto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoAdjuntoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TipoAdjunto::class);
    }
    
    public function guardar($tipo) 
    {
       try 
       {
           $this->getEntityManager()->persist($tipo);
           $this->getEntityManager()->flush();
           return true;
       } 
       catch (\Exception $e) 
       {   
           //var_dump($e->getMessage());
           //return $e->getMessage();
           return false;
       }
   } 
   
   
   public function obtenerTiposFaltantes($idTramite)
   {
       $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare("SELECT *
                            FROM tipo_adjunto a
                            WHERE a.id<>'" . TipoAdjunto::ACTA_DEFUNCION_LABRADA. "' AND a.borrado=0 AND a.id NOT IN
                             (
                            SELECT tipo_adjunto_id
                            FROM  documentacion_adjunta
                            WHERE documentacion_adjunta.tramite_id=".$idTramite." AND documentacion_adjunta.borrado=0
                           
                            )
                            ORDER BY nombre 
                        ");
            $stmt->execute();
            $result = $stmt->fetchAll();
        try 
        {
            return $result;
        } 
        catch (\Doctrine\ORM\NoResultException $e) 
        {
            return array();
        }
   }        
  
}
