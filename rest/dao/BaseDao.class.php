<?php
require_once __DIR__ . '/../Config.class.php';

class BaseDao
{

  private $conn;

  private $table_name;

  /**
   * constructor of dao class
   */
  public function __construct($table_name)
  {
    $this->table_name = $table_name;
    $servername = Config::DB_HOST();
    $username = Config::DB_USERNAME();
    $password = Config::DB_PASSWORD();
    $schema = Config::DB_SCHEME();
    $port = Config::DB_PORT();
    $this->conn = new PDO("mysql:host=$servername;dbname=$schema;port=$port", $username, $password);
    // set the PDO error mode to exception
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  public function get_all($user_id)
  {
    $stmt = $this->conn->prepare("SELECT t.* FROM " . $this->table_name . " t 
    INNER JOIN users u ON u.id = :user_id WHERE u.admin = 1");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function get_by_id($user_id, $id)
  {
    $stmt = $this->conn->prepare("SELECT t.* FROM " . $this->table_name . " t 
    INNER JOIN users u ON u.id = :user_id WHERE u.admin = 1 AND t.id = :id");
    $stmt->execute(['user_id' => $user_id, 'id' => $id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return reset($result);
  }

  public function add($user_id, $entity)
  {
    // Check user
    $user_query = "SELECT admin FROM users WHERE id = :user_id";

    $user_stmt = $this->conn->prepare($user_query);
    $user_stmt->execute(['user_id' => $user_id]); // sql injection prevention
    $user = $user_stmt->fetchAll(PDO::FETCH_ASSOC);
    $user = reset($user);
    // return $user;

    if ($user['admin'] == 1) {
      $query = "INSERT INTO " . $this->table_name . " (";
      foreach ($entity as $column => $value) {
        $query .= $column . ", ";
      }
      $query = substr($query, 0, -2);
      $query .= ") VALUES (";
      foreach ($entity as $column => $value) {
        $query .= ":" . $column . ", ";
      }
      $query = substr($query, 0, -2);
      $query .= ")";

      $stmt = $this->conn->prepare($query);
      $stmt->execute($entity); // sql injection prevention
      $entity['id'] = $this->conn->lastInsertId();
      return $entity;
    } else {
      throw new Exception("Unauthorized access!");
    }
  }

  public function update($user_id, $id, $entity, $id_column = "id")
  {
    // Check user
    $user_query = "SELECT admin FROM users WHERE id = :user_id";

    $user_stmt = $this->conn->prepare($user_query);
    $user_stmt->execute(['user_id' => $user_id]); // sql injection prevention
    $user = $user_stmt->fetchAll(PDO::FETCH_ASSOC);
    $user = reset($user);
    // return $user;

    if ($user['admin'] == 1) {
      $query = "UPDATE " . $this->table_name . " SET ";
      foreach ($entity as $name => $value) {
        $query .= $name . "= :" . $name . ", ";
      }
      $query = substr($query, 0, -2);
      $query .= " WHERE ${id_column} = :id";

      $stmt = $this->conn->prepare($query);
      $entity['id'] = $id;
      $stmt->execute($entity);
      return $entity;
    } else {
      throw new Exception("Unauthorized access!");
    }
  }

  public function delete($user_id, $id)
  {
    // Check user
    $user_query = "SELECT admin FROM users WHERE id = :user_id";

    $user_stmt = $this->conn->prepare($user_query);
    $user_stmt->execute(['user_id' => $user_id]); // sql injection prevention
    $user = $user_stmt->fetchAll(PDO::FETCH_ASSOC);
    $user = reset($user);
    // return $user;

    if ($user['admin'] == 1) {
      $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id=:id");
      $stmt->bindParam(':id', $id); // SQL injection prevention
      $stmt->execute();
    } else {
      throw new Exception("Unauthorized access!");
    }
  }

  protected function query($query, $params)
  {
    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  protected function query_unique($query, $params)
  {
    $results = $this->query($query, $params);
    return reset($results);
  }

  protected function query_entity($query, $entity)
  {
    $stmt = $this->conn->prepare($query);
    $stmt->execute($entity);
    $entity['id'] = $this->conn->lastInsertId();
    return $entity;
  }
}
