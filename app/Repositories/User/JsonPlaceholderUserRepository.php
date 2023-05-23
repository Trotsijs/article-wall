<?php declare(strict_types=1);

namespace App\Repositories\User;

use App\Cache;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class JsonPlaceholderUserRepository implements UserRepository
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function all(): array
    {
        try {

            $userCollection = [];

            if (!Cache::has('users')) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/users');
                $responseJson = $response->getBody()->getContents();
                Cache::remember('users', $responseJson);
            } else {
                $responseJson = Cache::get('users');
            }

            $userData = json_decode($responseJson);

            foreach ($userData as $user) {
                $userCollection[] = $this->buildModel($user);
            }

            return $userCollection;

        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function getById(int $id): ?User
    {
        try {

            if (!Cache::has('user-' . $id)) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/users/' . $id);
                $responseJson = $response->getBody()->getContents();
                Cache::remember('user-' . $id, $responseJson);
            } else {
                $responseJson = Cache::get('user-' . $id);
            }

            return $this->buildModel(json_decode($responseJson));

        } catch (GuzzleException $exception) {
            return null;
        }
    }

    private function buildModel(stdClass $user): User
    {
        return new User(
            $user->id,
            $user->username,
            $user->name,
            $user->email,
            $user->website
        );
    }
}