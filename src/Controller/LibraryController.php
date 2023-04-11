<?php

namespace App\Controller;
session_start();

use App\Entity\Books;
use App\Entity\Bucket;
use App\Entity\User;
use App\UserService\ActiveUser;
use App\UserService\BooksHandler;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
  
  #[Route('/bucket', name: 'bucket')]
  public function bucket() {
    return $this->render('bucket.html.twig');
  }

  #[Route('/wishlist', name: 'wishlist')]
  public function wishlist() {
    return $this->render('wishlist.html.twig');
  }
  
  #[Route('/logout', name: 'logout')]
  public function logout() {
    unset($_SESSION);
    session_destroy();
    return $this->render('login.html.twig');
  }

  #[Route('/home', name: 'home')]
  public function home() {
    if (isset($_SESSION['login'])) {
      $userRepo = $this->em->getRepository(User::class);
      $user = $userRepo->findOneBy(['id' => $_SESSION['user']]);

      return $this->render('home.html.twig', [
        'fullName' => $user->getFullName(),
        'id' => $user->getId()
      ]);
    }
    else {
      return $this->render('login.html.twig');
    }
  }

  #[Route('/upload', name: 'upload')]
  public function upload() {
    if (isset($_SESSION['login'])) {
      $user = $this->em->getRepository(User::class)->findOneBy(['id' => $_SESSION['user']]);
      if ($user->getRole() == 'author') {
        return $this->render('bookupload.html.twig');
      }
      elseif ($user->getRole() == 'reader') {
        return $this->render('accountview.html.twig', [
          'fullName' => $user->getFullName(),
          'id' => $user->getId()
        ]);
      }
    }
    else {
      return $this->render('login.html.twig');
    }
  }

  /**
   * Associative array builder
   * 
   * @param array $array
   * 
   * @return array
   */
  private function arrayBuilder(array $array): array {
    $books = array();
    $bucketRepo = $this->em->getRepository(Bucket::class);
    $userRepo = $this->em->getRepository(User::class);
    $user = $userRepo->findOneBy(['id' => $_SESSION['user']]);
    for($i = 0; $i < count($array); $i++) {
      $books[$i]['id'] = $array[$i]->getId();
      $books[$i]['added'] = $bucketItem = $bucketRepo->findOneBy(['bookId' => $array[$i], 'addedBy' => $user]);
      $books[$i]['cover'] = $array[$i]->getCoverImg();
      $books[$i]['title'] = $array[$i]->getTitle();
      $books[$i]['author'] = $array[$i]->getAuthor();
    }
    return $books;
  }

  #[Route('/accountview', name: 'accountview')]
  public function accountView() {
    if (isset($_SESSION['login'])) {
      $userRepo = $this->em->getRepository(User::class);
      $user = $userRepo->findOneBy(['id' => $_SESSION['user']]);

      return $this->render('accountview.html.twig', [
        'fullName' => $user->getFullName(),
        'id' => $user->getId()
      ]);
    }
    else {
      return $this->render('login.html.twig');
    }
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

  /**
   * Route for the Login Form Validation
   * 
   *   @param Request $request
   *     request madeby the server
   * 
   *   @return Response
   */
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

  /**
   * Route for the Login Form Validation
   * 
   *   @param Request $request
   *     request madeby the server
   * 
   *   @return Response
   */
  #[Route('/bookUploadValidation', name: 'bookUploadValidation')]
  public function bookUploadValidation(Request $request): Response {
    if ($request->isXmlHttpRequest()) {
      $obj = new BooksHandler($request, $this->em);
      $message = $obj->fieldvalidation();
      return $this->json([
        'message' => $message
      ]);
    }
    return $this->render('error.html.twig');
  }

  /**
   * Route to fech the books from the table
   * 
   *   @param Request $request
   *     Request type variable to gert the requested values
   *   
   *  @return Response
   *    returns the Response
   */
  #[Route('/fetchBooks', name: 'fetchBooks')]
  public function fetchBooks(Request $request): Response {
    if ($request->isXmlHttpRequest()) {
      $obj = new BooksHandler($request, $this->em);
      $array = $obj->fetchBooks();
      $books = $this->arrayBuilder($array);
      try {
        return $this->render('components/books.html.twig',
        ['books' => $books]);
      }
      catch(Exception $e) {
        return $this->json([
          'message' => $e->getMessage()
        ]);
      }
    }
    return $this->render('error.html.twig');
  }

  /**
   * Route to fech the books from the table
   * 
   *   @param Request $request
   *     Request type variable to gert the requested values
   *   
   *  @return Response
   *    returns the Response
   */
  #[Route('/addToBucket', name: 'addToBucket')]
  public function addToBucket(request $request): Response {
    if ($request->isXmlHttpRequest()) {
      $obj = new BooksHandler($request, $this->em);
      $message = $obj->bucketListHandler($_SESSION['user']);
      return $this->json([
        'message' => $message
      ]);
    }
    return $this->render('error.html.twig');
  }

  /**
   * Route to fech the books from the table
   * 
   *   @param Request $request
   *     Request type variable to gert the requested values
   *   
   *   @return Response
   *     returns the Response
   */
  #[Route('/fetchBucketList', name: 'fetchBucketList')]
  public function fetchBucketList(Request $request) {
    if ($request->isXmlHttpRequest()) {
      $bookRepo = $this->em->getRepository(Books::class);
      $book = $bookRepo->findOneBy(['id' => 4]);
      $date = new \DateTime('@'.strtotime('now'));
      $pubDate = $book->getDateOfPublication();
      if ($date <= $pubDate) {
        $message = 'here';
      }
      else {
        $message = 'there';
      }
      return $this->json([
        'message' => $message
      ]);
    }
    return $this->render('error.html.twig');
  }
}
