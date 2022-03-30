<?php

session_start();
include 'header.php';
global $db;

$msg = "";
if ( isset( $_POST['login'] ) ) {
	$username = trim( $_POST['username'] );
	$password = trim( $_POST['password'] );
	if ( $_POST['username'] != "" && $_POST['password'] != "" ) {
		try {
			$query = "select * from `user` where `username`=:username";
			$stmt  = $db->prepare( $query );
			$stmt->bindParam( 'username', $username, PDO::PARAM_STR );
			$stmt->execute();
			$count = $stmt->rowCount();
			$row   = $stmt->fetch( PDO::FETCH_ASSOC );

            $query_details = "SELECT * FROM `user_details` WHERE `client_id`=:id";
            $stmt_details = $db->prepare($query_details);
            $stmt_details->bindParam('id', $row['id'], PDO::PARAM_STR);
			$stmt_details->execute();
			$row_details   = $stmt_details->fetch( PDO::FETCH_ASSOC );
			if (password_verify($password, $row['password'])){
	            $_SESSION['sess_user_id']   = $row['id'];
	            $_SESSION['sess_user_name'] = $row['username'];
	            $_SESSION['sess_name']      = $row_details['prenom'];
	            $_SESSION['sess_lastname']      = $row_details['nom'];
	            header( 'location:index.php' );
            } else {
				$msg = "Invalid username and password!";
			}
		} catch ( PDOException $e ) {
			echo "Error : " . $e->getMessage();
		}
	} else {
		$msg = "Both fields are required!";
	}
}
?>

<form method="post" action="">
    <h2>Connexion</h2>
    <label>Nom d'utilisateur :
        <input type="text" name="username" value="" autocomplete="off" required>
    </label>
    <label for="password">Mot de passe:
        <input type="password" name="password" value="" autocomplete="off" required>
    </label>
    <input type="submit" name="login" id="login" value="Login"/>
    <span class="loginMsg"><?php echo @$msg; ?></span>
    <a href="signup.php"> Créer un compte </a>
</form>