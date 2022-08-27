<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/AvatarDao.class.php';

class AvatarService extends BaseService{

  public function __construct(){
    parent::__construct(new AvatarDao());
  }
}
?>
