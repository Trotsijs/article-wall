<?php declare(strict_types=1);

use App\Console\ArticleCommand;
use App\Console\ArticlesCommand;
use App\Console\UsersCommand;

require_once '../vendor/autoload.php';

$resource = $argv[1] ?? null;
$id = $argv[2] ?? null;

switch ($resource) {
    case 'article':
        $articlesCommand = new ArticleCommand();
        $articlesCommand->execute((int) $id);

        break;
    case 'articles':
        $articlesCommand = new ArticlesCommand();
        $articlesCommand->execute();

        break;
    case 'user':
        $userCommand = new UsersCommand();
        $userCommand->execute((int) $id);

        break;
    default:
        echo 'Invalid resource/command provided.';
        break;

}

