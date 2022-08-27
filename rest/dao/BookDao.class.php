<?php
require_once __DIR__.'/BaseDao.class.php';

class BookDao extends BaseDao{

  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("books");
  }

  public function get_public_books(){
    $query = "SELECT b.id, b.name, b.cover, b.author_id, a.name as author_name FROM books b 
    INNER JOIN users u ON b.user_id = u.id 
    INNER JOIN authors a ON b.author_id = a.id
    WHERE u.admin = 1 AND b.activity = 'active' ";
    
    return $this->query($query, null);
  }
  
  public function get_public_book($id){
    $query = "SELECT b.id, b.name, b.cover, b.author_id, a.name as author_name FROM books b 
    INNER JOIN users u ON b.user_id = u.id 
    INNER JOIN authors a ON b.author_id = a.id
    WHERE u.admin = 1 AND b.activity = 'active' AND b.id = :id";
    
    return $this->query($query, ['id' => $id]);
  }

  public function get_public_books_by_author($id){
    $query = "SELECT b.id, b.name, b.cover, b.author_id, a.name as author_name FROM books b 
    INNER JOIN users u ON b.user_id = u.id 
    INNER JOIN authors a ON b.author_id = a.id
    WHERE u.admin = 1 AND b.activity = 'active' AND a.id = :id";
    
    return $this->query($query, ['id' => $id]);
  }

}

?>
