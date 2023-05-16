<?php declare(strict_types=1);

namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Renderer
{
    private Environment $twig;

    public function __construct(string $basePath)
    {
        $loader = new FilesystemLoader($basePath);
        $this->twig = new Environment($loader);
    }

    public function render(TwigView $twigView): string
    {
        return $this->twig->render($twigView->getPath() . '.html.twig', $twigView->getData());
    }
}