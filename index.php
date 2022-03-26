<?php
include 'header.php';
global $db;
session_start();

$msg = "";
if ( isset( $_POST['form'] ) ) {
	$name     = trim( $_POST['name'] );
	$lastname = trim( $_POST['lastname'] );
	$phone    = trim( $_POST['phone'] );
	$mail     = trim( $_POST['mail'] );
	$adresse   = trim( $_POST['adresse'] );
	$fileid   = trim( $_POST['fileid'] );

	if ( $_POST['name'] != "" && $_POST['lastname'] != "" && $_POST['phone'] != "" && $_POST['mail'] != "" && $_POST['adresse'] != "") {
		try {
			$query = /** @lang sql */
				"INSERT INTO `data` (user_id, phone, mail, adresse, file_id) VALUES(
                        :user_id,
                        :phone,
                        :mail,
                        :adresse,
                        :file_id
                        )";
            var_dump($query);
			$stmt  = $db->prepare( $query );
			$stmt->execute(
				array(
					":user_id" => $_SESSION['sess_user_id'],
					":phone"     => $phone,
					":mail" => $mail,
					":adresse" => $adresse,
					":file_id" => $fileid,
				)
			);
		} catch ( PDOException $e ) {
			echo "Error : " . $e->getMessage();
		}
	} else {
		$msg = "Both fields are required!";
	}
}

if ( isset( $_SESSION['sess_user_id'] ) && $_SESSION['sess_user_id'] != "" ) {
	echo '<h1>Welcome ' . $_SESSION['sess_name'] . '</h1>';

	?>
    <h4><a href="logout.php">Se déconnecter</a></h4>


    <form method="post" action="">
        <h2>Formulaire de données</h2>
        <label class="firstLabel">
            Prénom :
            <input type="text" name="name" value="<?php echo $_SESSION['sess_name']; ?>" autocomplete="on" required>
        </label>
        <br>
        <label>
            Nom :
            <input type="text" name="lastname" value="<?php echo $_SESSION['sess_lastname']; ?>" autocomplete="on"
                   required>
        </label>
        <br>
        <label>
            Numéro de téléphone
            <input type="tel" name="phone" value="" autocomplete="on" required>
        </label>
        <br>
        <label>
            Adresse email
            <input type="email" name="mail" value="" autocomplete="on" required>
        </label>
        <br>
        <label>
            Adresse
            <input type="text" name="adresse" value="" autocomplete="off" required>
        </label>
        <br>
        <label>
            Pièce d'identité
            <input type="file" accept="image/*" name="fileid" value="" autocomplete="off" required>
        </label>
        <br>
        <input type="submit" name="form" value="Envoyer">
        <span class="loginMsg"><?php echo @$msg; ?></span>
    </form>


	<?php


} else {
	header( 'location:login.php' );
}

