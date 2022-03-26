<?php

session_start();
include 'header.php';
global $db;
$msg = "";
if ( isset( $_POST['signup'] ) ) {
	$username = trim( $_POST['username'] );
	$name     = trim( $_POST['name'] );
	$lastname = trim( $_POST['lastname'] );

	var_dump( $username );
	var_dump( $name );
	var_dump( $lastname );
	// Hash du password
	$password_brut = trim( $_POST['password'] );
	$password = password_hash( $password_brut, PASSWORD_DEFAULT );
	var_dump( $password );

	if ( isset( $_POST['username'] ) && isset( $_POST['lastname'] ) && isset( $_POST['name'] ) && isset( $_POST['password'] ) ) {
		try {
			$query = /** @lang sql */
				"INSERT INTO `user` (username, prenom, nom, password) VALUES(
                        :username,
                        :name,
                        :lastname,
                        :password
                        )";
			$stmt  = $db->prepare( $query );
			$stmt->execute(
				array(
					":username" => $username,
					":name"     => $name,
					":lastname" => $lastname,
					":password" => $password,
				)
			);
		} catch ( PDOException $e ) {
			echo "Error : " . $e->getMessage();
		}
	} else {
		$msg = "Both fields are required!";
	}
}
?>

<form method="post" action="">
    <h2>Créer un compte</h2>
    <label class="firstLabel">
        Nom d'utilisateur :
        <input type="text" name="username" value="" autocomplete="off"/>
    </label>

    <label>
        Mot de passe:
        <input type="password" name="password" value="" autocomplete="off"/>
    </label>

    <label>
        Nom
        <input type="text" name="lastname" value="" autocomplete="on">
    </label>

    <label>
        Prénom
        <input type="text" name="name" value="" autocomplete="on">
    </label>

    <input type="submit" name="signup" value="Créer le compte"/>
    <span class="loginMsg"><?php echo @$msg; ?></span>
    <a href="login.php"> Se connecter </a>
</form>
