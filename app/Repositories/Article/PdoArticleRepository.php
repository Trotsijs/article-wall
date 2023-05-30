<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Models\Article;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;

class PdoArticleRepository implements ArticleRepository
{
    private Connection $connection;
    private QueryBuilder $queryBuilder;

    public function __construct()
    {
        $connectionParams = [
            'dbname' => $_ENV['DB_NAME'],
            'user' => $_ENV['USER'],
            'password' => $_ENV['PASSWORD'],
            'host' => $_ENV['HOST'],
            'driver' => $_ENV['DRIVER'],
        ];
        $this->connection = DriverManager::getConnection($connectionParams);
        $this->queryBuilder = $this->connection->createQueryBuilder();

    }

    public function all(): array
    {
        $articles = $this->queryBuilder->select('*')
            ->from('articles')
            ->fetchAllAssociative();

        $articlesCollection = [];
        foreach ($articles as $article) {
            $articlesCollection[] = $this->buildModel($article);
        }

        return $articlesCollection;
    }

    public function getById(int $id): ?Article
    {
        $article = $this->queryBuilder
            ->select('*')
            ->from('articles')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->fetchAssociative();

        if (!$article) {
            return null;
        }

        return $this->buildModel($article);
    }

    public function getByUserId(int $userId): array
    {
        $articles = $this->queryBuilder->select('*')
            ->from('articles')
            ->where('user_id = ?')
            ->setParameter(0, $userId)
            ->fetchAllAssociative();

        $articlesCollection = [];
        foreach ($articles as $article) {
            $articlesCollection[] = $this->buildModel($article);
        }

        return $articlesCollection;
    }

    public function create(string $title, string $content): int
    {
        $this->queryBuilder
            ->insert('articles')
            ->values([
                'user_id' => 1,
                'title' => '?',
                'content' => '?',
            ])
            ->setParameter(1, $title)
            ->setParameter(2, $content)
            ->executeStatement();

        return (int)$this->connection->lastInsertId();

    }

    public function delete(int $id)
    {
        $this->queryBuilder
            ->delete('articles')
            ->where('id = ?')
            ->setParameter(1, $id)
            ->executeQuery();
    }

    private function buildModel(array $article): Article
    {
        return new Article(
            (int)$article['user_id'],
            $article['title'],
            $article['content'],
            'https://placehold.co/800x400?text=News',
            $article['created_at'],
            (int)$article['id']
        );
    }

    public function save(Article $article): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        if ($article->getId() === null) {

            $queryBuilder
                ->insert('articles')
                ->values(
                    [
                        'title' => '?',
                        'user_id' => '?',
                        'content' => '?',
                        'created_at' => '?',
                    ]
                )
                ->setParameter(0, $article->getTitle())
                ->setParameter(1, $article->getAuthorId())
                ->setParameter(2, $article->getContent())
                ->setParameter(3, $article->getCreatedAt());

            $queryBuilder->executeQuery();

            $article->setId((int)$this->connection->lastInsertId());

        } else {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->update('articles')
                ->set('title', '?')
                ->set('content', '?')
                ->where('id = ?')
                ->setParameter(0, $article->getTitle())
                ->setParameter(1, $article->getContent())
                ->setParameter(2, $article->getId());


            $queryBuilder->executeQuery();
        }
    }
}