<?php
/**
 * Path: jacketpages.gatech.edu/users/edit/$id
 * Passed variables:
 * @param $user - The User Model array for an individual user
 *
 * @author Stephen Roca
 * @since 3/27/2012
 */
$this -> Html -> addCrumb($user['User']['name'], '/users/view/' . $user['User']['id']);
$this -> Html -> addCrumb('Edit Profile', '/users/edit/' . $user['User']['id']);

$this -> extend('/Common/common');
$this -> assign('title', 'Edit Profile');

$this -> start('middle');

// Begin User Edit Form
echo $this -> Form -> create();
echo $this -> Form -> hidden('id');
echo $this -> Form -> input('first_name', array(
	'label' => 'First Name',
	array('id' => 'block')
));
echo $this -> Form -> input('last_name', array('label' => 'Last Name'));
if($admin)
{
	echo $this -> Form -> input('level', array('label' => 'Level', 'options' => array(
	'admin' => 'Administrator',
	'lace' => 'Leadership and Civic Engagment',
	'sga_exec' => 'SGA Executive',
	'sga_user' => 'SGA User',
	'gt_member' => 'General GT User'
	)));
}
echo $this -> Form -> input('email', array('label' => 'Email'));
echo $this -> Form -> end('Submit');
// End User Edit Form
$this -> end();
?>