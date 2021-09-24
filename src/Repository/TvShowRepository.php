<?php

namespace App\Repository;

use App\Entity\TvShow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TvShow|null find($id, $lockMode = null, $lockVersion = null)
 * @method TvShow|null findOneBy(array $criteria, array $orderBy = null)
 * @method TvShow[]    findAll()
 * @method TvShow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TvShowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TvShow::class);
    }

   /**
     * Récupère toutes les informations liées au tvShow demandé
     * @return TvShow
     */
    public function findOneWithAllInfos(int $id): TvShow
    {
        $entityManager = $this->getEntityManager();

        // on va utiliser le DQL ( Doctrine Query Language)
        $query = $entityManager->createQuery(
            'SELECT t, s, e
            -- dans le select il faut penser à ajouter les objets que l on veut récupérer
            -- car le SELECT t équivaut à SELECT tv_show.*
            -- En DQL on requête des objets ! donc on fournit le FQCN de l objet à récupérer
            FROM App\Entity\TvShow t
            -- le join permet de faire le inner join et de récupérer directement les informations de la table reliée
            JOIN t.seasons s
            -- vous remarquerez que l on passe par les propriétés de l objet // ON PENSE OBJET ET NON SQL !
            JOIN s.episodes e

            -- un petit paramètre pour éviter les injections DQL !
            WHERE t.id = :id'
        )->setParameter('id', $id);

        // returns the selected TvShow Object
        return $query->getOneOrNullResult();
    }


    
    // /**
    //  * @return TvShow[] Returns an array of TvShow objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TvShow
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
