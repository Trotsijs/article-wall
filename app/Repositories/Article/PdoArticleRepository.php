<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Models\Article;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class PdoArticleRepository implements ArticleRepository
{
    private Connection $connection;
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

    }

    public function all(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $articles = $queryBuilder->select('*')
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
        return null;
    }

    public function getByUserId(int $userId): array
    {
        return [];
    }

    private function buildModel(array $article): Article
    {
        return new Article(
            (int) $article['id'],
            (int) $article['user_id'],
            $article['title'],
            $article['content'],
            'https://placehold.co/800x400?text=News'
        );
    }
}