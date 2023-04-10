<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/LibraryController.php',
        ]);
    }

    #[Route('/home', name: 'homePage')]
    public function home() {
        return $this->render('login.html.twig');
    }

    #[Route('/loginValidation', name: 'loginValidation')]
    public function loginValidation(Request $request): Response {
      if ($request->isXmlHttpRequest()) {
        $a = $request->request->get('uName');
        return $this->json([
          'message' => $a
        ]);
      }
      return $this->json([
        'message' => 'hello There'
      ]);
    }
}
