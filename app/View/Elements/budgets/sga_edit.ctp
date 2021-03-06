<?php
echo $this -> Html -> script('budgets/sga_edit');
echo $this -> Html -> script('validation/validation');
$column_names = array('');
$subtotals = array(
	0,
	0,
	0,
	0,
	0,
	0,
	0,
	0,
	0,
	0
);
$totals = $subtotals;

echo $this -> Html -> tag('h1', $budget['Organization']['name'] . " Budget");
echo $this -> Html -> div('', null, array('id' => "org-accordion-0"));
echo $this -> Html -> tag('h3', $budget['Organization']['name']);
{
	echo $this -> Html -> div('', null, array('style' => "padding: 0 0 0 0;"));
	{
		$category = 0;
		foreach ($budget['Requested'] as $k => $budgetLineItem)
		{
			//debug($budgetLineItem);
			if ($category != $budgetLineItem['category'])
			{
				$rowNumber = 0;
				if ($category > 0)
				{
					echo $this -> Html -> tableCells(array(
						'',
						'<b>Subtotals</b>',
						$this -> Number -> currency($subtotals[0]),
						$this -> Number -> currency($subtotals[1]),
						$this -> Number -> currency($subtotals[2]),
						$this -> Number -> currency($subtotals[3]),
						$this -> Number -> currency($subtotals[4]),
						$this -> Number -> currency($subtotals[5]),
						$this -> Number -> currency($subtotals[6]),
						$this -> Number -> currency($subtotals[7]),
						$this -> Number -> currency($subtotals[8]),
						$this -> Number -> currency($subtotals[9]),
						'',
						''
					));
					echo $this -> Html -> tableEnd();
					echo $this -> Html -> useTag('tagend', 'div');
					echo $this -> Html -> useTag('tagend', 'div');
					for ($index = 0; $index < count($subtotals); $index++)
					{
						$totals[$index] += $subtotals[$index];
						$subtotals[$index] = 0;
					}
				}
				echo $this -> Html -> div('', null, array(
					'id' => "bli-accordion-0$category",
					'style' => 'padding: 0 0 0 0;'
				));
				echo $this -> Html -> tag('h3', $budgetLineItem['LineItemCategory']['name']);
				echo $this -> Html -> div('', null, array('style' => 'padding: 0 0 0 0;'));
				$tableName = "BudgetLineItem-$category";
				echo $this -> Html -> tableBegin(array(
					'class' => 'listing',
					'id' => $tableName
				));
				echo $this -> Html -> tableHeaders(array(
					array('#' => array('style' => 'width:4%;')),
					array('Name' => array('style' => 'width:350px;')),
					'PY Req',
					'PY Alloc',
					'CY Req',
					'JFC',
					'UHRC',
					'GSSC',
					'UHR',
					'GSS',
					'CONF',
					'Final',
					'',
					''
				));

			}
			$requested = 0;
			$allocated = 0;
			if (isset($budget['Previous_Budget']['Requested'][$py_index]) && strcmp($budget['Previous_Budget']['Requested'][$py_index]['name'], $budgetLineItem['name']) == 0)
			{
				$requested = $budget['Previous_Budget']['Requested'][$py_index]['amount'];
				$allocated = $budget['Previous_Budget']['Allocated'][$py_index]['amount'];
				$py_index++;
			}

			echo $this -> Html -> tableCells(array(array(
				array(
					$this -> Form -> input("BudgetLineItem.$k.line_number", array(
						'label' => false,
						'value' => $budgetLineItem['line_number'],
						'style' => 'background-color:transparent; border:none;' . 
						(isset($budget[$state][$k]) && strlen($budget[$state][$k]['comments']) > 0 ? "text-decoration:underline;" : ""),
						'readonly' => 'readonly',
						'type' => 'text'
					)),
					array('id' => "highlight",'onclick' => ($sga_exec) ? 'openCommentDialog("' . "BudgetLineItem$k" . 'Id")' : '')
				),
				$this -> Form -> input("BudgetLineItem.$k.name", array(
					'label' => false,
					'div' => false,
					'readonly' => 'readonly',
					'type' => 'textarea',
					'style' => 'background-color:transparent; border:none;resize:none;',
					'rows' => (int) ceil(strlen($budgetLineItem['name']) / 47),
					'value' => $budgetLineItem['name'], 
					'title' => $budgetLineItem['name']
				)) . $this -> Form -> input("BudgetLineItem.$k.category", array(
					'type' => 'hidden',
					'value' => $budgetLineItem['category']
				)) . $this -> Form -> input("BudgetLineItem.$k.id", array(
					'type' => 'hidden',
					'value' => isset($budget[$state][$k]) ? $budget[$state][$k]['id'] : ''
				)),
				$this -> Number -> currency($requested),
				$this -> Number -> currency($allocated),
				$this -> Number -> currency($budgetLineItem['amount']),
				strcmp($state, 'JFC') == 0  && $sga_exec ? $this -> Form -> input("BudgetLineItem.$k.amount", array(
					'label' => false,
					'value' => isset($budget['JFC'][$k]) ? $budget['JFC'][$k]['amount'] : 0,
					'type' => 'text'
				)) : (isset($budget['JFC'][$k]) ? $this -> Number -> currency($budget['JFC'][$k]['amount']) : $this -> Number -> currency(0)),
				strcmp($state, 'UHRC') == 0 && $sga_exec ? $this -> Form -> input("BudgetLineItem.$k.amount", array(
					'label' => false,
					'value' => isset($budget['UHRC'][$k]) ? $budget['UHRC'][$k]['amount'] : 0
				)) : (isset($budget['UHRC'][$k]) ? $this -> Number -> currency($budget['UHRC'][$k]['amount']) : $this -> Number -> currency(0)),
				strcmp($state, 'GSSC') == 0 && $sga_exec ? $this -> Form -> input("BudgetLineItem.$k.amount", array(
					'label' => false,
					'value' => isset($budget['GSSC'][$k]) ? $budget['GSSC'][$k]['amount'] : 0
				)) : (isset($budget['GSSC'][$k]) && $sga_exec ? $this -> Number -> currency($budget['GSSC'][$k]['amount']) : $this -> Number -> currency(0)),
				strcmp($state, 'UHR') == 0 && $sga_exec ? $this -> Form -> input("BudgetLineItem.$k.amount", array(
					'label' => false,
					'value' => isset($budget['UHR'][$k]) ? $budget['UHR'][$k]['amount'] : 0
				)) : (isset($budget['UHR'][$k]) ? $this -> Number -> currency($budget['UHR'][$k]['amount']) : $this -> Number -> currency(0)),
				strcmp($state, 'GSS') == 0 && $sga_exec ? $this -> Form -> input("BudgetLineItem.$k.amount", array(
					'label' => false,
					'value' => isset($budget['GSS'][$k]) ? $budget['GSS'][$k]['amount'] : 0
				)) : (isset($budget['GSS'][$k]) ? $this -> Number -> currency($budget['GSS'][$k]['amount']) : $this -> Number -> currency(0)),
				strcmp($state, 'CONF') == 0 && $sga_exec ? $this -> Form -> input("BudgetLineItem.$k.amount", array(
					'label' => false,
					'value' => isset($budget['CONF'][$k]) ? $budget['CONF'][$k]['amount'] : 0
				)) : (isset($budget['CONF'][$k]) ? $this -> Number -> currency($budget['CONF'][$k]['amount']) : $this -> Number -> currency(0)),
				strcmp($state, 'Final') == 0 && $sga_exec ? $this -> Form -> input("BudgetLineItem.$k.amount", array(
					'label' => false,
					'value' => isset($budget['Final'][$k]) ? $budget['Final'][$k]['amount'] : 0,
					'type' => 'text'
				)) : (isset($budget['Final'][$k]) ? $this -> Number -> currency($budget['Final'][$k]['amount']) : $this -> Number -> currency(0)),
				(!$sga_exec) ? '' : $this -> Form -> button('+', array(
					'type' => 'button',
					'onclick' => "addRow(" . $rowNumber . ",'" . $tableName . "')",
					'escape' => false
				)),
				($budgetLineItem['original'] || !$sga_exec) ? '' : $this -> Form -> button('-', array(
					'type' => 'button',
					'onclick' => "deleteRow(" . $rowNumber . ",'" . $tableName . "')",
					'escape' => false
				)))
			));
			$rowNumber++;
			$subtotals[0] += $requested;
			$subtotals[1] += $allocated;
			$subtotals[2] += $budgetLineItem['amount'];
			$subtotals[3] += (isset($budget['JFC'][$k]) ? $budget['JFC'][$k]['amount'] : 0);
			$subtotals[4] += (isset($budget['UHRC'][$k]) ? $budget['UHRC'][$k]['amount'] : 0);
			$subtotals[5] += (isset($budget['GSSC'][$k]) ? $budget['GSSC'][$k]['amount'] : 0);
			$subtotals[6] += (isset($budget['UHR'][$k]) ? $budget['UHR'][$k]['amount'] : 0);
			$subtotals[7] += (isset($budget['GSS'][$k]) ? $budget['GSS'][$k]['amount'] : 0);
			$subtotals[8] += (isset($budget['CONF'][$k]) ? $budget['CONF'][$k]['amount'] : 0);
			$subtotals[9] += (isset($budget['Final'][$k]) ? $budget['Final'][$k]['amount'] : 0);
			$category = $budgetLineItem['category'];

		}
		for ($idx = 0; $idx < count($subtotals); $idx++)
			$totals[$idx] += $subtotals[$idx];
		echo $this -> Html -> tableCells(array(
			'',
			'<b>Subtotals</b>',
			$this -> Number -> currency($subtotals[0]),
			$this -> Number -> currency($subtotals[1]),
			$this -> Number -> currency($subtotals[2]),
			$this -> Number -> currency($subtotals[3]),
			$this -> Number -> currency($subtotals[4]),
			$this -> Number -> currency($subtotals[5]),
			$this -> Number -> currency($subtotals[6]),
			$this -> Number -> currency($subtotals[7]),
			$this -> Number -> currency($subtotals[8]),
			$this -> Number -> currency($subtotals[9]),
			'',
			''
		));
		echo $this -> Html -> tableCells(array(
			'',
			'<b>Totals</b>',
			$this -> Number -> currency($totals[0]),
			$this -> Number -> currency($totals[1]),
			$this -> Number -> currency($totals[2]),
			$this -> Number -> currency($totals[3]),
			$this -> Number -> currency($totals[4]),
			$this -> Number -> currency($totals[5]),
			$this -> Number -> currency($totals[6]),
			$this -> Number -> currency($totals[7]),
			$this -> Number -> currency($totals[8]),
			$this -> Number -> currency($totals[9]),
			'',
			''
		));
		echo $this -> Html -> tableEnd();

		echo $this -> Html -> useTag('tagend', 'div');
		echo $this -> Html -> useTag('tagend', 'div');
	}
	echo $this -> Html -> useTag('tagend', 'div');
}
echo $this -> Html -> useTag('tagend', 'div');
?>

