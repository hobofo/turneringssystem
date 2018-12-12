<?php
include_once("conn.php");

session_start();
unset($_SESSION['username']);
if(isset($_POST["password"])){

    $username = mysql_real_escape_string($_POST['username']);
    $password = ($_POST['password']);
    $result1 = mysqli_query($link,"SELECT * FROM hbf_indstillinger WHERE short = 'password' AND setting ='$password'");
    $result2 = mysqli_query($link,"SELECT * FROM hbf_indstillinger WHERE short = 'brugernavn' AND setting ='$username'");
	$result3 = mysqli_query($link,"SELECT * FROM *");
    
	if(mysqli_num_rows($result1) && mysqli_num_rows($result2) )
	{
		// Login

		$_SESSION['username'] = htmlspecialchars($username); // htmlspecialchars() sanitises XSS
		// Redirect
		header('Location: index.php');
		exit;
	}
	else
	{
	  // Invalid username/password
	 header("Location: login.php");
	}
}
include_once("inc_header.php");
?>

<body id="login">
		<header>
			<div id="logologin">
				<a href="login.html">Hovedstadens Bordfodboldforening</a>
			</div>
		</header>
		

    <form action="login.php" method="post" data-ajax="false">
       <fieldset>
<section><label for="username">Brugernavn</label>
					<div><input type="text" required id="username" name="username" autofocus></div>
				</section>
				<section>

                                            <label for="password">Kodeord  <? if(9==8) {?><a href="#">glemt kodeord?<? } ?></a>

                                            </label>

                                        <div><input type="password" required  id="password" name="password"></div>
					<? if(9==8) {?>
                                        <div><input type="checkbox"  id="remember" name="remember">

                                            <label for="remember" class="checkbox">husk mig</label></div>
                                            <? } ?>
				</section>
</fieldset>
   <fieldset>
<section>
        <div style="text-align:right;"><button class="submit green" id="submit">Videre</button></div>
</section>
</fieldset>
</form>
		<footer>Hovedstadens Bordfodboldforening <? echo date("Y"); ?></footer>

</body>
<?php include_once("inc_footer.php"); ?>