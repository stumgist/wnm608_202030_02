<?php

include_once "lib/php/functions.php";
include_once "parts/templates.php";

$result = getRows(
	makeConn(),
	"SELECT *
	FROM `products`
	WHERE `id` = '{$_GET['id']}'
	"
);
$o = $result[0];

$thumbs = explode(",", $o->images);

// print_p($_SESSION);

?><!DOCTYPE html>
<html lang="en">
<head>
	<title>Store: Product Item</title>

	<?php include "parts/meta.php" ?>

</head>
<body>

	<?php include "parts/navbar.php" ?>

	<div class="container">
		<nav class="nav-crumbs" style="margin:1em 0">
			<ul>
				<li><a href="product_list.php">Back</a></li>
			</ul>
		</nav>

		<div class="grid gap">
			<div class="col-xs-12 col-md-7">
				<div class="card soft">
					<div class="product-main">
						<img src="../li.han/<?= $o->thumbnail ?>" alt="">
					</div>
					<div class="product-thumbs">
					<?php

					echo array_reduce($thumbs,function($r,$o){
						return $r."<img src='../li.han/$o'>";
					})

					?>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-5">
				<form class="card soft flat" method="get" action="data/form_actions.php">
					<div class="card-section">
						<h3><?= $o->name ?></h>
						<div class="product-description">
							<div class="product-price">&dollar;<?= $o->price ?></div>
						</div>
					</div>

					<div class="card-section">
						<label class="form-label amount">Amount</label>
						<select name="amount" class="form-button" style="background-color: white;">
							<!-- option*10>{$} -->
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
							<option>6</option>
							<option>7</option>
							<option>8</option>
							<option>9</option>
							<option>10</option>
						</select>
					</div>

					<div class="card-section">
						<input type="hidden" name="action" value="add-to-cart">
						<input type="hidden" name="id" value="<?= $o->id ?>">
						<input type="hidden" name="price" value="<?= $o->price ?>">
						<input type="submit" class="form-button" value="Add To Cart">
					</div>
				</form>
			</div>
		</div>

		<div class="card soft dark">
			<h3>Description</h3>
			<div class="product-description" >
				<?= $o->description ?>
			</div>
		</div>

		<div>
			<h2>Recommended Products</h2>

			<?php recommendedProducts($o->category,$o->id) ?>
		</div>
	</div>
</body>
		<footer>
		<?php include "parts/footer.php" ?>
	</footer>

</html>