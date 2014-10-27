<?php
session_start();
	
    $con = new mysqli('localhost', 'root', 'root', 'bundlehub');

	$errnum=mysqli_connect_errno();
	if ($errnum)
	{
     	$errmsg=mysqli_connect_error();
		print "Connect failed. error number=$errnum<br />
        		error message=$errmsg";
		exit();
	}

    function login($con){
        $username = mysqli_real_escape_string($con, $_POST['username']);
        $password = mysqli_real_escape_string($con, $_POST['password']); 

        $query = "SELECT password FROM bundlehub.users WHERE username = '$username'";
        $result = mysqli_query($con, $query);

        if (!mysqli_query($con, $query)) {
            die('Error: ' . mysqli_error($con) . mysqli_errno($con));
        }

        $row = mysqli_fetch_array($result);

        if(mysqli_num_rows($result) == 0){
            echo "<script>alert('That Username does not exist')</script>";
        }
        else{
            if($password == $row['password']){
                $_SESSION["username"] = $username;
            }
            else{
                echo "<script>alert('Wrong Username/Password combination.')</script>";
            }
        }
        echo "<script>console.log('login ran for " . $_SESSION['username'] . "');</script>";
        return $username;
    }
    
    function newuser($con){
        $username = mysqli_real_escape_string($con, $_POST['username']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);

        $query = "INSERT INTO bundlehub.users (username, password, email, type) VALUES ('$username', '$password', '$email', '0');";

        if (!mysqli_query($con,$query)) {
            die('Error: ' . mysqli_error($con) . mysqli_errno($con));
        }

        mysqli_query($con, $query);
    }

    function editUser($con){
    	$name = mysqli_real_escape_string($con, $_GET['username']);
        $pass = mysqli_real_escape_string($con, $_GET['password']);
        $email = mysqli_real_escape_string($con, $_GET['email']);
        $type = mysqli_real_escape_string($con, $_GET['access']);
        $id = mysqli_real_escape_string($con, $_GET['id']);

        $query = "UPDATE bundlehub.users SET username='$name', password='$pass', email='$email', type='$type' WHERE id='$id'";
        $result = mysqli_query($con, $query);
        
        if (!$result) {
                die('Error: ' . mysqli_error($con) . mysqli_errno($con));
            }

        mysqli_query($con, $query);
    }

    function addItem($con){
        $name = mysqli_real_escape_string($con, $_GET['name']);
        $desc = mysqli_real_escape_string($con, $_GET['description']);
        $cat = mysqli_real_escape_string($con, $_GET['category']);
        $sku = mysqli_real_escape_string($con, $_GET['sku']);
        $tier = mysqli_real_escape_string($con, $_GET['tier']);
        
        $result = mysqli_query($con,"SELECT * FROM products WHERE name = '$name'");
        
        if ($result && mysqli_num_rows($result) == 0){
            $query = "INSERT INTO bundlehub.products (name, description, category, sku, tier) VALUES ('$name', '$desc', '$cat', '$sku', '$tier');";

            if (!$result) {
                die('Error: ' . mysqli_error($con) . mysqli_errno($con));
            }
            mysqli_query($con, $query);
        }
    }

    function editItem($con){
    	$name = mysqli_real_escape_string($con, $_GET['name']);
        $price = mysqli_real_escape_string($con, $_GET['price']);
        $cat = mysqli_real_escape_string($con, $_GET['category']);
        $sku = mysqli_real_escape_string($con, $_GET['sku']);
        $tier = mysqli_real_escape_string($con, $_GET['tier']);
        $id = mysqli_real_escape_string($con, $_GET['id']);

        $query = "UPDATE bundlehub.products SET name='$name', category='$cat', sku='$sku', price='$price', rating='$tier' WHERE productid='$id'";
        $result = mysqli_query($con, $query);
        
        if (!$result) {
                die('Error: ' . mysqli_error($con) . mysqli_errno($con));
            }

        mysqli_query($con, $query);
    }

    function removeItem($con,$item){
        $query = "UPDATE bundlehub.products SET active=1 WHERE productid='$item'";
        $result = mysqli_query($con, $query);
        
        if (!$result) {
                die('Error: ' . mysqli_error($con) . mysqli_errno($con));
            }
        mysqli_query($con, $query);
    }

    function removeUser($con,$user){
        $query = "UPDATE bundlehub.users SET active=1 WHERE id='$user'";
        $result = mysqli_query($con, $query);
        
        if (!$result) {
                die('Error: ' . mysqli_error($con) . mysqli_errno($con));
            }
        mysqli_query($con, $query);
    }

    function featuredItem($con){
        $result = mysqli_query($con, "SELECT * FROM products WHERE productid=6");

        while($row = mysqli_fetch_array($result)) {
		  $name = $row['name'];
          $price = $row['price'];
		  $img = " <img src='".$row['image']."' class='fullwidth banner-image' />";
	   }
        echo "<h3>Featured Bundle</h3>
					<div class='flex flex-end'>
						<h4>$name</h4>
						<p>Pay above <span class='green'>$ $price</span> to get everything!</p>
					</div>
				    $img
					<div>
						<form class='pay-choice flex flex-center space-around'>
							<div><input type='radio' name='pay' value='50'> $50<br></div>
							<div><input type='radio' name='pay' value='30'> $30<br></div>
							<div><input type='radio' name='pay' value='20'> $20<br></div>
							<div><input type='radio' name='pay' value='10'> $10<br></div>
							<div><input type='radio' name='pay' value='other'> Other<input id='other-text' style='display:none;'type='text'><br></div>
						</form>
					</div>
					<div class='center'>
						<input type='submit' class='center atc-button' value='Add to Cart'/>
					</div>";
    }

    function displayItemPage($con,$productid){
        $result = mysqli_query($con, "SELECT * FROM products WHERE productid=$productid");

        while($row = mysqli_fetch_array($result)) {
            $name = $row['name'];
            $description = $row['description'];
            $img = " <img src='".$row['image']."' class='fullwidth banner-image' />";
            $price = "$".$row['price'];
            $prerating = $row['rating'];
        }
        if ($prerating>=7){$rating="<span class='green'>".$prerating."</span>/10";}
        else if ($prerating<=4){$rating="<span class='red'>".$prerating."</span>/10";}
        else{$rating="<span class='yellow'>".$prerating."</span>/10";}
        
        return array('name'=>$name,'desc'=>$description,'img'=>$img,'price'=>$price,'rating'=>$prerating);
    }

    function checkpriv($con,$user){
        $result = mysqli_query($con, "SELECT * FROM users WHERE username='$user'"); 
        while($row = mysqli_fetch_array($result)) {
            $type = $row['type'];
        }
        return $type;
    }

