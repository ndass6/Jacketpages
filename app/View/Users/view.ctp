<?php
/**
 * Path: Jacketpages/users/view/$id
 * Passed variables:
 * @param $user - The User Model array for an indivdual user
 *
 * @author Stephen Roca
 * @since 03/22/2012
 */

//TODO Add in more information about the user. Location etc.

// Add the appropriate breadcrumbs
$this -> Html -> addCrumb($user['User']['name'], '/users/view/' . $user['User']['id']);
$this -> extend('/Common/common');
$links = array();
if ($userEditPerm)
{
	$links[] = $this -> Html -> link(__('Edit Profile', true), array(
		'action' => 'edit',
		$user['User']['id']
	));
}
if ($userDeletePerm)
{
	$links[] = $this -> Html -> link(__('Delete Profile', true), array(
		'action' => 'delete',
		$user['User']['id']
	), array('style' => 'color:red'), __('Are you sure you want to delete %s?', $user['User']['name']));
}

$this -> start('sidebar');
echo $this -> Html -> nestedList($links, array());
$this -> end();

$this -> assign('title', $user['User']['name']);

$this -> start('middle');
?>
<table class='listing' id='halftable'>
	<?php
	echo $this -> Html -> tableCells(array(
		'GT Username',
		$user['User']['gt_user_name']
	));
	echo $this -> Html -> tableCells(array(
		'Email',
		$user['User']['email']
	));
	?>
</table>
<?php
echo $this -> Html -> tag('h1', 'Executive Positions');
?>
<table class='listing'>
	<?php
	echo $this -> Html -> tableHeaders(array(
		'Organization',
		'Title',
		'Start Date',
		'End Date'
	));
	foreach ($memberships as $membership)
	{
		if ($membership['Membership']['role'] != 'Member')
		{
			echo $this -> Html -> tableCells(array(
				$this -> Html -> link($membership['Organization']['name'], array(
					'controller' => 'organizations',
					'action' => 'view',
					$membership['Organization']['id']
				)),
				$membership['Membership']['title'],
				$membership['Membership']['start_date'],
				$membership['Membership']['end_date']
			));
		}
	}
	?>
</table>
<?php
echo $this -> Html -> tag('h1', 'General Affiliations');
?>
<table class='listing'>
	<?php
	echo $this -> Html -> tableHeaders(array(
		'Organization',
		'Title',
		'Start Date',
		'End Date'
	));
	foreach ($memberships as $membership)
	{
		if ($membership['Membership']['role'] == 'Member')
		{
			echo $this -> Html -> tableCells(array(
				$this -> Html -> link($membership['Organization']['name'], array(
					'controller' => 'organizations',
					'action' => 'view',
					$membership['Organization']['id']
				)),
				$membership['Membership']['title'],
				$membership['Membership']['start_date'],
				$membership['Membership']['end_date']
			));
		}
	}
	?>
</table>
<?php
$this -> end();
?>