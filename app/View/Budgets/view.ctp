<?php
$this -> extend('/Common/common');

$this -> assign('title', "Budgets");

$this -> start('middle');
echo $this -> Form -> create();
echo $this -> Html -> tag('h1', 'Overall');
echo $this -> Html -> tableBegin(array('class' => 'listing'));
{
	echo $this -> Html -> tableCells(array(
		'Fiscal Year',
		$this -> Form -> input('fiscal_year', array(
			'type' => 'select',
			'label' => false,
			'options' => $fiscal_years,
			'onchange' => 'submit()'
		))
	));
	echo $this -> Html -> tableCells(array(
		'Total Amount Requested',
		$this -> Number -> currency($total_requested, 'USD')
	));
	echo $this -> Html -> tableCells(array(
		'Total Requested Change',
		$this -> Number -> currency(($total_requested - $ly_total_requested), 'USD')
	));
	echo $this -> Html -> tableCells(array(
		'Total Amount Allocated',
		(($total_allocated == null) ? '$0.00' : $this -> Number -> currency($total_allocated, 'USD'))
	));
	echo $this -> Html -> tableCells(array(
		'Total Allocated Change',
		$this -> Number -> currency(($total_allocated - $ly_total_allocated), 'USD')
	));
}
echo $this -> Html -> tableEnd();

echo $this -> Html -> tag('h1', 'Individual');

echo $this -> Html -> tableBegin(array('class' => 'listing'));
{
	echo $this -> Html -> tableCells(array(
		'Tier',
		$this -> Form -> input('tier', array(
			'type' => 'select',
			'label' => false,
			'options' => array(
				'All',
				'I',
				'II',
				'III'
			),
			'onchange' => 'submit()'
		))
	));
	echo $this -> Html -> tableCells(array(
		'Organization',
		$this -> Form -> input('org_id', array(
			'type' => 'select',
			'label' => false,
			'options' => $organizations,
			'onchange' => 'submit()',
			'value' => $org_id
		))
	));
	if (count($budgets) == 1)
	{
		echo $this -> Html -> tableCells(array(
			'State',
			$this -> Form -> input('state', array(
				'type' => 'select',
				'label' => false,
				'options' => array(
					'JFC' => 'JFC',
					'UHRC'=>'UHRC',
					'GSSC'=>'GSSC',
					'UHR'=>'UHR',
					'GSS'=>'GSS',
					'CONF'=>'CONF',
					'Final'=>'Final'
				),
				'value' => $state,
				'onchange' => 'submit()'
			))
		));
	}
}
echo $this -> Html -> tableEnd();

echo $this -> element('/budgets/organization_accordions');

echo $this -> Form -> submit('Save');
echo $this -> Form -> submit('Save and Continue', array('name' => "data[redirect]"));
$this -> end();
