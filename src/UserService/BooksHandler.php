<?php 
namespace App\UserService;

use App\Entity\Books;
use App\Entity\User;
use App\Entity\Bucket;
use App\Entity\Wishlist;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * This Class Conatins the Details of the book and related validator functions
 */

class BooksHandler {

  /**
   * FieldValidation trait to access the function for validations
   */
  use FieldValidation;
  
  /**
   *   @var string $bookId
   *     contains the alphanumeric book id
   */
  private string $bookId;

  /**
   *   @var string $title
   *     contains the title of the book
   */
  private string $title;

  /**
   *   @var object $imageFile
   *     contains the book's cover image file
   */
  private $imageFile;

  /**
   *   @var string $randomPicName
   *     Contains the randomly generated name of the image file
   */
  private string $randomPicName;

  /**
   *   @var string $genere
   *     contains the genere of the book
   */
  private string $genere;

  /**
   *   @var \DateTime $pubDate
   *     contains the date of publication of the book
   */
  private \DateTime $pubDate;
  
  /**
   *   @var string $author
   *     contains the name of the ayuthor of the book
   */
  private string $author;

  /**
   *   @var int $rating
   *     contains the rating of the book
   */
  private int $rating;

  /**
   *   @var string $category
   *     contains the category type of the book
   **/
  private string $category;

  /**
   *   @var Request
   *     Contains the Request variable value
   */
  private Request $request;

  /**
   *  @var int $offset
   *    contains the offset value to fetch the books after
   */
  private int $offset;

  /***
   *   @var EntityManagerInterface $em
   *     Contains the Entity manager Interface object to manage the entities
   */
  private EntityManagerInterface $em;

  /**
   * Construictor function to initialize the Request varible
   * 
   *   @param request $request
   *     Requesdt variable to access the requested form data
   * 
   *   @param EntityManagerInterface $em;
   *     Contains the Entity manager Interface object to handfle with the different entities
   */
  public function __construct(Request $request, EntityManagerInterface $em){
    $this->request = $request;
    $this->em = $em;
  }
  
  /**
   * Function to validatethe different fields of the form
   * 
   *   @return string
   *     Returs the string m,erssage based on the validation
   */
  public function fieldvalidation() {
    $this->bookId = $this->trimdata($this->request->request->get('id'));
    $this->title = $this->trimdata($this->request->request->get('title'));
    $this->imageFile = $this->request->files->get('cover');
    $this->pubDate =  new \DateTime('@' . strtotime($this->request->get('date')));
    $this->author = $this->trimdata($this->request->request->get('author'));
    $this->genere = $this->request->request->get('genere');
    $this->rating = $this->request->request->get('rating');
    $this->category = $this->request->request->get('category');

    if (!$this->nameValidation($this->author)) {
      return 'Invalid author name Formate';
    }
    elseif (empty($this->bookId) || empty($this->title) || empty($this->pubDate) || empty($this->author) || empty($this->rating) ) {
      return 'Empty Field Not Allowed';
    }
    elseif (!$this->coverImageFormate()) {
      return 'Invalid Picture Formate';
    }
    elseif ($this->rating > 5 || $this->rating < 0) {
      return 'rate from 0 to 5';
    }
    elseif ($this->bookExixts()) {
      return 'Given Book Id Alreday taken';
    }
    elseif (!$this->makeARecord()) {
      return 'Failed to Upload';
    }
    else {
      return 'success';
    }
  }
  
