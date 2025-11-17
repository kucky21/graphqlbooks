<?php

namespace App\Repository;

use App\Entity\Book;
use App\Enum\BookGenre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @return Book[]
     */
    public function findByFilters(?string $author, ?BookGenre $genre): array
    {
        $qb = $this->createQueryBuilder('b');

        if ($author !== null && $author !== '') {
            $qb
                ->andWhere('LOWER(b.author) LIKE :author')
                ->setParameter('author', '%' . mb_strtolower($author) . '%');
        }

        if ($genre !== null) {
            $qb
                ->andWhere('b.genre = :genre')
                ->setParameter('genre', $genre);
        }

        return $qb
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
