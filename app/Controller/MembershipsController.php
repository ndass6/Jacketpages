<?php
/**
 * Memberships Controller
 *
 * @author Stephen Roca
 * @since 06/08/2012
 */
class MembershipsController extends AppController
{
	public $helpers = array(
		'Form',
		'Html'
	);

	// Add in or condition to check dates greater than today.
	public function index($id = null)
	{
		$this -> loadModel('Membership');
		$db = ConnectionManager::getDataSource('default');
		$officers = $this -> Membership -> find('all', array(
			'conditions' => array('AND' => array(
					'Membership.org_id' => $id,
					'Membership.role <>' => 'Member',
					'OR' => array(
						$db -> expression('Membership.end_date >= NOW()'),
						'Membership.end_date' => null
					)
				)),
			'fields' => array(
				'Membership.role',
				'Membership.name',
				'Membership.status',
				'Membership.title',
				'Membership.id',
			),
			'recursive' => 0
		));

		$members = $this -> Membership -> find('all', array(
			'conditions' => array('AND' => array(
					'Membership.org_id' => $id,
					'Membership.role' => 'Member',
					'OR' => array(
						$db -> expression('Membership.end_date >= NOW()'),
						'Membership.end_date' => null
					)
				)),
			'fields' => array(
				'Membership.role',
				'Membership.name',
				'Membership.status',
				'Membership.title',
				'Membership.id',
			)
		));
		$pending_members = $this -> Membership -> find('all', array('conditions' => array('AND' => array(
					'Membership.status' => 'Pending',
					'Membership.org_id' => $id
				))));
		$this -> set('officers', $officers);
		$this -> set('members', $members);
		$this -> set('pending_members', $pending_members);
		$this -> set('orgId', $id);
	}

	/**
	 * Edits an individual Membership
	 * @param id - a membership's id
	 */
	public function edit($mem_id = null, $org_id =null)
	{
		$this -> Membership -> id = $mem_id;
		if ($this -> request -> is('get'))
		{
			$this -> request -> data = $this -> Membership -> read();
			$this -> set('membership', $this -> Membership -> read(null, $mem_id));
		}
		else
		{
			if ($this -> Membership -> save($this -> request -> data))
			{
				$this -> Session -> setFlash('The membership has been saved.');
				$this -> redirect(array('action' => 'index', $org_id));
			}
			else
			{
				$this -> Session -> setFlash('Unable to edit the membership.');
			}
		}
	}

	public function delete($id = null, $orgId = null)
	{
		if (!$id)
		{
			$this -> Session -> setFlash(__('Invalid ID for membership', true));
			$this -> redirect(array(
				'controller' => 'memberships',
				'action' => 'index',
				$orgId
			));
		}
		$this -> Membership -> id = $id;
		if ($this -> Membership -> saveField('end_date', date("Y-m-d")))
		{
			$this -> Session -> setFlash(__('Membership deleted.', true));
			$this -> redirect(array(
				'controller' => 'memberships',
				'action' => 'index',
				$orgId
			));
		}
		$this -> Session -> setFlash(__('Membership was not deleted.', true));
		$this -> redirect(array(
			'controller' => 'memberships',
			'action' => 'index',
			$orgId
		));
	}

	public function accept($id = null, $orgId = null)
	{
		if (!$id)
		{
			$this -> Session -> setFlash(__('Invalid ID for membership', true));
			$this -> redirect(array(
				'controller' => 'memberships',
				'action' => 'index'
			));
		}
		$this -> Membership -> id = $id;
		$this -> Membership -> set('status', 'Active');
		$this -> Membership -> set('start_date',date("Y-m-d"));
		if ($this -> Membership -> save())
		{
			$this -> Session -> setFlash('The member has been accepted.');
			$this -> redirect(array(
				'controller' => 'memberships',
				'action' => 'index',
				$orgId
			));
		}
		else
		{
			$this -> Session -> setFlash('Unable to accept the member.');
		}

	}

	public function joinOrganization($org_id)
	{
		$this -> Membership -> set('org_id', $org_id);
		$this -> Membership -> set('user_id', $this -> Session -> read('User.id'));
		$this -> Membership -> set('role', 'Member');
		$this -> Membership -> set('title', 'Member');
		$this -> Membership -> set('status', 'Pending');
		$this -> Membership -> set('room_reserver', 'No');

		if ($this -> Membership -> save())
			$this -> redirect($this -> referer());
	}

	public function acceptAll($org_id)
	{
		$pendingMemberships = $this -> Membership -> findAllByOrgIdAndStatus($org_id,'Pending');
		for($i = 0; $i < count($pendingMemberships); $i++)
		{
			$pendingMemberships[$i]['Membership']['status'] = 'Active';
			$pendingMemberships[$i]['Membership']['start_date'] = date("Y-m-d");
		}
		$this -> Membership -> saveAll($pendingMemberships);
		$this -> redirect(array('controller' => 'organizations', 'action'=> 'view',$org_id));
	}

}
?>