  /**
   * Function to check whether a book with given book id already exists
   */
  private function bookExixts() {
    $bookRepo = $this->em->getRepository(Books::class);
    $book = $bookRepo->findOneBy(['bookId' => $this->bookId]);
    if (!empty($book)) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Function to make a record of the book in DB
   *   
   *   @return bool
   *     return true or false based on upload status of the book
   */
  private function makeARecord() {
    $book = new Books();
    $path = 'coverImg/' . (isset($this->randomPicName) ? $this->randomPicName : 'default.png');
    try {
      $book->setBookId($this->bookId);
      $book->setCoverImg($path);
      $book->setTitle($this->title);
      $book->setGenere($this->genere);
      $book->setDateOfPublication($this->pubDate);
      $book->setAuthor($this->author);
      $book->setRating($this->rating);
      $book->setCategory($this->category);

      $this->em->persist($book);
      $this->em->flush();
      return TRUE;
    }
    catch (Exception $e) {
      return FALSE;
    }
  }

  /**
   * Function to get the books from the database after a certain offset
   * 
   *   @param int $offset
   *     Offset value for the books list
   * 
   *   @return array
   *     returns the array of the objects of books type
   */
  public function fetchBooks() {
    $this->offset = (int) $this->request->request->get('offset');
    try {
      $bookRepo = $this->em->getRepository(Books::class);
      $books = $bookRepo->findBy([], ['title' => 'DESC'], 9, $this->offset);
      return $books;
    }
    catch(Exception $e) {
      return [];
    }
  }

  /**
   * Function to add or remove a book from the booklist
   * 
   *   @param int uId
   *     Contains the user idf of the user who is uploading the book
   */
  public function bucketListHandler(int $uId) {
    $flag = $this->request->request->get('added');
    $bookId = (int) $this->request->request->get('bookId');
    $bookRepo = $this->em->getRepository(Books::class);
    $book = $bookRepo->findOneBy(['id' => $bookId]);
    $date = new \DateTime('@'.strtotime('now'));
    if ($flag == 1) {
      if ($book->getDateOfPublication() <= $date) {
        $this->addToBucket($bookId, $uId);
      }
      else {
        $this->addToWishlist($bookId, $uId);
      }
    }
    elseif ($flag == 0) {
      if ($book->getDateOfPublication() <= $date) {
        $this->removeFromBucket($bookId, $uId);
      }
      else {
        $this->removeFromWishlist($bookId, $uId);
      }
    }
  }

  /**
   * Function to add a book to wishlist
   * 
   *   @param int $bookId
   *     Book Id of the book
   * 
   *   @param int $userId
   *     User Id of the User
   */
  private function addToWishlist(int $bookId, int $userId) {
    $wishlist = new Wishlist();
    try {
      $bookRepo = $this->em->getRepository(Books::class);
      $book = $bookRepo->findOneBy(['id' => $bookId]);
      $userRepo = $this->em->getRepository(User::class);
      $user = $userRepo->findOneBy(['id' => $userId]);
      $wishlist->setBookId($book);
      $wishlist->setAddedBy($user);
      $this->em->persist($wishlist);
      $this->em->flush();
      return TRUE;
    }
    catch (Exception $e) {
      return FALSE;
    }
  }

  /**
   * Function to add a book to bucket
   * 
   *   @param int $bookId
   *     Book Id of the book
   * 
   *   @param int $userId
   *     User Id of the User
   */
  private function addToBucket(int $bookId, int $userId) {
    $bucket = new Bucket();
    try {
      $bookRepo = $this->em->getRepository(Books::class);
      $book = $bookRepo->findOneBy(['id' => $bookId]);
      $userRepo = $this->em->getRepository(User::class);
      $user = $userRepo->findOneBy(['id' => $userId]);
      $bucket->setBookId($book);
      $bucket->setAddedBy($user);
      $this->em->persist($bucket);
      $this->em->flush();
      return TRUE;
    }
    catch (Exception $e) {
      return FALSE;
    }

  }

  /**
   * Function to remove the item from wishlist
   * 
   *   @param int $bookId
   *     Book Id of the book
   * 
   *   @param int $userId
   *     User Id of the User
   * 
   */
  private function removeFromWishlist(int $bookId, int $userId) {
    try {
      $bookRepo = $this->em->getRepository(Books::class);
      $book = $bookRepo->findOneBy(['id' => $bookId]);
      $userRepo = $this->em->getRepository(User::class);
      $user = $userRepo->findOneBy(['id' => $userId]);
      $wishlistRepo = $this->em->getRepository(Wishlist::class);
      $wishlistItem = $wishlistRepo->findOneBy(['bookId' => $book, 'addedBy' => $user]);
      $this->em->remove($wishlistItem);
      $this->em->flush();
      return TRUE;
    }
    catch (Exception $e) {
      return FALSE;
    }
  }

  /**
   * Function to remove the item from bucket list
   * 
   *   @param int $bookId
   *     Book Id of the book
   * 
   *   @param int $userId
   *     User Id of the User
   * 
   */
  private function removeFromBucket(int $bookId, int $userId) {
    try {
      $bookRepo = $this->em->getRepository(Books::class);
      $book = $bookRepo->findOneBy(['id' => $bookId]);
      $userRepo = $this->em->getRepository(User::class);
      $user = $userRepo->findOneBy(['id' => $userId]);
      $bucketRepo = $this->em->getRepository(Bucket::class);
      $bucketItem = $bucketRepo->findOneBy(['bookId' => $book, 'addedBy' => $user]);
      $this->em->remove($bucketItem);
      $this->em->flush();
      return TRUE;
    }
    catch (Exception $e) {
      return FALSE;
    }
  }

  /**
   * Function to create the book array feom the list of book ids
   * 
   *   @param array $bookIds
   *     Contains the list of book ids to get the book object from
   * 
   *   @return array
   *     returns thhe array of books objects
   */
  private function getBookArray(array $bookIds) {
    $bookRepo = $this->em->getRepository(Books::class);
    $array = array();
    foreach ($bookIds as $bookItem) {
      $book = $bookRepo->findOneBy(['id' => $bookItem->getBookId()->getId()]);
      array_push($array, $book);
    }
    return $array;
  }

  /**
   * Function to fetch the all bucket items specific to the user
   * 
   *   @param int $userId
   *     User Id of the user
   * 
   *   @return array
   *     returns an array of all books in the bucket
   */
  public function fetchBuckeyItems(int $userId) {
    $offset = $this->request->get('offset');
    try {
      $userRepo = $this->em->getRepository(User::class);
      $user = $userRepo->findOneBy(['id' => $userId]);
      $bucketRepo = $this->em->getRepository(Bucket::class);
      $bucketItems = $bucketRepo->findBy(['addedBy' => $user], ['id' => 'DESC'], 9, $offset);
      return $this->getBookArray($bucketItems);
    }
    catch (Exception $e) {
      return [];
    }
  }

  /**
   * Function to fetch the all wishlist items specific to the user
   * 
   *   @param int $userId
   *     User Id of the user
   * 
   *   @return array
   *     returns an array of all books in the WishList
   */
  public function fetchWishListItems(int $userId) {
    $offset = $this->request->get('offset');
    try {
      $userRepo = $this->em->getRepository(User::class);
      $user = $userRepo->findOneBy(['id' => $userId]);
      $wishListRepo = $this->em->getRepository(Wishlist::class);
      $wishListItems = $wishListRepo->findBy(['addedBy' => $user], ['id' => 'DESC'], 9, $offset);
      return $this->getBookArray($wishListItems);
    }
    catch (Exception $e) {
      return [];
    }
  }


}
