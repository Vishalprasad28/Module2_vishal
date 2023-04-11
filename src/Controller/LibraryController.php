<?php

namespace App\Controller;

use App\UserService\ActiveUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{

      /**
   * @var mixed
   */
  private $em;

  /**
   * Function that iniitilizes the rntity manager interface object
   *   
   *   @param EntityManagerInterface $em
   *     Entity Manager Object
   * 
   *   @return void
   */
  public function __construct(EntityManagerInterface $em) {
    $this->em = $em;
  }

    #[Route('/', name: 'loginPage')]
    public function loginPage() {
        return $this->render('login.html.twig');
    }

    #[Route('/signup', name: 'signup')]
    public function signUpPage() {
        return $this->render('signup.html.twig');
    }
    
    /**
     * Route for the Login Form Validation
     * 
     *   @param Request $request
     *     request madeby the server
     * 
     *   @return Response
     */
    #[Route('/loginValidation', name: 'loginValidation')]
    public function loginValidation(Request $request): Response {
      if ($request->isXmlHttpRequest()) {
        $user = new ActiveUser($request, $this->em);
        $message = $user->loginValidation();

        return $this->json([
          'message' => $message
        ]);
      }
      return $this->render('error.html.twig');
    }

    #[Route('/signUpValidation', name: 'signUpValidation')]
    public function signUpValidation(Request $request): Response {
      if ($request->isXmlHttpRequest()) {
        $user = new ActiveUser($request, $this->em);
        $message = $user->signUpValidation();

        return $this->json([
          'message' => $message
        ]);
      }
      return $this->render('error.html.twig');
    }
}
