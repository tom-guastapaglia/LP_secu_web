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
	$adresse  = trim( $_POST['adresse'] );
	$fileid   = trim( $_POST['fileid'] );


	if ( $_POST['name'] != "" && $_POST['lastname'] != "" && $_POST['phone'] != "" && $_POST['mail'] != "" && $_POST['adresse'] != "" ) {
		try {

			$fileTmpPath = $_FILES['fileid']['tmp_name'];
			$fileName = $_FILES['fileid']['name'];
			$fileSize = $_FILES['fileid']['size'];
			$fileType = $_FILES['fileid']['type'];
			$fileNameCmps = explode(".", $fileName);
			$fileExtension = strtolower(end($fileNameCmps));

			$newFileName = md5(time() . $fileName) . '.' . $fileExtension;

			$allowedfileExtensions = array('jpg', 'png', 'pdf', 'tiff');
			if (in_array($fileExtension, $allowedfileExtensions)) {
				$uploadFileDir = './uploaded_files/';
				$dest_path = $uploadFileDir . $newFileName;

				if(move_uploaded_file($fileTmpPath, $dest_path))
				{
					$msg ='File is successfully uploaded.';
				}
				else
				{
					$msg = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
				}
			}

			$query = /** @lang sql */
				"REPLACE INTO `user_details` (client_id, nom, prenom, mail, tel, adresse, fichier) VALUES(
                        :user_id,
                        :last_name,
                        :name,
                        :mail,
                        :phone,
                        :adresse,
                        :fileid
                        )";
			$stmt = $db->prepare( $query );
			$stmt->execute(
				array(
					":user_id"   => $_SESSION['sess_user_id'],
					":last_name" => $lastname,
					":name"      => $name,
					":mail"      => $mail,
					":phone"     => $phone,
					":adresse"   => $adresse,
					":fileid"    => $newFileName
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


    <form method="post" action="" enctype="multipart/form-data">
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

