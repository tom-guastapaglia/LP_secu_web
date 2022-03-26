<?php

session_start();
include 'header.php';

    $msg = "";
    if(isset($_POST['submitBtnLogin'])) {
      $username = trim($_POST['username']);
      $password = trim($_POST['password']);
      if($username != "" && $password != "") {
        try {
          $query = "select * from `user_login` where `username`=:username and `password`=:password";
          $stmt = $db->prepare($query);
          $stmt->bindParam('username', $username, PDO::PARAM_STR);
          $stmt->bindValue('password', $password, PDO::PARAM_STR);
          $stmt->execute();
          $count = $stmt->rowCount();
          $row   = $stmt->fetch(PDO::FETCH_ASSOC);
          if($count == 1 && !empty($row)) {
            /******************** Your code ***********************/
            $_SESSION['sess_user_id']   = $row['uid'];
            $_SESSION['sess_user_name'] = $row['username'];
            $_SESSION['sess_name'] = $row['name'];

          } else {
            $msg = "Invalid username and password!";
          }
        } catch (PDOException $e) {
          echo "Error : ".$e->getMessage();
        }
      } else {
        $msg = "Both fields are required!";
      }
    }
?>

<form method="post" action="">
    <h2>Connexion</h2>
    <label for="username" class="firstLabel">Nom d'utilisateur :</label>
    <input type="text" name="username" id="username" value="" autocomplete="off"/>
    <label for="password">Mot de passe:</label>
    <input type="password" name="password" id="password" value="" autocomplete="off"/>
    <input type="submit" name="submitBtnLogin" id="submitBtnLogin" value="Login"/>
    <span class="loginMsg"><?php echo @$msg; ?></span>
    <a href="login.php"> Se connecter </a>
</form>