<?php 
namespace UserService;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * This Class Conatins the User credentials like Full name username and more
 */

class ActiveUser {

  /**
   * @var string $userName
   *  It contains the username of the user
   */
  private string $userName;

  /**
   * @var string $fullName
   *   Stores the Full Name of the User
   */
  private string $fullName;

  /**
   * @var string $password
   *   Stores the password of the User
   */
  private string $password;

  /**
   * @var Request $request
   *   Stores the Request parameters of the request bring made
   */
  private Request $request;
  
  /**
   *   @var string $role
   *     Stores the Role of the User
   */
  private string $role;

  /**
   * constructor to initialize the Request variable
   */
  public function __construct(Request $request) {
    $this->request = $request;
  }

  /**
   * This Function Validates the fields after the login
   * 
   * @return string
   *   returns the message of the field validation status
   */
  // public function loginValidation(){

  // }

  /**
   * This Function validates the fields after gthe Signup
   * 
   *   @return string
   *     Returns a message afterthe validation of the fields
   */
  // public function signUpValidation(){

  // }

  /**
   * Checking if the user with given credentials already exists
   * 
   *   @param string $userName
   *     User name of the user to be verfified
   * 
   *   @param string $email
   *     Email of the user to be verified
   * 
   *   @param EntityManagerInterface $em
   *     Entity Manager Interface to interact with the Entity Class
   * 
   *   @return string
   *     Returns the Message based on the check
   */
  public function userExists(string $userName, string $email, EntityManagerInterface $em) {
    $userRepo = $em->getRepository(User::class);
    $userWithUserName = $userRepo->findOneBy(['userName' => $userName]);
    $userWithEmail = $userRepo->findOneBy(['email' => $email]);

    if (!empty($userWithUserName)) {
      return 'User Name Exists';
    }
    else if (!empty($userWithEmail)) {
      return 'User Email Exists';
    }
    else {
      return 'User Not Found';
    }
  }

  /**
   * Function to Register a new User
   * 
   *   @param EntityManagerInterface $em
   *     Entity manager object
   * 
   *   @return bool
   *     returns true or false based on the status of registration
   */
  private function registerUser(EntityManagerInterface $em) {
    try {
      $user = new User();
      $user->setFullName($this->fullName);
      $user->setUserName($this->userName);
      $user->setRole($this->role);
      $hashsePassword = password_hash($this->password, PASSWORD_DEFAULT);
      $user->setPassword($hashsePassword);
      return TRUE;
    }
    catch (Exception $e) {
      return FALSE;
    }
  }

}