<script>
	$(document).ready(function()
	{
		$("#dialog").css('visibility','visibile');
		$("#dialog").dialog(
		{
			autoOpen : false,
			height : 300,
			width : 350,
			modal : true
		});
	});

	function openCommentDialog(id)
	{
		var lineItemId = $("#" + id).val();
		if (lineItemId.length != 0)
		{
			$.ajax(
			{
				url : "processCommentDialog/" + $("#" + id).val(),
				context : document.body,
				type : 'GET',
				success : function(response, status)
				{
					var resp = JSON.parse(response);
					console.log(resp);
					var bli = resp["data"]["BudgetLineItem"];
					$("#DialogFormComment").val(bli["comments"]);
					$("#DialogFormName").html("Name: " + bli["name"]);
					$("#DialogFormState").html("State: " + bli["state"]);
				}
			});
			$("#dialog").dialog(
			{
				buttons :
				{
					"Add Comment" : function()
					{
						$.ajax(
						{
							url : "processCommentDialog/" + $("#" + id).val(),
							context : document.body,
							type : 'POST',
							data : $("#CommentDialogForm").serialize(),
							success : function(response, status)
							{
								$("#dialog").dialog("close");
								alert("Your comment has posted successfully.");
							}
						});
					},
					"Cancel" : function()
					{
						$(this).dialog("close");
					}
				}
			});
			$('.ui-widget-content').css('border','1px solid #bbb').css('background', 'white 50% top repeat-x');
			$("#dialog").css("background-color", "white");
			$("#dialog").dialog("open");
		}
		else
		{
			alert("A line item does not exist for this state yet. Please save then try again.");
		}
	}
</script>