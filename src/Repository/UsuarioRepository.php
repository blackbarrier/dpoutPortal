<?php

namespace App\Repository;

use App\Entity\Usuario;
use App\Entity\Rol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * @method Usuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usuario[]    findAll()
 * @method Usuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, Usuario::class);
    }

    public function obtenerPorId($id) {
        return $this->find($id);
    }

    public function obtenerTodos() {
        return $this->findBy(['borrado' => 0]);
    }

    public function obtenerTodosConOrigenMunicipio() {
        return $this->findBy(['rol' => Rol::ROLE_ORGANISMO, 'borrado'=>0]);

    }
    public function obtenerTodosConFuneriarias() {
        return $this->findBy(['rol' => Rol::ROLE_FUNERARIA, 'borrado'=>0]);

    }
        public function obtenerTodosConOrigenDelegacion() {
        return $this->findBy(['rol' => Rol::ROLE_DELEGACION,'borrado'=>0]);

    }

    public function guardar(Usuario $usuario) {
        try {

            $this->getEntityManager()->persist($usuario);
            $this->getEntityManager()->flush();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function eliminar(Usuario $usuario) {
        try {
            $usuario->setBorrado(1);
            $usuario->setDni("xxx" . $usuario->getDni() . 'xxx');
            $this->getEntityManager()->persist($usuario);
            $this->getEntityManager()->flush();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function activarOrganismoSeleccionado($idUsuario,$idEntidad) 
    {
        
       $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare("UPDATE usuario_entidad 
                            SET activo=1
                            WHERE usuario_id='".$idUsuario."'
                            AND entidad_id='".$idEntidad."'    
                         ");
        $stmt->execute();
        
        try 
        {
            return true;
        } 
        catch (\Doctrine\ORM\NoResultException $e) {
            return false;
        }
   }        
   
    public function obtenerTodosConOrigenMunicipioArray() 
    {
        
        $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare("SELECT id as idUsuario,
                                  dni,
                                  nombre,
                                  apellido,
                                  email,
                                  activo
                            FROM usuario
                            WHERE borrado=0
                            AND rol_id='".ROL::ROLE_ORGANISMO."'

                          ");
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        
        try 
        {
            $copiaResult=array();
            if(sizeof($result)>0)
            {
                $entidadesAsociadas='';
                foreach ($result as $value) 
                {
                    $idUsuario=$value['idUsuario'];
                    $entidadesAsociadas=$this->obtenerDescripcionEntidadesAsignadas($idUsuario);
                    $value['entidadesAsociadas']=$entidadesAsociadas;
                    array_push($copiaResult,$value);
                }
                
                
            }   
            
            return $copiaResult;
        } 
        catch (\Doctrine\ORM\NoResultException $e) {
            return array();
        }
    }
    
    public function obtenerDescripcionEntidadesAsignadas($idUsuario)
   {
       $descripcion='';
       $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare("SELECT entidad.*
                            FROM  usuario_entidad 
                            INNER JOIN entidad ON (usuario_entidad.entidad_id=entidad.id)
                            WHERE usuario_id='".$idUsuario."'
                            AND usuario_entidad.activo=1
                            AND entidad.borrado=0
                            ORDER BY descripcion
                            ");
        
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        try 
        {
            if(sizeof($result)>0)
            {
                foreach ($result as $value) 
                {
                    $descripcion.=$value['descripcion']."<br>";
                }
            }    
            
            return $descripcion;
        } 
        catch (\Doctrine\ORM\NoResultException $e) {
            return $descripcion;
        }
   }  
   
   
}
