<?php

namespace App\Repository;

use App\Entity\Inscription;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
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
//        if ($estInscrit){
//            return $user->getInscriptions();
//        }
        if($estInscrit) {
            $queryBuilder->Join('App\Entity\Inscription', 'i');

        }
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
        if(($estInscrit)){ //Sortie auxquelles l'utilisateur est inscrit
            $queryBuilder->andWhere('s = i.sortie_id');
            $queryBuilder->andWhere(':user = i.participant_id')
                ->setParameter(':user', $user);
        }
        if($sortiesPassees){
            $queryBuilder->andWhere('s.date_debut <= :date3')
                ->setParameter(':date3', date_create_immutable('now'));
        }
        $query = $queryBuilder->getQuery();

        $response = $query->getResult();

        if ($pasInscrit){
            $inscriptions = $user->getInscriptions();
            foreach($inscriptions as $inscription){
                $insc = $this->findOneBy(['id' => $inscription->getSortieId()]);
                $arr[] = $insc;
            }

            if ($estInscrit){
                return array_merge($response, $arr);
            }else {
                return array_diff($response, $arr);
            }
        }

        return $response;
    }
}
