<?php
abstract class BaseService {

  protected $dao;

  public function __construct($dao){
    $this->dao = $dao;
  }

  public function get_all($user){
    $result = $this->dao->get_all($user['id']);
    if(count($result) == 0){
      throw new Exception("Unauthorized access!");
    }
    return $result;
  }

  public function get_by_id($user, $id){
    $result = $this->dao->get_by_id($user['id'], $id);
    if($result == false){
      throw new Exception("Unauthorized access or data not found!");
    }
    return $result;
  }

  public function add($user, $entity){
    return $this->dao->add($user['id'], $entity);
  }

  public function update($user, $id, $entity){
    return $this->dao->update($user['id'], $id, $entity);
  }

  public function delete($user, $id){
    return $this->dao->delete($user['id'], $id);
  }
}
