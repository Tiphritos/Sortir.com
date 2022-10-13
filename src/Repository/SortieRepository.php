<?php

namespace App\Repository;

use App\Entity\Inscription;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function save(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false ): void
    {

        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findFiltres($site, $motClef, $date1, $date2, $estOrganisateur, $estInscrit, $pasInscrit, $sortiesPassees, $user):array{

        $queryBuilder= $this->createQueryBuilder('s');
        //$queryBuilder->join('App\Entity\Inscription', 'i');
        if($site != null){
            $queryBuilder->andWhere('s.site_organisateur = :site')
            ->setParameter(':site', $site);
        }
        if(strlen($motClef)>0){
            $queryBuilder->andWhere('s.nom LIKE :motClef')
                ->setParameter(':motClef', $motClef );
        }
        if($date1 != null){
            $queryBuilder->andWhere('s.date_debut >= :date1')
                ->setParameter(':date1', $date1 );
        }
        if($date2 != null){
            $queryBuilder->andWhere('s.date_debut <= :date2')
                ->setParameter(':date2', $date2 );
        }
        if($estOrganisateur){
            $queryBuilder->andWhere('s.organisateur = :user')
                ->setParameter(':user', $user );
        }
//        if(($estInscrit) && !($pasInscrit)){
//            $queryBuilder->andWhere('s.id = i.sortie_id');
//        }
//        if(!($estInscrit) && ($pasInscrit)){
//            $queryBuilder->andWhere('s.id = i.sortie_id');
//        }
        if($sortiesPassees){
            $queryBuilder->andWhere('s.etats_no_etat IN (4, 5, 7)');
        }

          //  dd($queryBuilder, $siteId);
        $query = $queryBuilder->getQuery();
        //dd($query);
        return $query->getResult();

    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
