<?php
/**
 * @author Stephen Roca
 * @since 06/08/2012
 *
 * @param $organization - The selected organization's information
 * @param $president - The president of the selected organization
 * @param $treasurer - The treasurer of the selected organization
 * @param $advisor - The advisor of the selected organization
 * @param $officers - The remaining officers of the selected organization
 * @param $members - The members of the selected organization
 * @param $pending_members - The pending members of the selected organization
 */

// Define which view this view extends.
$this -> extend('/Common/common');

// Define any crumbs for this view.
echo $this -> Html -> addCrumb('All Organizations', '/organizations');
echo $this -> Html -> addCrumb($organization['Organization']['name'], '/organizations/view/' . $organization['Organization']['id']);

// Define any extra sidebar links for this view
$this -> start('sidebar');
$sidebar = array();

if ($lace || $isOfficer)
{
	$sidebar[] = $this -> Html -> link(__('Edit Information', true), array(
		'action' => 'edit',
		$organization['Organization']['id']
	));
	$sidebar[] = $this -> Html -> link(__('Edit Logo', true), array(
		'action' => 'addlogo',
		$organization['Organization']['id']
	));
	$sidebar[] = $this -> Html -> link(__('Submit Budget', true), array(
		'controller' => 'budgets',
		'action' => 'submit',
		$organization['Organization']['id']
	));

}
if ($orgViewDocumentsPerm)
{
	$sidebar[] = $this -> Html -> link(__('Documents', true), array(
		'controller' => 'documents',
		'action' => 'index',
		$organization['Organization']['id']
	));
	$sidebar[] = $this -> Html -> link(__('Roster', true), array(
		'controller' => 'memberships',
		'action' => 'index',
		$organization['Organization']['id']
	));
}

//MRE TO DO: add ledger page in budgets
$sidebar[] = $this -> Html -> link(__('Finance Ledger', true), array(
	'controller' => 'bills',
	'action' => 'ledger',
	$organization['Organization']['id']
));

if (!$orgJoinOrganizationPerm)
{
	$sidebar[] = $this -> Html -> link(__('Join Organization', true), array(
		'controller' => 'memberships',
		'action' => 'joinOrganization',
		$organization['Organization']['id']
	), null, __('Are you sure you want to join ' . $organization['Organization']['name'] . '?', true));
}

if ($orgAdminPerm)
{
	$sidebar[] = $this -> Html -> link(__('Delete Organization', true), array(
		'action' => 'delete',
		$organization['Organization']['id']
	), array('style' => 'color:red'), __('Are you sure you want to delete %s?', $organization['Organization']['name']));
}

echo $this -> Html -> nestedList($sidebar, array());
$this -> end();

// Define the main information for this view.
$this -> assign('title', $organization['Organization']['name']);
$this -> start('middle');
?>
<div style="display:inline-block;position:relative;width:100%">
<div style="float:left;width:50%;">
	<?php
	echo $this -> Html -> tag('h1', 'Officers:');
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	foreach ($presidents as $president)
	{
		if (isset($president['Membership']))
		{
			echo $this -> Html -> tableCells(array(
				$this -> Html -> link($president['Membership']['name'], 'mailto:' . $president['User']['email']),
				(!strcmp($president['Membership']['title'], $president['Membership']['role'])) ? $president['Membership']['title'] : $president['Membership']['title'] . " (" . $president['Membership']['role'] . ")"
			));
		}
	}
	foreach ($treasurers as $treasurer)
	{
		if (isset($treasurer['Membership']))
		{
			echo $this -> Html -> tableCells(array(
				$this -> Html -> link($treasurer['Membership']['name'], 'mailto:' . $treasurer['User']['email']),
				(!strcmp($treasurer['Membership']['title'], $treasurer['Membership']['role'])) ? $treasurer['Membership']['title'] : $treasurer['Membership']['title'] . " (" . $treasurer['Membership']['role'] . ")"
			));
		}
	}
	foreach ($advisors as $advisor)
	{
		if (isset($advisor['Membership']))
		{
			echo $this -> Html -> tableCells(array(
				$this -> Html -> link($advisor['Membership']['name'], 'mailto:' . $advisor['User']['email']),
				(!strcmp($advisor['Membership']['title'], $advisor['Membership']['role'])) ? $advisor['Membership']['title'] : $advisor['Membership']['title'] . " (" . $advisor['Membership']['role'] . ")"
			));
		}
	}
	foreach ($officers as $officer)
	{
		if (isset($officer['Membership']))
		{
			echo $this -> Html -> tableCells(array(
				$this -> Html -> link($officer['Membership']['name'], 'mailto:' . $officer['User']['email']),
				$officer['Membership']['title']
			));
		}
	}
	echo $this -> Html -> tableEnd();
	echo "</div>";
	echo $this -> Html -> div();
	echo $this -> Html -> image($organization['Organization']['logo_path'], array(
		'id' => 'logo',
		'style' => 'float:right;height:160px;'
	));
	echo "</div>";
	echo "</div>";
	// Print out the Organization's description and other
	// general information.
	echo $this -> Html -> tag('h1', 'Description');
	echo $this -> Html -> para('leftalign', $organization['Organization']['description']);
	echo $this -> Html -> nestedList(array(
		'Status: ' . $organization['Organization']['status'],
		'Organization Contact: ' . $this -> Html -> link($organization['Organization']['organization_contact'], 'mailto:'.$organization['Organization']['organization_contact_campus_email']),
		'External Website: ' . $this -> Html -> link($organization['Organization']['website']),
		'Meetings: ' . $organization['Organization']['meeting_information']
	), array('id' => 'description'));
	echo "<br/><br/>";
	$this -> end();
?>