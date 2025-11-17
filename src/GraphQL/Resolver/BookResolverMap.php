<?php

namespace App\GraphQL\Resolver;

use App\Entity\Book;
use App\Enum\BookGenre;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Resolver\ResolverMap;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use GraphQL\Error\UserError;

class BookResolverMap extends ResolverMap
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly EntityManagerInterface $em,
        private readonly ValidatorInterface $validator,
    ) {
    }

    protected function map(): array
    {
        return [
            'Query' => [
                'book' => [$this, 'resolveBook'],
                'books' => [$this, 'resolveBooks'],
            ],
            'Mutation' => [
                'createBook' => [$this, 'createBook'],
            ],
        ];
    }

    /**
     * book(id: ID!): Book
     */
    public function resolveBook($rootValue, Argument $args): ?array
    {
        $id = (int) $args['id'];

        $book = $this->bookRepository->find($id);
        if (!$book instanceof Book) {
            return null;
        }

        return $this->normalizeBook($book);
    }

    /**
     * books(author: String, genre: BookGenre): [Book!]!
     */
    public function resolveBooks($rootValue, Argument $args): array
    {
        $author = $args['author'] ?? null;
        $genreValue = $args['genre'] ?? null;

        $genre = null;
        if ($genreValue !== null) {
            $genre = BookGenre::from($genreValue);
        }

        $books = $this->bookRepository->findByFilters($author, $genre);

        return array_map([$this, 'normalizeBook'], $books);
    }

    /**
     * createBook(input: CreateBookInput!): Book!
     */
    public function createBook($rootValue, Argument $args): array
    {
        $input = $args['input'];

        $book = new Book();
        $book
            ->setAuthor($input['author'])
            ->setTitle($input['title'])
            ->setPrice((float) $input['price'])
            ->setExcerpt($input['excerpt'])
            ->setGenre(BookGenre::from($input['genre']));

        $errors = $this->validator->validate($book);

        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = sprintf('%s: %s', $error->getPropertyPath(), $error->getMessage());
            }

            throw new UserError('Validation failed: ' . implode(', ', $messages));
        }

        $this->em->persist($book);
        $this->em->flush();

        return $this->normalizeBook($book);
    }

    private function normalizeBook(Book $book): array
    {
        return [
            'id' => $book->getId(),
            'author' => $book->getAuthor(),
            'title' => $book->getTitle(),
            'price' => $book->getPrice(),
            'excerpt' => $book->getExcerpt(),
            'genre' => $book->getGenre()->value,
        ];
    }
}
