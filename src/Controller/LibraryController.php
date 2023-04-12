<?php

namespace App\Controller;
session_start();

use App\Entity\Bucket;
use App\Entity\User;
use App\Entity\Wishlist;
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

  /**
   * Function to redirect to the Login Page
   * 
   *   @return Response
   *     Renders the Login Page
   */
  #[Route('/', name: 'loginPage')]
  public function loginPage() {
    return $this->render('login.html.twig');
  }

   /**
   * Function to redirect to the SignUp Page
   * 
   *   @return Response
   *     Renders the SignUp Page
   */
  #[Route('/signup', name: 'signup')]
  public function signUpPage() {
    return $this->render('signup.html.twig');
  }
  
   /**
   * Function to redirect to the BucketList Page
   * 
   *   @return Response
   *     Renders the BucketList Page
   */
  #[Route('/bucket', name: 'bucket')]
  public function bucket() {
    return $this->render('bucket.html.twig');
  }

   /**
   * Function to redirect to the WishList Page
   * 
   *   @return Response
   *     Renders the WishList Page
   */
  #[Route('/wishlist', name: 'wishlist')]
  public function wishlist() {
    return $this->render('wishlist.html.twig');
  }
  
   /**
   * Function to Log Out
   * 
   *   @return Response
   *     Redirects top the login page
   */
  #[Route('/logout', name: 'logout')]
  public function logout() {
    unset($_SESSION);
    session_destroy();
    return $this->render('login.html.twig');
  }

   /**
   * Function to redirect to home page
   * 
   *   @return Response
   *     Renders the home page with user's credentials
   */
  #[Route('/home', name: 'home')]
  public function home(): Response {
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

  /**
   * Function to Redirect to the Book upload Form Page
   */
  #[Route('/upload', name: 'upload')]
  public function upload() {
    if (isset($_SESSION['login'])) {
      $user = $this->em->getRepository(User::class)->findOneBy(['id' => $_SESSION['user']]);
      if ($user->getRole() == 'author') {
        return $this->render('bookupload.html.twig');
      }
      elseif ($user->getRole() == 'reader') {
        return $this->redirect('/accountview');
      }
    }
    else {
      return $this->render('login.html.twig');
    }
  }

  /**
   * Associative array builder
   * 
   *   @param array $array
   *     Takes the array of Books type object
   * 
   *   @return array
   *     Returns the associative array of Book's details
   */
  private function arrayBuilder(array $array): array {
    $books = array();
    $bucketRepo = $this->em->getRepository(Bucket::class);
    $wishlistRepo = $this->em->getRepository(Wishlist::class);
    $userRepo = $this->em->getRepository(User::class);
    $user = $userRepo->findOneBy(['id' => $_SESSION['user']]);
    for($i = 0; $i < count($array); $i++) {
      $books[$i]['id'] = $array[$i]->getId();
      $books[$i]['addedToBucket'] = $bucketRepo->findOneBy(['bookId' => $array[$i], 'addedBy' => $user]);
      if (empty($books[$i]['addedToBucket'])) {
        $books[$i]['addedToWishlist'] = $wishlistRepo->findOneBy(['bookId' => $array[$i], 'addedBy' => $user]);
      }
      $books[$i]['cover'] = $array[$i]->getCoverImg();
      $books[$i]['title'] = $array[$i]->getTitle();
      $books[$i]['author'] = $array[$i]->getAuthor();
    }
    return $books;
  }

  /**
   * Function to redfirect to the account View page
   * 
   *   @return Response
   *     Renders the acopuntview page or the login page depending upon
   *     whether the user is logged in or not
   */
  #[Route('/accountview', name: 'accountview')]
  public function accountView(): Response {
    if (isset($_SESSION['login'])) {
      $userRepo = $this->em->getRepository(User::class);
      $user = $userRepo->findOneBy(['id' => $_SESSION['user']]);

      return $this->render('accountview.html.twig', [
        'fullName' => $user->getFullName(),
        'id' => $user->getId()
      ]);
    }
    else {
      return $this->redirect('/');
    }
  }
  
  /**
   * Route for the Login Form Validation
   * 
   *   @param Request $request
   *     Request madeby the server
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
   * Route for the SignUp Form Validation
   * 
   *   @param Request $request
   *     Request madeby the server
   * 
   *   @return Response
   *     Returns the response
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
   * Route for the Books Upload Form Validation
   * 
   *   @param Request $request
   *     Request madeby the server
   * 
   *   @return Response
   *     Returns the response
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
   *    Returns the Response
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
   * Route to add or remove the books from bucket list
   * 
   *   @param Request $request
   *     Request type variable to gert the requested values
   *   
   *   @return Response
   *    Returns the Response
   */
  #[Route('/addToBucket', name: 'addToBucket')]
  public function addToBucket(request $request): Response {
    if ($request->isXmlHttpRequest()) {
      $obj = new BooksHandler($request, $this->em);
      $obj->bucketListHandler($_SESSION['user']);
      return $this->json([
        'message' => 'added'
      ]);
    }
    return $this->render('error.html.twig');
  }

  /**
   * Route to fetch the books from the bucket list
   * 
   *   @param Request $request
   *     Request type variable to gert the requested values
   *   
   *   @return Response
   *     Returns the Response
   */
  #[Route('/fetchBucketList', name: 'fetchBucketList')]
  public function fetchBucketList(Request $request) {
    if ($request->isXmlHttpRequest()) {
      $obj = new BooksHandler($request, $this->em);
      $array = $obj->fetchBuckeyItems($_SESSION['user']);
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
   * Route to fetch the books from the WishList
   * 
   *   @param Request $request
   *     Request type variable to gert the requested values
   *   
   *   @return Response
   *     Returns the Response
   */
  #[Route('/fetchWishList', name: 'fetchWishList')]
  public function fetchWishList(Request $request) {
    if ($request->isXmlHttpRequest()) {
      $obj = new BooksHandler($request, $this->em);
      $array = $obj->fetchWishListItems($_SESSION['user']);
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
}