if (isset($_POST['email'])) {
    newuser($con);
}
if (isset($_POST['username'])) {
    login($con);
}
if(isset($_SESSION['username'])){
    $user = $_SESSION['username'];
    if(checkpriv($con,$_SESSION['username'])==1){
        $loginOut = "<li><a href='php/logout.php' id='logout'>Log Out</a></li><li><a href='admin_inventory.php'>Welcome, $user</a></li>";
    }
    else{
         $loginOut = "<li><a href='php/logout.php' id='logout'>Log Out</a></li><li><a href='client.php'>Welcome, $user</a></li>";
    }
}
else{
    $user = "user";
    $loginOut = "<a href='' id='login'>Log In</a>"; 
}
if(checkpriv($con,$_SESSION['username'])==1 && isset($_GET['additem'])){
    addItem($con);
    header('Location: php/bounce.php');
}
if(checkpriv($con,$_SESSION['username'])==1 && isset($_GET['edititem'])){
    editItem($con);
    header('Location: php/bounce.php');
}
if(checkpriv($con,$_SESSION['username'])==1 && isset($_GET['edituser'])){
    editUser($con);
    header('Location: php/bounce_u.php');
}
if(checkpriv($con,$_SESSION['username'])==1 && isset($_GET['deleteitem'])){
    removeItem($con,$_GET['id']);
    header('Location: php/bounce.php');
}
if(checkpriv($con,$_SESSION['username'])==1 && isset($_GET['deleteuser'])){
    removeUser($con,$_GET['id']);
    header('Location: php/bounce_u.php');
}
?>