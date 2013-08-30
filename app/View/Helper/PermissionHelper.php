<?php

//@TODO include comments
class PermissionHelper extends AppHelper 
{
   public $helpers = array('Session');
   public $components = array('Acl', 'Session');
   // @TODO probably not need. isUser will give the same result
   public function isLoggedIn()
   {
      if ($this -> Session -> read('Auth.User') != '')
      {
         return true;
      }
      return false;
   }
   
   public function isUser()
   {
      return ($this -> isLevel('user') || $this -> isLevel('sga') || $this -> isLevel('admin'));
   }
   
   public function isMember($user_id, $org_id)
   {
      /**
       * @TODO After implementing memberships and organizations
       * Load the membership model and do a find for that org_id
       * and user_id combination
       */
    
   }
   
   public function isSGA()
   {
      return ($this -> isLevel('sga') || $this -> isLevel('admin'));
   }
   
   public function isAdmin()
   {
      return $this -> isLevel('admin');
   }
   
   private function isLevel($level)
   {
      $session_level = $this -> Session -> read('User.level');
      if ($session_level == $level)
      {
         return true;
      }
      return false;
   }
   
   public function check($viewPath, $view)
   {
   	return $this -> Acl -> check($viewPath . "/" . $view, 'Role/' . $this -> Session -> read('User.level'));
   }
}