<?php

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 */
class AppController extends Controller
{
   public $helpers = array('Js', 'Session', 'Permission', 'Html');
   public $components = array(
   	  'Acl',
      'Auth' => array('loginRedirect' => array(
            'controller' => 'users',
            'action' => 'index'
         ), 'logoutRedirect'=>array('controller'=>'users', 'action'=> 'index'),
         'authError'=> "You cannot access that page",
         'authorize'=>array('Controller', 'Actions' => array('actionPath' => 'controllers'))),
      'Session'
   );
   
   /**
    * Users that aren't logged in have access to the following actions.
    */
   public function beforeFilter(){
      $this -> Auth -> allow('index');
   }
   
   public function isAuthorized($user){
      return true;
   }
   
   
}
