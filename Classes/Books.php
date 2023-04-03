<?php 
namespace App;

use Dotenv\Dotenv;
use Exception;
use PDO;

$dotenv = Dotenv::createImmutable("../");
$dotenv->load();
class Books {

  public string $id;

  public string $title;

  public string $genere;

  public string $author;

  public int $rating;

  public string $category;

  public string $date;

  public mixed $cover;

  private mixed $conn;
  /**
   * Initializing the variables
   */
  public function __construct(string $id, string $title, mixed $cover, string $genere, string $author, int $rating, string $category, int $date) {
    $this->id = $this->trimData($id);
    $this->title = $this->trimData($title);
    $this->genere = $this->trimData($genere);
    $this->author = $this->trimData($author);
    $this->cover = $cover;
    $this->rating = $rating;
    $this->category = $this->trimData($category);
    $this->date =$date;
    $dbName = 'mysql:host=' . $_ENV['HOST'] . ';dbname=' . $_ENV['DB_NAME'];
    $username = $_ENV['USER_NAME'];
    $password = $_ENV['PASSWORD'];
    $this->conn = new PDO($dbName, $username, $password);
  }

  /**
   * Field validation function
   * 
   * @return string
   */
  public function validate() {
    if (empty($this->id) || empty($this->title) || empty($this->genere) || empty($this->date)) {
      return 'Field Empty';
    }
    elseif ($this->date > 9999 || $this->date < 0) {
      return 'Invalid Year';
    }
    elseif (!$this->nameValidation($this->author)) {
      return 'Invalid author Name Formate';
    }
    elseif (!$this->nameValidation($this->category)) {
      return 'Invalid category formate';
    }
    elseif ($this->rating> 5 || $this->rating < 0) {
      return 'invalid Rating Formate';
    }
    elseif (!$this->picFormate()) {
      return 'Invalid Picture Fotmate';
    }
    else {
      if (!$this->addBook()) {
        return 'Failed to add';
      }
      else {
        return 'uploaded';
      }
      
    }
  }

  /**
   * Storing to database
   */
  public function addBook() {
    try {
      $path =  'cover/' . $this->cover['name'];
      $sql = 'INSERT into books(book_id,path,title, genere, date, author, rating,catagory) values(?,?,?,?,?,?,?,?);';
      $result = $this->conn->prepare($sql);
      $result->execute([$this->id, $path, $this->title, $this->genere, $this->date, $this->author, $this->rating, $this->category]);
      return TRUE;
    }
    catch (Exception $e) {
      return FALSE;
    }
  }

  /**
   * Function to validate the fields
   * 
   * @return bool
   */
  private function nameValidation(string $name) {
    if ($name == "") {
      return FALSE;
    }
    elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }
  
    /**
   * Function to validate the picture formate
   * 
   * @return bool
   */
  public function picFormate() {
    if(isset($this->cover) && $this->cover['name'] != '') {
      $target_dir = '../public/cover/';
      $file_type = $this->cover["type"];
      $file_name= $this->cover["name"];
      $file_size = $this->cover["size"];
      if ($file_type != "image/jpg" && $file_type != "image/png" && $file_type != "image/jpeg") {
        return FALSE;
      }
      elseif ($file_size > 100000000) {
        return FALSE;
      }
      elseif (!file_exists($target_dir . $file_name)) {
        if (move_uploaded_file($this->cover["tmp_name"], $target_dir . $file_name)) {
          return TRUE;
        }
        else {
          return FALSE;
        }
      }
      else {
        return TRUE;
      }
    }
    else {
      return FALSE;
    }
  }

  /**
   * Function for trimming data
   * 
   * @param string
   */
  private function trimData(string $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  

}
