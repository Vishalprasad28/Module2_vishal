<?php 
namespace Controller;
session_start();
use App\User;

/**
 * Class for Routing through different pages
 * 
 * @param array $uri
 *   It contains the path of all tha available pages.
 * 
 * @method  route()
 *   It return the exact path of the.
 */
class Router {
  
  /**
   * @var array $uri
   *   Contains the mapping of all available pages.
   */
  private array $uri;

  /**
   * @var string $errorPage
   */
  private string $errorPage;

  /**
   * @var array $availablePages
   */
  private array $availablePages;

  /**
   * Constructor makes a mapping of all available pages.
   */
  public function __construct() {
    $this->uri = array(
      "/" => "login",
      "/loginValidation" => 'loginValidation',
      "/home" => "home",
      "/wishlist" => "wishlist",
      "/bucket" => "bucket",
      "/bookform" => "bookForm",
      "/accountview" => "accountView",
      "/bookValidation" => 'bookValidation'
    );
    $this->errorPage = "public/error.php";
  }

  /**
   * @param string $uri
   * 
   */
  public function route(string $page) {
    if (array_key_exists($page, $this->uri)) {
      $this->{$this->uri[$page]}();
    }
    else {
      return $this->errorPage;
    }
  }

  public function bookForm() {
    header('location:bookform.php');
  }
  public function accountView() {
    header('location:accountoverview.php');
  }
  public function home() {
    header('Refresh:0,url=home.php');
  }
  public function login() {
    header('location:login.php');
  }
  public function wishlist() {
    header('location:wishlist.php');
  }
  public function bucket() {
    header('location:bucketlist.php');
  }
  public function loginValidation() {
    $uName = $_REQUEST['uName'];
    $password = $_REQUEST['pwd'];
    $obj = new User($uName, $password);
    $message = $obj->checkUser();
    if ($message == 'success') {
      $role = $obj->roleGetter();
      echo $role;
      $_SESSION['login'] = TRUE;
      $_SESSION['uName'] = $obj->getUserName();
      $_SESSION['fullName'] = $obj->getFullName();
      $_SESSION['role'] = $role;
      if ($role == 'author') {
        header('location:/bookform');
      }
      else {
        header('location:/accountview');
      }
    }
    else {
      header('location:/');
    }
  }

  public function bookValidation() {
    $id = $_REQUEST['id'];
    
  }
}
