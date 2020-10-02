<?php

namespace App\Repository;

use App\Entity\Marcador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Marcador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Marcador|null findOneBy(array $criteria, array $orderBy = null)
 * @method Marcador[]    findAll()
 * @method Marcador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarcadorRepository extends ServiceEntityRepository
{

    private $user;

    public function __construct(Security $security, ManagerRegistry $registry)
    {
        parent::__construct($registry, Marcador::class);
        $this->user = $security->getUser();
    }

    public function findEverything($page = 1, $per_page_elements = 5)
    {
        $query = $this->createQueryBuilder('m')
            ->where('m.user = :user')
            ->setParameter('user', $this->user)
            ->orderBy('m.creado', 'DESC')
            ->addOrderBy('m.nombre', 'ASC')
            ->getQuery();

        return $this->pagination(
            $query,
            $page,
            $per_page_elements
        );
    }

    public function findByCategoriaName(
        $nombreCategoria,
        $page = 1,
        $per_page_elements = 5
    ) {
        $query = $this->createQueryBuilder('m')
            ->innerJoin('m.categoria', 'c')
            ->where('c.nombre = :nombreCategoria')
            ->setParameter('nombreCategoria', $nombreCategoria)
            ->orderBy('m.creado', 'DESC')
            ->addOrderBy('m.nombre', 'ASC')
            ->getQuery();

        return $this->pagination(
            $query,
            $page,
            $per_page_elements
        );
    }

    public function pagination($dql, $page = 1, $per_page_elements = 5)
    {
        $paginator = new Paginator($dql);
        $paginator
            ->getQuery()
            ->setFirstResult($per_page_elements * ($page - 1))
            ->setMaxResults($per_page_elements);

        return $paginator;
    }

    public function findByName($name, $page = 1, $per_page_elements = 5)
    {
        $query = $this->createQueryBuilder('m')
            ->where('m.nombre LIKE :nombre')
            ->andWhere('m.user = :user')
            ->setParameter('user', $this->user)
            ->setParameter('nombre', "%$name%")
            ->orderBy('m.creado', 'DESC')
            ->addOrderBy('m.nombre', 'ASC')
            ->getQuery();

        return $this->pagination(
            $query,
            $page,
            $per_page_elements
        );
    }

    public function findByFavorites($page = 1, $per_page_elements = 5)
    {
        $query = $this->createQueryBuilder('m')
            ->where('m.favorito = TRUE')
            ->andWhere('m.user = :user')
            ->setParameter('user', $this->user)
            ->orderBy('m.creado', 'DESC')
            ->addOrderBy('m.nombre', 'ASC')
            ->getQuery();

        return $this->pagination(
            $query,
            $page,
            $per_page_elements
        );
    }

    // /**
    //  * @return Marcador[] Returns an array of Marcador objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Marcador
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
