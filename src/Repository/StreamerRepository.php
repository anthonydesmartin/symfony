<?php

namespace App\Repository;

use App\Entity\Streamer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Streamer>
 *
 * @method Streamer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Streamer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Streamer[]    findAll()
 * @method Streamer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StreamerRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Streamer::class);
    }

    public function save(Streamer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Streamer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Streamer) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    public const STREAMERS_PER_PAGE = 12;
    /**
     * @return Paginator Returns an Paginator object for Streamer entity for Companies
     */
    public function getPaginatorStreamer( int $offset): Paginator
    {
        $query = $this->createQueryBuilder('s')
            ->orderBy('s.username', 'ASC')
            ->setMaxResults(self::STREAMERS_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery();

        return new Paginator($query);
    }


     /**
     * @return Array Returns in list of Streamer objects
     */
    public function getListUsername(): array
    {
        $username =[];
        foreach ($this->createQueryBuilder('fil')
            ->select('fil.username')
            ->distinct(true)
            ->orderBy('fil.username', 'ASC')
            ->getQuery()
            ->getResult() as $ligne)
        {
            $username[] = $ligne['username'];
        }
        return $username;
    }


//    /**
//     * @return Streamer[] Returns an array of Streamer objects
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

//    public function findOneBySomeField($value): ?Streamer
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
