<?php

include "../lib/php/functions.php";
include "../parts/templates.php";

$empty_product = (object) [
	"name"=>"Lancome Eye Gel",
	"price"=>"28",
	"category"=>"Eye Treatments",
	"description"=>"Lancome Énergie de Vie Illuminating & Anti-Fatigue Cooling Eye Gel",
	"thumbnail"=>"images/lancome_eye_thumbnail",
	"images"=>"images/lancome_eye_1,images/lancome_eye_2",
	"size"=>"0.5oz",
	"quantity"=>"10"
];








if(isset($_GET['id'])) {
try{

$conn = makePDOConn();

switch(@$_GET['action']) {

	case "update":

		$statement = $conn->prepare("UPDATE
		`products`
		SET
			`name`=?,
			`price`=?,
			`category`=?,
			`description`=?,
			`thumbnail`=?,
			`images`=?,
			`size`=?,
			`quantity`=?,
			`date_modify`=NOW()
		WHERE `id`=?
		");
		$statement->execute([
			$_POST["product-name"],
			$_POST["product-price"],
			$_POST["product-category"],
			$_POST["product-description"],
			$_POST["product-thumbnail"],
			$_POST["product-images"],
			$_POST["product-size"],
			$_POST["product-quantity"],
			$_GET['id']
		]);

		header("location:{$_SERVER['PHP_SELF']}?id={$_GET['id']}");
		break;
	case "create":
		$statement = $conn->prepare("INSERT INTO
		`products`
		(
			`name`,
			`price`,
			`category`,
			`description`,
			`thumbnail`,
			`images`,
			`size`,
			`quantity`,
			`date_create`,
			`date_modify`
		)
		VALUES
		(?,?,?,?,?,?,?,?,NOW(),NOW())
		
		");
		$statement->execute([
			$_POST["product-name"],
			$_POST["product-price"],
			$_POST["product-category"],
			$_POST["product-description"],
			$_POST["product-thumbnail"],
			$_POST["product-images"],
            $_POST["product-size"],
			$_POST["product-quantity"]
		]);
		$id = $conn->lastInsertId();

		header("location:{$_SERVER['PHP_SELF']}?id=$id");
		
		break;
	case "delete":
		$statement = $conn->prepare("DELETE FROM `products` WHERE `id`=?");
		$statement->execute([$_GET['id']]);

		header("location:{$_SERVER['PHP_SELF']}");
		break;
}

} catch(PDOException $e) {
	die($e->getMessage());
}
}







function makeProductForm($o) {

$id = $_GET['id'];
$addoredit = $id=='new' ? 'Add' : 'Edit';
$createorupdate = $id=='new' ? 'create' : 'update';
$deletebutton = $id=='new' ? "" : "<li class='flex-none'><a href='{$_SERVER['PHP_SELF']}?id=$id&action=delete'>Delete</a></li>";


$images = array_reduce(explode(",",$o->images),function($r,$o){
	return $r."<img src='../li.han/$o'>";
});

$data_show = $id=='new' ? "" : <<<HTML
<div class="card soft">

<div class="product-main">
	<img src="../li.han/$o->thumbnail" alt="">
</div>
<div class="product-thumbs">$images</div>

<h2>$o->name</h2>
<div>
	<strong>Price</strong>
	<span>&dollar;$o->price</span>
</div>
<div>
	<strong>Category</strong>
	<span>$o->category</span>
</div>
<div>
	<strong>Quantity</strong>
	<span>$o->quantity</span>
</div>
<div>
	<strong>Description</strong>
	<div>$o->description</div>
</div>
</div>
HTML;



echo <<<HTML
<div class="card soft">
	<nav class="nav-pills">
		<ul>
			<li class="flex-none"><a href="{$_SERVER['PHP_SELF']}">Back</a></li>
			<li class="flex-stretch"></li>
			$deletebutton
		</ul>
	</nav>
</div>
<div class="grid gap">
	<div class="col-xs-12 col-md-5">$data_show</div>
	<form method="post" action="{$_SERVER['PHP_SELF']}?id=$id&action=$createorupdate" class="col-xs-12 col-md-7">
		<div class="card soft">
		<h2>$addoredit Product</h2>
		<div class="form-control">
			<label for="product-name" class="form-label">name</label>
			<input type="text" class="form-input" placeholder="A Product name" id="product-name" name="product-name" value="$o->name">
		</div>
		<div class="form-control">
			<label for="product-price" class="form-label">Price</label>
			<input type="number" class="form-input" placeholder="A Product Price" id="product-price" name="product-price" value="$o->price" step="0.01" min="0.01" max="1000">
		</div>
		<div class="form-control">
			<label for="product-category" class="form-label">Category</label>
			<input type="text" class="form-input" placeholder="A Product Category" id="product-category" name="product-category" value="$o->category">
		</div>
		<div class="form-control">
			<label for="product-description" class="form-label">Description</label>
			<textarea class="form-input" placeholder="A Product Description" id="product-description" name="product-description">$o->description</textarea>
		</div>
		<div class="form-control">
			<label for="product-thumbnail" class="form-label">Thumbnail</label>
			<input type="text" class="form-input" placeholder="A Product Thumbnail" id="product-thumbnail" name="product-thumbnail" value="$o->thumbnail">
		</div>
		<div class="form-control">
			<label for="product-images" class="form-label">Images</label>
			<input type="text" class="form-input" placeholder="A Product Images" id="product-images" name="product-images" value="$o->images">
		</div>
		<div class="form-control">
			<label for="product-quantity" class="form-label">Quantity</label>
			<input type="number" class="form-input" placeholder="A Product Quantity" id="product-quantity" name="product-quantity" value="$o->quantity">
		</div>
		<div class="form-control">
			<label for="product-size" class="form-label">Size</label>
			<input type="text" class="form-input" placeholder="Size" id="size" name="size" value="$o->size">
		</div>
		<div class="form-control">
			<input type="submit" value="Submit" class="form-button">
		</div>
		</div>
	</form>
</div>
HTML;
}




?><!DOCTYPE html>
<html lang="en">
<head>
	
	<?php include "../parts/meta.php" ?>
</head>
<body>

	<header class="navbar">
		<div class="container display-flex">
			<div class="flex-stretch">
				<h1>Product Admin</h1>
			</div>
			<nav class="nav flex-none">
				<ul class="display-flex">
					<li><a href="./">Store</a></li>
					<li><a href="<?= $_SERVER['PHP_SELF'] ?>">Product List</a></li>
					<li><a href="<?= $_SERVER['PHP_SELF'] ?>?id=new">Add New Product</a></li>
				</ul>
			</nav>
		</div>
	</header>

	<div class="container">

		<?php

		$conn = makeConn();

		if(isset($_GET['id'])) {

			if($_GET['id']=="new") {
				makeProductForm($empty_product);
			} else {
				$rows = getRows($conn,"SELECT * FROM `products` WHERE `id`='{$_GET['id']}'");
				makeProductForm($rows[0]);
			}

		} else {


		?>
		<div class="card soft">
		<p style="color: #074476">Product List</p>

		<div class="itemlist">
		<?php

		$rows = getRows($conn,"SELECT * FROM `products`");

		echo array_reduce($rows,'makeListItemTemplate');

		?>
		</div>
		</div>
		<?php

		}

		?>
	</div>
	
</body>
</html>