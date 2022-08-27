<?php
require_once __DIR__.'/BaseDao.class.php';

class UserbookDao extends BaseDao{

  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("userbooks");
  }

  public function get_userbooks_shelf($user_id){
    $query = "SELECT b.*, a.name as author_name, ub.id as 'userbook_id', ub.user_id as 'userbook_user_id', ub.bookmark 
    FROM userbooks ub 
    INNER JOIN books b ON ub.book_id = b.id 
    INNER JOIN authors a ON b.author_id = a.id 
    WHERE ub.user_id = :user_id";
    
    return $this->query($query, ['user_id' => $user_id]);
  }

  public function get_userbook_shelf($user_id, $book_id){
    $query = "SELECT b.*, ub.id as 'userbook_id', ub.user_id as 'userbook_user_id', ub.bookmark FROM userbooks ub INNER JOIN books b ON ub.book_id = b.id WHERE ub.user_id = :user_id AND ub.book_id = :book_id";
    
    return $this->query_unique($query, ['user_id' => $user_id, 'book_id' => $book_id]);
  }

  public function add_userbook_shelf($user_id, $book_id){
    $query = "INSERT INTO userbooks(user_id, book_id) VALUES (:user_id, :book_id) ";
    
    $this->query_unique($query, ['user_id' => $user_id, 'book_id' => $book_id]);
  }

  public function update_userbook_shelf($userbook_id, $bookmark){
    $query = "UPDATE userbooks SET bookmark = :bookmark WHERE id = :userbook_id";

    $this->query_unique($query, ['userbook_id' => $userbook_id, 'bookmark' => $bookmark]);

    return ["message" => "bookmark updated"];
  }

  public function delete_userbook_shelf($userbook_id){
    $query = "DELETE FROM userbooks WHERE id = :userbook_id";  

    $this->query_unique($query, ['userbook_id' => $userbook_id]);

    return ["message" => "Removed from the shelf"];
  }



}

?>
