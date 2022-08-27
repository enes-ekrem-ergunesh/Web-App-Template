<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/UserDao.class.php';

class UserService extends BaseService{

  public function __construct(){
    parent::__construct(new UserDao());
  }
  
  public function sign_up($entity){
    return $this->dao->sign_up($entity);
  }
  
  public function update_current($entity, $id){
    return $this->dao->update_current($entity, $id);
  }
}
?>
