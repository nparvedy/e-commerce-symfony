<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HelloController extends AbstractController
{

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("hello/{name?Tom}", name="name")
     */
    public function Hello($name, Environment $twig)
    {

        return $this->render('hello.html.twig', [
            'prenom' => $name
        ]);
    }

    //remplacer par abstract controller
    // public function render(string $path, array $variables = [])
    // {
    //     $html = $this->twig->render($path, $variables);
    //     return new Response($html);
    // }
}
