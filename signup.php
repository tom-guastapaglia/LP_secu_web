<?php

session_start();
include 'header.php';
global $db;
$msg = "";
if ( isset( $_POST['signup'] ) ) {
	$username = trim( $_POST['username'] );
	$name     = trim( $_POST['name'] );
	$lastname = trim( $_POST['lastname'] );

	// Hash du password
	$password_brut = trim( $_POST['password'] );
	$password      = password_hash( $password_brut, PASSWORD_DEFAULT );

	if ( isset( $_POST['username'] ) && isset( $_POST['lastname'] ) && isset( $_POST['name'] ) && isset( $_POST['password'] ) ) {
		/**
		 * Ajout des premières informations de connexion dans USER
		 */
		try {
			$query = /** @lang sql */
				"INSERT INTO `user` (username, password) VALUES(
                        :username,
                        :password
                        )";
			$stmt  = $db->prepare( $query );
			$stmt->execute(
				array(
					":username" => $username,
					":password" => $password,
				)
			);
		} catch ( PDOException $e ) {
			echo "Error : " . $e->getMessage();
		}
		/**
		 * Ajout des secondes informations dans USER_DETAILS
		 */
		try {
			$query = /** @lang sql */
				"INSERT INTO `user_details` (client_id, nom, prenom) VALUES(
                        :id,
                        :last_name,
                        :name
                        )";
			$stmt  = $db->prepare( $query );
			$stmt->execute(
				array(
					":id" => $db->lastInsertId(),
					":name" => $name,
                    ":last_name" => $lastname,
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
        <input type="text" name="username" value="" autocomplete="off" required>
    </label>

    <label>
        Mot de passe:
        <input type="password" name="password" value="" autocomplete="off" required>
    </label>

    <label>
        Nom
        <input type="text" name="lastname" value="" autocomplete="on" required>
    </label>

    <label>
        Prénom
        <input type="text" name="name" value="" autocomplete="on" required>
    </label>

    <input type="submit" name="signup" value="Créer le compte"/>
    <span class="loginMsg"><?php echo @$msg; ?></span>
    <a href="login.php"> Se connecter </a>
</form>
