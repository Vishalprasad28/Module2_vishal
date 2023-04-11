<?php 
namespace App\UserService;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * This Class Conatins the User credentials like Full name username and more
 */

class ActiveUser {

  /**
   * Importing the FieldValidation Trait to use the Field Validator Functions
   */

  use FieldValidation;

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
   * @var string $confPwd
   */
  private string $confPwd;

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
   *   @var EntityManagerInterface $em
   *     Stores the entity manager object
   */
  private EntityManagerInterface $em;
  /**
   * constructor to initialize the Request variable
   * 
   *   @param Request $request
   *     Request Variable
   * 
   *   @param EntityManagerInterface $em
   *     Entity Manager Object to deal with Entities
   */
  public function __construct(Request $request, EntityManagerInterface $em) {
    $this->request = $request;
    $this->em = $em;
  }

  /**
   * This Function Validates the fields after the login
   * 
   * @return string
   *   returns the message of the field validation status
   */
  public function loginValidation() {
    $this->userName = $this->trimdata($this->request->request->get('uName'));
    $this->password = stripcslashes(trim($this->request->request->get('pwd')));

    if (!$this->userExists()) {
      return 'User Not Found';
    }
    elseif (!$this->passwordValidation()) {
      return "Wrong Password";
    }
    else {
      $this->sessionUser();
      return 'success';
    }
  }

  /**
   * This Function validates the fields after gthe Signup
   * 
   *   @return string
   *     Returns a message afterthe validation of the fields
   */
  public function signUpValidation(){
    $this->fullName = $this->trimData($this->request->get('fullName'));
    $this->role = $this->request->get('role');
    $this->userName = $this->trimdata($this->request->request->get('uName'));
    $this->password = stripSlashes(trim($this->request->request->get('pwd')));
    $this->confPwd = $this->request->request->get('confPwd');
    
    if (!$this->nameValidation($this->fullName)) {
      return 'Invalid Name Formate';
    }
    elseif (empty($this->role)) {
      return 'Select a role';
    }
    elseif (!$this->userNameValidation()) {
      return 'Invalid User Name Formate';
    }
    elseif (!$this->validatePassword()) {
      return 'Invalid Pasword Formate';
    }
    elseif (!$this->confPwdmatcher()) {
      return 'Confirm Your Password carefully';
    }
    elseif (!$this->userExists()) {
      if (!$this->registerUser()) {
        return 'Failed to SignUp';
      }
      else {
        $this->sessionUser();
        return 'Registered';
      }
    }
    else {
      return 'User Already Exists';
    }
  }

  /**
   * Function to store the user info to a session
   * 
   *   @return void
   */
  private function sessionUser() {
    $user = $this->em->getRepository(User::class)->findOneBy(['userName' => $this->userName]);
    $_SESSION['user'] = $user->getId();
    $_SESSION['login'] = TRUE;
  }

  /**
   * Checking if the user with given credentials already exists
   * 
   *   @param string $userName
   *     User name of the user to be verfified
   * 
   *   @return bool
   *     Returns True or False based on the check
   */
  private function userExists() {
    $userRepo = $this->em->getRepository(User::class);
    $userWithUserName = $userRepo->findOneBy(['userName' => $this->userName]);

    if (!empty($userWithUserName)) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Function validates the user's password during the login
   * 
   *   @return bool
   *     Based on check returns trueor false
   */
  private function passwordValidation() {
    $userRepo = $this->em->getRepository(User::class);
    try {
      $user = $userRepo->findOneBy(['userName' => $this->userName]);
      if (password_verify($this->password, $user->getPassword())) {
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
    catch(Exception $e) {
      return FALSE;
    }
  }
  /**
   * Function to Register a new User
   * 
   *   @return bool
   *     Returns true or false based on the status of registration
   */
  private function registerUser() {
    try {
      $user = new User();
      $user->setFullName($this->fullName);
      $user->setUserName($this->userName);
      $user->setRole($this->role);
      $hashsePassword = password_hash($this->password, PASSWORD_DEFAULT);
      $user->setPassword($hashsePassword);
      $this->em->persist($user);
      $this->em->flush();
      return TRUE;
    }
    catch (Exception $e) {
      return FALSE;
    }
  }
}
