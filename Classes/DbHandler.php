<?php 
namespace App;

use Dotenv\Dotenv;
use Exception;
use PDO;

$dotenv = Dotenv::createImmutable("../");
$dotenv->load();

class DbHandler{

  /**
   * Stores the Db connection object
   */
  private mixed $conn;

  public function __construct(){
    $dbName = 'mysql:host=' . $_ENV['HOST'] . ';dbname=' . $_ENV['DB_NAME'];
    $username = $_ENV['USER_NAME'];
    $password = $_ENV['PASSWORD'];
    $this->conn = new PDO($dbName, $username, $password);
  }

  /**
   * Functionto fetch books by name
   * 
   * @param int
   * 
   * @return array
   */
  public function fetchByName(int $count) {
    $sql = 'SELECT * FROM books order by title LIMIT 9 OFFSET :count';
    $result = $this->conn->prepare($sql);
    $result->bindParam("count", $count, PDO::PARAM_INT);
    $result->execute();
    $array = $result->fetchAll(PDO::FETCH_ASSOC);
    return $array;
  }
  
  /**
   * Function to add a song to wishlist
   * @param int
   */
  public function addToWishlist(int $id, int $uId) {
    $sql = 'INSERT into wislist(book_id, user_id) values(:bId, :uId)';
    $result = $this->conn->prepare($sql);
    $result->bindParam("bId", $id, PDO::PARAM_INT);
    $result->bindParam("uId", $uId, PDO::PARAM_INT);
    $result->execute();
    $array = $result->fetchAll(PDO::FETCH_ASSOC);
    return $array;
  }
  public function removeFromWishlist(int $id, int $uId) {
    $sql = 'DELETE FROM wishlist WHERE book_id = :bId AND user_id = :uId';
    $result = $this->conn->prepare($sql);
    $result->bindParam("bId", $id, PDO::PARAM_INT);
    $result->bindParam("uId", $uId, PDO::PARAM_INT);
    $result->execute();
    $array = $result->fetchAll(PDO::FETCH_ASSOC);
    return $array;
  }

}