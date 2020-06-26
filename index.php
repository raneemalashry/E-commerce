
<?php 
    session_start();
    $pagetitle = 'HomePage';
    include "init.php";
    ?>
    
<div class="container">
	<div class="row">
		<?php
			$allItems = getallitems("*", 'items', 'WHERE approve = 1', '', 'ItemID' ,"asc");
			foreach ($allItems as $item) {
				echo '<div class="col-sm-6 col-md-3">';
					echo '<div class="thumbnail item-box">';
						echo '<span class="price-tag">$' . $item['Price'] . '</span>';
						echo '<img class="img-responsive" src="img.png" alt="" />';
						echo '<div class="caption">';
							echo '<h3><a href="items.php?itemid='. $item['ItemID'] .'">' . $item['Name'] .'</a></h3>';
							echo '<p>' . $item['Description'] . '</p>';
							echo '<div class="date">' . $item['AddDate'] . '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		?>
	</div>
</div>

    
    

   
<?php
 include $tpl."footer.php"; ?>