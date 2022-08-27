<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/BookDao.class.php';

class BookService extends BaseService{

  public function __construct(){
    parent::__construct(new BookDao());
  }
  
  public function get_public_books(){
    return $this->dao->get_public_books();
  }

  public function get_public_book($id){
    $result = $this->dao->get_public_book($id);
    if(empty($result)) return null;
    else{
      return $result;
    }
  }
  
  public function get_public_books_by_author($id){
    $result = $this->dao->get_public_books_by_author($id);
    if(empty($result)) return null;
    else{
      return $result;
    }
  }

}
?>
