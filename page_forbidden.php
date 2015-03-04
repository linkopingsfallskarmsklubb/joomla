<?php  defined('C5_EXECUTE') or die("Access Denied."); 


$v   = View::getInstance();
$c   = $v->getCollectionObject();
$cID = $c->getCollectionID();
$p   = Page::getByID($cID);
$url = DIR_REL .'/medlemslogin/logga_in/?url=' . DIR_REL . $p->getCollectionPath();


header('location: '. $url);

?>