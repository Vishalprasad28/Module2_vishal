<?php
namespace App;
// require("../vendor/autoload.php");
use Dotenv\Dotenv;
use PDO;

$dotenv = Dotenv::createImmutable("../");
$dotenv->load();

/**
 * Employee Class That Contains the Employee's Details.
 */

class User {
  /**
   * @var string
   *   Full name
   */
  private string $fullName;

  /**
   * @var string
   *   User name of User
   */
  private string $uName;

  /**
   * @var string
   */
  private string $role;

  /**
   * @var string
   *   password of User
   */
  private string $password;

  /**
   * @var mixed
   */
  private $conn;

  /**
   * Constructor for the Initialization
   * 
   * 
   * @param string uname
   * 
   * @param string $password
   */
  public function __construct(string $uName, string $password) {

    $this->uName = $this->trimData($uName);
    $this->password = stripslashes(trim($password));
    $dbName = 'mysql:host=' . $_ENV['HOST'] . ';dbname=' . $_ENV['DB_NAME'];
    $username = $_ENV['USER_NAME'];
    $password = $_ENV['PASSWORD'];
    $this->conn = new PDO($dbName, $username, $password);

  }

  /**
   * userName getter
   * 
   * @return string
   */
  public function getUserName() {
    return $this->uName;
  }

    /**
   * userName getter
   * 
   * @return string
   */
  public function getFullName() {
    return $this->fullName;
  }

  /**
   * Function For the FieldValidation
   * 
   * @return string
   */
  public function checkUser(): string {
    if (!$this->userExists()) {
      return 'User Not Found';
    } 
    elseif (!$this->passwordValidation($this->uName)) {
      return 'Wrong Password';
    }
    else {
      $this->fullName = $this->fullNameGetter();
      $this->role = $this->roleGetter();
      return 'success';
    }
  }
  /**
   * Function to check whether the user already exists
   * 
   * @return bool
   */
  public function userExists() {
    $sql = 'SELECT * FROM user WHERE user_name=?';
    $result = $this->conn->prepare($sql);
    $result->execute([$this->uName]);
    if (count($result->fetchAll()) > 0)
      return TRUE;
    else
      return FALSE;
  }

  /**
   * Getter for Full Name;
   * @param string $uName
   */
  public function fullNameGetter() {
    $sql = 'SELECT * FROM user WHERE user_name=?';
    $result = $this->conn->prepare($sql);
    $result->execute([$this->uName]);
    $array = $result->fetch();
    return $array['full_name'];
  }

  /**
   * Getter for Role
   * @param string $uName
   */
  public function roleGetter() {
    $sql = 'SELECT * FROM user WHERE user_name=?';
    $result = $this->conn->prepare($sql);
    $result->execute([$this->uName]);
    $array = $result->fetch();
    return $array['role'];
  }

  /**
   * FUnction to validate the password
   * 
   * @param string
   */
  public function passwordValidation(string $uName) {
    $sql = 'SELECT * FROM user WHERE user_name=?';
    $result = $this->conn->prepare($sql);
    $result->execute([$this->uName]);
    $array = $result->fetch();
    if (count($array) > 0) {
      $hashedPassword = $array['password'];
      if (password_verify($this->password, $hashedPassword)) {
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
    return FALSE;
  }
  /** Function to trim the input data
   * 
   * @param string $data
   *   takes the input field values
   * 
   * @return string
   */
  private function trimData(string $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

}

?>