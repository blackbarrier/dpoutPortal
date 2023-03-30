<?php

namespace App\Repository;

use App\Entity\Contenido;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * @method Contenido|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contenido|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contenido[]    findAll()
 * @method Contenido[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContenidoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Contenido::class);
    }




    public function  obtenerTodos()
    {
        return $this->findBy(['borrado'=>0]);
    }

    public function  obtenerTodosPorTipo($tipo)
    {
        return $this->findBy(['tipo' => $tipo , 'borrado'=>0]);
    }

    public function  obtenerTodosParaBiblioteca()
    {
        $query = $this->createQueryBuilder('e');

        $tipo = 2;

        $query->andWhere("e.tipo = :tipo ")
            ->setParameter('tipo', $tipo)
            ->andWhere("e.publicado   is not NULL ");


        //dd($query->getQuery()->getSQL());
        return $query->orderBy('e.fecha', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function obtenerEnlaces($search = null, $tipo)
    {
        try 
        {
        
            $where = "";
            if ($search) {
                $search = trim($search);
                $where = "";
                $searchArray = explode(' ', $search);
                $query = $this->createQueryBuilder('e');
                foreach ($searchArray as $search) {
                    $where .= "OR tags.nombre  like '%" . $search . "%'";
                }
                $where = substr($where, 2, strlen($where));
            }
            $where .= " AND contenido.tipo_contenido_id=" . $tipo;
            if (empty($where)) {
                $where = substr($where, 4, strlen($where));
            }

            $sql = "SELECT DISTINCT *
                    FROM 
                    (
                    SELECT 
                      contenido.id AS id_contenido,
                      contenido.hash AS hash,
                      contenido.url AS url,
                      contenido.nombre AS titulo,
                      contenido.interno
                    FROM
                      contenido
                      INNER JOIN contenido_tag
                        ON (
                          contenido.id = contenido_tag.contenido_id
                        )
                      INNER JOIN tags
                        ON (contenido_tag.tag_id = tags.id)
                    WHERE ".$where."
                    ORDER BY contenido.fecha DESC
                    ) AS t ";

            $query = $this->getEntityManager()
                ->createQuery($sql);

            $stmt = $this->getEntityManager()
                    ->getConnection()
                    ->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->fetchAll();
            if(count($result)>0)
            {
                foreach ($result as $key => $value) {
                    
                    $id_contenido = $result[$key]['id_contenido'];
                    $tags = $this->obtenerTags($id_contenido);
                    $result[$key]['tags'] = $tags;
                }
            }    
            
            return $result;
       
        } 
        catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }


    public function obtenerEnlacesConLimite($tipo, $limite)
    {
        
        try 
        {
        
        $sql = "SELECT DISTINCT *
                    FROM 
                    (
                    SELECT 
                      contenido.id AS id_contenido,
                      contenido.hash AS hash,
                      contenido.url AS url,
                      contenido.nombre AS titulo,
                      contenido.interno
                    FROM
                      contenido
                      INNER JOIN contenido_tag
                        ON (
                          contenido.id = contenido_tag.contenido_id
                        )
                      INNER JOIN tags
                        ON (contenido_tag.tag_id = tags.id)
                    WHERE contenido.tipo_contenido_id=" . $tipo."
                    ORDER BY contenido.fecha DESC
                    LIMIT 10
                    ) AS t ";

            $query = $this->getEntityManager()
                ->createQuery($sql);

            $stmt = $this->getEntityManager()
                    ->getConnection()
                    ->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->fetchAll();
            if(count($result)>0)
            {
                foreach ($result as $key => $value) {
                    
                    $id_contenido = $result[$key]['id_contenido'];
                    $tags = $this->obtenerTags($id_contenido);
                    $result[$key]['tags'] = $tags;
                }
            }    
            
            return $result;
        
         } 
        catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
        
        
    }

    public function guardar($item)
    {
        try {
            $this->getEntityManager()->persist($item);
            $this->getEntityManager()->flush();
            return true;
        } catch (\Exception $e) {
            dd($e);
            return false;
        }
    }
    
    
    public function eliminar($contenido)
    {
        try {
            $contenido->setBorrado(1);
            $this->getEntityManager()->persist($contenido);
            $this->getEntityManager()->flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    function obtenerTags($id_contenido)
    {
        
        $tags = '';
        
        $sql = "SELECT tags.nombre
                FROM 
                contenido
                INNER JOIN contenido_tag
                ON (
                  contenido.id = contenido_tag.contenido_id
                )
                INNER JOIN tags
                ON (contenido_tag.tag_id = tags.id)
                WHERE contenido.id = ".$id_contenido;

            $query = $this->getEntityManager()
                ->createQuery($sql);

            $stmt = $this->getEntityManager()
                    ->getConnection()
                    ->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->fetchAll();
            if(count($result)>0)
            {
                foreach ($result as $tag) {
                   
                    $tags .= $tag['nombre']. ' | ';
                }      
            }    
        
        return $tags;
    }
}
