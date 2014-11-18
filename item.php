<?php
	$mysqli = new mysqli('localhost', 'root', 'root', 'bundlehub');

	$errnum=mysqli_connect_errno();
	if ($errnum)
	{
     	$errmsg=mysqli_connect_error();
		print "Connect failed. error number=$errnum<br />
        		error message=$errmsg";
		exit();
	}

	$result = mysqli_query($mysqli, "SELECT * FROM products WHERE productid=9");

	while($row = mysqli_fetch_array($result)) {
		$name = $row['name'];
		$description = $row['description'];
		$img = " <img src='".$row['image']."' class='fullwidth banner-image' />";
		$price = "$".$row['price'];
		$rating = $row['rating'];
	}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Bundle Hub Item - Josh Delgado</title>
    <?php include 'meta.html' ?>
</head>
<body>
	<?php include 'header.html'; ?>
	<main>
		<div class="container">
			<h2 class="text-center"><?php echo $name ?> <span class="font-small">[<span class="green"><?php echo $rating ?></span>/10]</span></h2>
			<?php echo $img ?>
			<div class="flex child50">
				<div id="pay-extra">
					<h3>About the Bundle</h3>
					<p><?php echo $description ?></p>
				</div>
<<<<<<< Updated upstream
				<div>
					<h3>Pay What You Want <span class="font-small green"><span class="font-small"> Average Price: <?php echo displayItemPage($con,$item)['price']; ?></span></span></h3>
					<form class="pay-choice" action="item.php?">
						<?php echo "<input type='hidden' name='item' value=".$item.">"?>
						<?php echo "<input type='hidden' name='addtocart' value=".$item.">"?>
=======
				<div class="pay-extra">
					<div class="flex flex-center">
						<h3>Pay What You Want</h3>
						<p><span class="green">Average Price: <?php echo $price ?></span></p>
					</div>
					<form class="pay-choice">
>>>>>>> Stashed changes
						<input type="radio" name="pay" value="50"> $50<br>
						<input type="radio" name="pay" value="30"> $30<br>
						<input type="radio" name="pay" value="20"> $20<br>
						<input type="radio" name="pay" value="10"> $10<br>
						<input type="radio" name="pay" value="other"> Other <input id="other-text" style="display:none;"type="text"><br>
					</form>
				</div>
				<!--<div>
					<h3>Top contributers</h3>
					<table class="top-cont">
						<tr>
							<td>1. </td>
							<td>Zelda</td>
							<td>$123.00</td>
						</tr>
						<tr>
							<td>2. </td>
							<td>Link</td>
							<td>$122.00</td>
						</tr>
						<tr>
							<td>3. </td>
							<td>Ganondorf</td>
							<td>$5.01</td>
						</tr>
						<tr>
							<td>4. </td>
							<td>Navi</td>
							<td>$5.00</td>
						</tr>
						<tr>
							<td>5. </td>
							<td>Fi</td>
							<td>$1.00</td>
						</tr>
					</table>
				</div>-->
			</div>
		</div>
	</main>
	<?php include 'footer.html'; ?>
</body>
</html>