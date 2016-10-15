<?php
//require_once 'constants.php';
//include_once 'includes/constants.php';
include 'header.php';

//require_once 'lib/dbconfig.php';
$isValid;

function checkValidSubscription($login, $birthd, $upass, $mdp2) {
    global $isValid;
    $isValid = TRUE;
    if ($mail == "") {
        $error = "provide login info";
        $isValid = FALSE;
    }
//    if ($user->familyExists() != TRUE) {
//        $isValid = FALSE;
//    }
    if ($upass == "") {
        $error = "provide password ";
        $isValid = FALSE;
    }
    if ($upass != $mdp2) {
        $error = "the passwords don't match ";
        $isValid = FALSE;
    }
    if (strlen($upass) < 6) {
        $error = "Password must be at least 6 characters";
        $isValid = FALSE;
    }
//    if ($user->userExistsCheck($mail)) {
//        $error = "User Email is already registered!";
//        $isValid = FALSE;
//    }
//        return $isValid;
}

//function userExists($umail){
//}
//if trim($G)
//if ($GLOBALS['displayErrMsg']) {
/*
  if (isset($GLOBAL['displayErrMsg'])) {

  //    include 'header';
  //    echo $GLOBALS['errMsg'];
  $GLOBALS['displayErrMsg'] = FALSE;
  //    $user->redirect('interactUser.php');
  $user->redirect("interactUser.php");
  //    header("interactUser.php");
  exit();

  //    sleep(5);
  }
 */
if ($user->is_loggedin()) {
    $user->redirect('profil.php');
}

if (isset($_POST['btn-signup'])) {
    global $isValid;
    $mail = trim($_POST['mail']);
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $birthd = date(trim($_POST['birthd']));
    $upass = trim($_POST['upass']);
    $mdp2 = trim($_POST['mdp2']);
    $user_is_admin = boolval(trim($_POST['code_admin']) == AdminKey);

    /*
      //    if ($mail == "") {
      //        $GLOBALS['errMsg'] = "provide email id !";
      //    }
      //    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
      //        $GLOBALS['errMsg'] = 'Please enter a valid email address !';
      //        $GLOBALS['displayErrMsg'] = TRUE;
      //    }
      //    if ($upass == "") {
      //        $GLOBALS['errMsg'] = "provide password !";
      //        $GLOBALS['displayErrMsg'] = TRUE;
      //    }
      //    if ($upass != $mdp2) {
      //        $GLOBALS['errMsg'] = "the passwords don't match !";
      //        $GLOBALS['displayErrMsg'] = TRUE;
      //    }
      //    if (strlen($upass) < 6) {
      //        $GLOBALS['errMsg'] = "Password must be at least 6 characters";
      //        $GLOBALS['displayErrMsg'] = TRUE;
      //    }
      //    if ($user->userExistsCheck($mail)) {
      //        $GLOBALS['errMsg'] = "User Email is already registered!";
      //        $GLOBALS['displayErrMsg'] = boolval(TRUE);
      //    }
      //    if ($GLOBALS['displayErrMsg']) {
      ////        $user->redirect('signup.php');
      ////        $user->redirect('signup.php?error');
      //        $user->redirect('interactUser.php');
      ////        header("./interactUser.php");
      ////        header('signup.php?error');
      ////        exit();
      //    }
      //
     */
    checkValidSubscription($mail, $fname, $lname, $birthd, $upass, $mdp2);
//    if (checkValidSubscription($mail, $fname, $lname, $birthd, $upass, $mdp2, $address, $newsletter, $user_is_admin, $user )) {
    if ($isValid) {
        try {
            /*
              //            $stmt = $DB_con->prepare("SELECT * FROM user WHERE uEMail=:mail;");
              //            $stmt->execute(array(':uname' => $uname, ':umail' => $umail));
              //            $stmt->execute(array(':uEMail' => $mail, ':uFirstName' => $fname, ':uLastName' => $lname, ':uBirthDate' => $birthd, ':uPassword' => $upass, ':address' => $address));
              //			$stmt->execute(array(':uEMail'=>$mail, ':uFirstName'=>$fname, ':uLastName'=>$lname, ':uBirthDate'=>$birthd, ':uPassword'=>$upass,':address'=>$address,':newsletter'=>$newsletter, 'user_is_admin'=>$user_is_admin));
              //            $row = $stmt->fetch(PDO::FETCH_ASSOC);
              //            if ($row['uEMail'] == $mail) {
              //                $GLOBALS['errMsg'] = "sorry email already registered !";
              //            } else if ($row['user_email'] == $umail) {
              //                $GLOBALS['errMsg'] = "sorry email id already taken !";
              //            } else {
              //                if ($user->register($fname, $lname, $mail, $upass, $birthd, $address, $newsletter, $user_is_admin)) {
             */
            $user->registerParent($mail, $fname, $lname, $birthd, $upass);
//        $user->register($mail, $fname, $lname, $birthd, $upass, $address, $newsletter, $isAdmin);
//        print "successfully signed up!";
            $user->redirect('signup.php?joined');
//                }
//            }
        } catch (PDOException $e) {
//        echo $e->getMessage();
            $GLOBALS['errMsg'] = $e->getMessage();
            $GLOBALS['displayErrMsg'] = TRUE;
        }
    }
}


include 'header.php';
?>
<script type="text/javascript" src="./datepicker/js/bootstrap-datepicker.js"></script>
<!--<script type="text/javascript" src="./datepicker/js/bootstrap-datepicker.js"/>-->
<!--<script src="./datepicker/js/bootstrap-datepicker.js"/>-->
<!--<script src="./datepicker/js/bootstrap-datepicker.js"></script>-->
<!--<form class="form-signin" role="form" action="functions/connectUser2.php" method="post">-->
<!--<form class="form-signin" role="form" action="" method="post">-->
<div class="container" align="center" >

    <?php
    if (isset($error)) {
        ?>
        <div class="alert alert-danger">
            <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?> !
        </div>
        <?php
    }
    ?>
    <br/>
    <br/>
    <br/>


    <!--<div id="ha-form">-->
    <!--<div class="row row-centered">-->
    <!--<div class="col-xs-6 col-centered">-->
    <form class="form-signin" role="form" method="post" >
        <h2 class="form-signin-heading">Sign up</h2>

        <label for="login" >Pseudo</label>
        <input type="text" name="login" id="login" class="form-signin" placeholder="Login" required autofocus>
        <br/>

        <label for="birthd">Date de Naissance</label>
        <input type="date" name="birthd" id="birthd" class="form-signin" placeholder="BirthDate" required autofocus>
        <br/>

        <label for="password">Mot de passe</label>
        <input type="password" name="upass" id="mdp" class="form-signin" placeholder="Password" required autofocus>
        <br/>

        <label for="password_confirm">Confirmation Mot de passe</label>
        <!--<input type="password" name="mdp2" id="mdp2" class="form-signin" placeholder="Confirm Password" onfocus="checkPasswordMatch(mdp, mdp2)">-->
        <input type="password" name="mdp2" id="mdp2" class="form-signin" placeholder="Confirm Password" required autofocus>
        <br/>

        <button class="btn btn-lg btn-info " type="submit" name="btn-signup">Sign Up</button>

    </form>
    Avez vous déja un compte? <a href="../authentification.php">Enregistrez vous!</a>
</div>

    
    <?php
include 'footer.php';
?>