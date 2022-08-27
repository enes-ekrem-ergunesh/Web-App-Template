<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/UserbookDao.class.php';

class UserbookService extends BaseService{

  public function __construct(){
    parent::__construct(new UserbookDao());
  }
  
  public function get_userbooks_shelf($user){
    return $this->dao->get_userbooks_shelf($user['id']);
  }
  
  public function get_userbook_shelf($user, $book_id){
    return $this->dao->get_userbook_shelf($user['id'], $book_id);
  }
  
  public function add_userbook_shelf($user, $book_id){
    return $this->dao->add_userbook_shelf($user['id'], $book_id);
  }
  
  public function update_userbook_shelf($user, $book_id, $bookmark){
    $userbook = $this->dao->get_userbook_shelf($user['id'], $book_id);
    if($userbook===false) throw new Exception("Book doesn't exist in the shelf");
    else{
      return $this->dao->update_userbook_shelf($userbook['userbook_id'], $bookmark);
    }
  }
  
  public function delete_userbook_shelf($user, $book_id){
    $userbook = $this->dao->get_userbook_shelf($user['id'], $book_id);
    if($userbook===false) throw new Exception("Book doesn't exist in the shelf");
    else{
      return $this->dao->delete_userbook_shelf($userbook['userbook_id']);
    }
  }

}
?>
