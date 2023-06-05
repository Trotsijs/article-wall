<?php declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;

class PdoUserRepository implements UserRepository {

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
        $users = $this->queryBuilder->select('*')
            ->from('users')
            ->fetchAllAssociative();

        $userCollection = [];
        foreach ($users as $user) {
            $userCollection[] = $this->buildModel($user);
        }

        return $userCollection;
    }

    public function getById(int $id): ?User
    {
        $user = $this->queryBuilder
            ->select('*')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->fetchAssociative();

        if (!$user) {
            return null;
        }

        return $this->buildModel($user);
    }

    public function getByEmail(string $email): ?User
    {
        $user = $this->queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->fetchAssociative();

        if (!$user) {
            return null;
        }

        return $this->buildModel($user);
    }

    private function buildModel(array $user): User
    {
        return new User(
            $user['name'],
            $user['email'],
            $user['password'],
            (int) $user['id'],
        );
    }

    public function save(User $user): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        if ($user->getId() === null) {

            $queryBuilder
                ->insert('users')
                ->values(
                    [
                        'name' => '?',
                        'email' => '?',
                        'password' => '?',
                    ]
                )
                ->setParameter(0, $user->getName())
                ->setParameter(1, $user->getEmail())
                ->setParameter(2, $user->getPassword());


            $queryBuilder->executeQuery();

            $user->setId((int)$this->connection->lastInsertId());

        } else {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->update('users')
                ->set('name', '?')
                ->set('email', '?')
                ->set('password', '?')
                ->where('id = ?')
                ->setParameter(0, $user->getName())
                ->setParameter(1, $user->getEmail())
                ->setParameter(2, $user->getPassword())
                ->setParameter(3, $user->getId());


            $queryBuilder->executeQuery();
        }
    }

    public function login(string $email, string $password): ?User
    {
        $user = $this->queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->fetchAssociative();

        if (!$user) {
            return null;
        }

        return $this->buildModel($user);
    }
}