$(document).ready(function() {			
	// Such-Filter am Anfang ausblenden
	//$('#search').setAttribute("style", "display: none");
	
	// Show Filter options if Sessions variable ItemIndexFilterVisability==1
	var se = document.getElementById("searchDIV");
	if(sessionStorage.getItem("ItemIndexFilterVisability") == 1)
		se.setAttribute("style", "display: block");
	else
		se.setAttribute("style", "display: none");
		
	$('#tbl').tableSelect({
		onClick: function(row) {
			//alert(row);
		},
		onDoubleClick: function(rows) {
			$.each(rows,function(i,row) {
				
				var id = $(row).children('td').eq(0).html().replace(/&nbsp;/, '');
				var html = 	'<td>'+$(row).children('td').eq(1).html() +'</td><td>'
							+ $(row).children('td').eq(2).html() +'</td><td>'
							+ $(row).children('td').eq(3).html() +'</td><td>'
							+ $(row).children('td').eq(4).html() +'</td><td>'
							+ $(row).children('td').eq(5).html() +'</td><td>'
							+ $(row).children('td').eq(6).html() +'</td><td>';
				
				$.post(
					'<?php echo $this->Html->url(array('controller' => 'items', 'action' => 'shopping_cart')); ?>/',
					//'/cakephp/hephydb/items/shopping_cart/',
					{id:  id, html: html },
					handleShoppingCart
				);	
				
			});
		},
		onChange: function(row) {
			//alert(row);
		}
	});
	
	function handleShoppingCart(table){
		$('#shopping-cart-tbl').remove();
		if(table.length > 0){
				$('#Navigator').after(table);
		}
	}
});