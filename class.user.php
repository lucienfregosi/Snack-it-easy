<?php
//error_reporting(1);

class USER {

    private $db;
    private $cpt = 0;
    private $ExceptionThrown = FALSE;

    function __construct($DB_con) {
        $this->db = $DB_con;
    }

    public function getPId($mail) {
        $stmt = $this->db->prepare("SELECT * FROM human WHERE hEMail=:hmail");
        $stmt->bindparam(":mail", $mail);
        $stmt->execute(array(':hmail' => $hmail));
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userRow['hId'];
    }

    public function getLastName($parentId) {
        $stmt = $this->db->prepare("SELECT hFamily FROM human WHERE hId=:parentId");
        $stmt->bindparam(":hId", $parentId);
        $stmt->execute(array(':hId' => $parentId));
        return $stmt->fetch(PDO::FETCH_ASSOC);
//        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
//        return $userRow;
    }

    public function registerParent($mail, $fname, $lname, $birthd, $hpass) {
        $parentID = $this->registerParentUser($mail, $fname, $birthd, $hpass);
        if (!$this->ExceptionThrown) {
            $famId = $this->registerFamily($lname, $parentID);
        }
        if (!$this->ExceptionThrown) {
            $this->bindParentName($mail, $famId);
        }
        if (!$this->ExceptionThrown) {
            $this->login($mail, $hpass);
        }
    }

    public function registerParentUser($mail, $fname, $birthd, $hpass) {
//	public function register($mail,$fname,$lname,$birthd,$hpass,$address)
//$isParent = TRUE;
        $isParent = 1;
        $isAdmin = 0;
        try {
//            $hpass = password_hash($hpass, PASSWORD_DEFAULT);
            /*
              $stmt = $this->db->prepare("INSERT INTO user(hEMail, uFirstName,uLastName,uBirthDate, uPassword ,uAddress, newsletter, user_is_admin)
              VALUES(:mail,:fname,:lname,:birthd,:hpass,:address,:newsletter,:user_is_dmin);");
             */
            if (isset($birthd)) {
                $stmt = $this->db->prepare("INSERT INTO human(hFName, isParent, hBirthDate, hEMail, hPassword)"
                        . " VALUES(:fname,:isParent,:birthd,:mail,:hpass);");
                $stmt->bindparam(":birthd", $birthd, PDO::PARAM_STR);
            } else {
//			$stmt = $this->db->prepare("INSERT INTO user(hEMail, uFirstName,uLastName,uBirthDate, uPassword) 
                $stmt = $this->db->prepare("INSERT INTO human(hFName, isParent, hEMail, hPassword)"
                        . " VALUES(:fname,:isParent,:mail,:hpass);");
//			$stmt = $this->db->prepare("INSERT INTO user(hEMail, uFirstName,uLastName,uBirthDate, uPassword) 
//		                                               VALUES(:mail,:fname,:lname,:birthd,:hpass,:address)");
            }
            $stmt->bindparam(":mail", $mail);
            $stmt->bindparam(":fname", $fname);
            $stmt->bindparam(":isParent", $isParent);
            $stmt->bindparam(":hpass", $hpass);

//            if (isset($birthd)) {
//                $stmt->execute(array(":mail" => $mail,":isParent" => $isParent,":birthd" => $birthd, ":hpass" => $hpass));
            $stmt->execute();
//            } else {
//                $stmt->execute();
//                $stmt->execute(array(":mail" => $mail, ":fam" => $famId, ":hpass" => $hpass));
//            }
//            $this->bindParentName($mail, $this->registerFamily($lname, $parentID));
            if (!$stmt)
                throw Exception("PARENT REGISTER USER ERROR");
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            $this->ExceptionThrown = TRUE;
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->ExceptionThrown = TRUE;
        }
    }

    public function registerFamily($lname, $hId) {
//    if(!familyExists($lname)){
        try {
            $stmt = $this->db->prepare("INSERT INTO family(fName,fCreator)"
                    . " VALUES(:lname,:creator);");

            $stmt->bindparam(":lname", $lname);
            $stmt->bindparam(":creator", $hId);

            $stmt->execute();
            if (!$stmt)
                throw Exception("FAMILY REGISTER USER ERROR");
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            $this->ExceptionThrown = TRUE;
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->ExceptionThrown = TRUE;
        }
    }

    public function bindParentName($mail, $famId) {
        $stmt = $this->db->prepare("UPDATE human SET hFamily=:fam WHERE hEMail=:mail;");
        $stmt->bindparam(":mail", $mail);
        $stmt->bindparam(":fam", $famId);
//        $stmt->execute(array(':hmail' => $hmail, ":fam" => $famId));
        $stmt->execute();
//        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
//        return $userRow['hId'];
    }

    public function getKidLName($parentId) {
        $stmt = $this->db->prepare("SELECT * FROM human WHERE hId=:parentId");
        $stmt->bindparam(":parentId", $parentId);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userRow['hFamily'];
    }

    public function registerKid($fname, $birthd) {
        $parentId = $_SESSION['user_session'];
//        $parentId = $_SESSION[user_session];
        $lname = $this->getKidLName($parentId);

        try {
            $stmt = $this->db->prepare("INSERT INTO human(hFName,hFamily, hBirthDate)"
                    . " VALUES(:fname,:lname,:birthd);");
            $stmt->bindparam(":birthd", $birthd, PDO::PARAM_STR);
            $stmt->bindparam(":fname", $fname);
            $stmt->bindparam(":lname", $lname);

            $stmt->execute();
            if (!$stmt)
                throw Exception("CHILD REGISTER ERROR");
        } catch (PDOException $e) {
            echo $e->getMessage();
            $this->ExceptionThrown = TRUE;
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->ExceptionThrown = TRUE;
        }
        return !$this->ExceptionThrown;
    }

    public function registerUser($mail, $fname, $lname, $birthd, $hpass) {
        try {
//            $hpass = password_hash($hpass, PASSWORD_DEFAULT);
            /*
              $stmt = $this->db->prepare("INSERT INTO user(hEMail, uFirstName,uLastName,uBirthDate, uPassword ,uAddress, newsletter, user_is_admin)
              VALUES(:mail,:fname,:lname,:birthd,:hpass,:address,:newsletter,:user_is_dmin);");
             */
            $stmt = $this->db->prepare("INSERT INTO human(hEMail, uFirstName,uLastName,uBirthDate, uPassword ,"
                    . " VALUES(:mail,:fname,:lname,:birthd,:hpass)");

            $stmt->bindparam(":mail", $mail);
            $stmt->bindparam(":fname", $fname);
            $stmt->bindparam(":lname", $lname);
            $stmt->bindparam(":birthd", $birthd, PDO::PARAM_STR);
            $stmt->bindparam(":hpass", $hpass);




            $stmt->execute(array(':mail' => $mail, ':fname' => $fname, ':lname' => $lname, ':birthd' => $birthd, ':hpass' => $hpass));

            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function userExistsCheck($hmail) {
        $stmt = $this->db->prepare("SELECT hEMail FROM human WHERE hEMail=:hmail");
        $stmt->bindparam(":hmail", $hmail);
//        $stmt->execute(array(':hmail' => $hmail));
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $numRows = count($userRow);
//        return boolval($stmt->rowCount() > 0);
//      return boolval($userRow->rowCount() > 0);
        if (!$userRow) {
            return $userRow;
        } else {
            return boolval(count($userRow) > 0);
        }
    }

    public function sendConfEmail() {
        
    }

    public function passwdCheck($hpass, $userRow) {
        return $userRow['uPassword'] == $hpass;
    }

    public function login($hmail, $hpass) {
//        if (!$this->userExistsCheck($hmail)) {
//            $msg = "user does not exist";
//            $this->msgAndRedirect($msg, 'interactUser.php');
//        } else {

        try {
//			$stmt = $this->db->prepare("SELECT * FROM human WHERE user_name=:uname OR user_email=:hmail LIMIT 1");
//			$stmt->execute(array(':uname'=>$uname, ':hmail'=>$hmail));
            //                    echo $hmail;
//                    echo ":hmail";

            $stmt = $this->db->prepare("SELECT * FROM human WHERE hEMail=:hmail");
//			$stmt = $this->db->prepare("SELECT * FROM human WHERE hEMail=" . $hmail . " LIMIT 1");
            $stmt->execute(array(':hmail' => $hmail));
            $stmt->bindparam(":mail", $mail);
//                $hpass = password_hash($hpass, PASSWORD_DEFAULT);

            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if (($userRow['hPassword'] == $hpass) && (isset($userRow['isParent']))) {
//                if (passwdCheck($hpass, $userRow['uPassword'])) {
//                if (password_verify($hpass, $userRow['uPassword'])) {
                $_SESSION['user_session'] = $userRow['hId'];
//                    $_SESSION['userInfo'] = $userRow[''];
 //               $_COOKIE['SessionID'] = $userRow['hId'];
 //               $_COOKIE['SessionID'] = $userRow['hEMail'];
//                $_COOKIE['FamilyID'] = $userRow['hFamily'];
//                $_COOKIE['user_is_admin'] = boolval($userRow['isAdmin']);
 //               $_COOKIE['user_is_parent'] = boolval($userRow['isParent']);
                $_SESSION['user_is_admin'] = boolval($userRow['isAdmin']);
                $_SESSION['user_is_parent'] = boolval($userRow['isParent']);
                if (session_status() == PHP_SESSION_NONE)
                    session_start();
                session_name('user_session_id=' . $userRow['hId'] . '-session_cpt=' . $this->cpt);
//                    $_COOKIE['SESS'] = new SESS(session_id(),$userRow['user_is_admin'], true);
                return true;
            } else {
                return false;
            }
//        }
//        }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return true;
    }

    public function is_loggedin() {
//        if(session_status()== PHP_SESSION_NONE){
//            session_start();
//            $_SESSION['connecte']
//        }
        return isset($_SESSION['CONNECTE']);
    }

    public function getSubscribers() {
        try {
            $stmt = $this->db->prepare("SELECT hEMail FROM human WHERE newsletter!=0;");
            $sz = $this->db->prepare("SELECT COUNT(hEMail) FROM human WHERE newsletter!=0;");
            $stmt->execute();
            $sz->execute();
            $nMax = $sz->fetch(PDO::FETCH_NUM);
            $num = intval($nMax[0]);
//            $newsers = array();
//            $nusers = array();
//            $nusers;
//            $fetchmode = PDO::FETCH_NUM;
//            $fetchmode = PDO::FETCH_ASSOC;
            $fetchmode = PDO::FETCH_COLUMN;
//            $fetchmode = PDO::FETCH_SERIALIZE;
            for ($i = 0; $i < $num; $i++) {
//                $newsers[] = $stmt->fetch($fetchmode);
                $nusers[$i] = strval($stmt->fetch($fetchmode));
            }
            return $nusers;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


    public function redirect($url) {
        header("Location: $url");
    }

    public function logout() {
        session_destroy();
        unset($_SESSION['user_session']);
        unset($_COOKIE['user_session']);
//        return true;
    }

    public function transformDate2($date){
           return date('m-d-Y', strtotime($date));
    }
    public function transformDate($date){
           return date('d-m-Y', strtotime($date));
    }

///APP COMMUNICATIONS

    
    public function showNumApps() {
        $stmt = $this->db->prepare("SELECT COUNT(appId) FROM app");
        echo 1;
        exit();
        $stmt->execute();
        $userRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<br/>";
        echo "number of Apps registered";
        echo $userRow['appId'];
        echo "<br/>";
        return $userRow;
        $q = $this->db->prepare("DESCRIBE app");
        $q->execute();
        $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
        for ($i = 0; $i < 5; $i++) {
            echo $table_fields[$i];
        }
    }

    public function createAssociationRecordA($appId) {
        $stmt = $this->db->prepare("INSERT INTO app (appId, userId, token) VALUES (:appId, :user, :token)");
        $stmt->bindparam(":user", $appId);
        $stmt->execute();
    }

    public function createAssociationRecordE() {
//        $stmt = $DB_con->prepare("INSERT INTO app (appId) VALUES (:appId)");
        $stmt = $this->db->prepare("INSERT INTO app (appId) VALUES (:appId)");
//        echo "<br/>";
//        echo "inside CARE";
        $stmt->bindparam(":appId", $this->generateAppId());
        $stmt->execute();
//        echo "<br/>";
//        echo "inside AID";
//        echo $aid;
    }

    public function createAssociationRecordUser($userId) {
        $stmt = $this->db->prepare("INSERT INTO app (appId, user, token) VALUES (:appId, :user, :token)");
        $stmt->bindparam(":user", $userId);
        $stmt->bindparam(":appId", $this->generateAppId());
        $stmt->bindparam(":token", $this->generateToken());
        $stmt->execute();
    }

    public function createAssociationRecord($userId, $appId) {
        $stmt = $this->db->prepare("INSERT INTO app (appId, user, token) VALUES (:appId, :user, :token)");
        $stmt->bindparam(":user", $userId);
        $stmt->bindparam(":appId", $appId);
        $stmt->bindparam(":token", $this->generateToken());
        $stmt->execute();
    }

    public function appIdExists($appId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS NUM FROM app WHERE appId=:appId");
        $stmt->bindparam(":appId", $appId);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($userRow['NUM'] > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function tokenExists($token) {
//        echo "inside token loop";
        $stmt = $this->db->prepare("SELECT COUNT(*) AS NUM FROM app WHERE token=:token");
        $stmt->bindparam(":token", $token);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($userRow['NUM'] > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function generateAppId() {
//        echo "inside GAID";
//        echo "<br/>";
        $appId = rand(0, 999999999);
//        echo "App ID Generated : " . $appId;
//        echo $this->appIdExists($appId);
        while ($this->appIdExists($appId)) {
            $appId = rand(0, 9999999999999999999);
//            echo "App ID Generated : \n" . $appId;
        }
//        echo "App ID returned : " . $appId;
        return $appId;
    }

    public function getTokenAndApp($hId, $appId) {
        $stmt = $this->db->prepare("SELECT token FROM app WHERE user=:hId AND appId=:appId");
        $stmt->bindparam(":hId", $hId);
        $stmt->bindparam(":appId", $appId);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if (sizeof($userRow['token']) == 0) {
            echo $this->createAssociationRecord($hId, $appId);
            return $this->getTokenAndApp($hId, $appId);
        } else {
            echo $userRow['token'];
            return $userRow['token'];
        }
    }

    public function getToken($hId) {
        $stmt = $this->db->prepare("SELECT token FROM app WHERE user=:hId");
        $stmt->bindparam(":hId", $hId);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
//        echo "size:";
//        echo sizeof($userRow['token']);
        if (sizeof($userRow['token']) == 0) {
            echo $this->createAssociationRecordUser($hId);
            return $this->getToken($hId);
        } else {
            echo $userRow['token'];
            return $userRow['token'];
        }
    }

    public function generateToken() {
        $token = rand(100000, 999999);
//        echo "token gen : " . $token;
        while ($this->tokenExists($token)) {
            $token = rand(100000, 999999);
        }
//        echo "token ret : " . $token;
        return $token;
    }

    public function getAgeFromApp($appId) {
        $stmt = $this->db->prepare("SELECT TIMESTAMPDIFF(YEAR, 
                                        (SELECT hBirthDate 
                                        FROM human 
                                        JOIN app 
                                        ON user=hId
                                        WHERE appId=:appId)
                                    , CURDATE()) AS age"
        );
        $stmt->bindparam(":appId", $appId);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $userRow['age'];
        return $userRow['age'];
    }

    public function getAgeFromUser($hId) {
        $stmt = $this->db->prepare("SELECT TIMESTAMPDIFF(YEAR, (SELECT hBirthDate AS BD FROM human WHERE hId=:hId), CURDATE()) AS age");
        $stmt->bindparam(":hId", $hId);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        //$format = "Y-d-m";
        echo $userRow['age'];
        return $userRow['age'];
    }

    public function getBirthdFromApp($appId) {
        $stmt = $this->db->prepare("select hBirthDate 
                                    from human
                                    join app
                                    on user = hId
                                    where appId = :appId
                                    ");
        $stmt->bindparam(":appId", $appId);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userRow['hBirthDate'];
    }

    public function getBirthdFromUser($hId) {
        $stmt = $this->db->prepare("select hBirthDate 
                                    from human
                                    where hId=:hId");
        $stmt->bindparam(":hId", $hId);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $userRow['hBirthDate'];
        return $userRow['hBirthDate'];
    }

    public function associatePhoneFrom($token, $appId) {
        $stmt = $this->db->prepare("select token 
                                    from app
                                    where appId=:appId");
        $stmt->bindparam(":appId", $appId);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($userRow == $token) {
            $stmt = $this->db->prepare("update app set token = NULL where appId = :appId");
            $stmt = $this->db->prepare("update app set confirmed = 1 where appId = :appId");
            $stmt->bindparam(":appId", $appId);
            $stmt->execute();
        }
        return $userRow == $token;
    }

    public function echoPageTest($msg = "Hello") {
        echo $msg;
    }

    public function confirmInstance($appId) {
        $stmt = $this->db->prepare("UPDATE app
                                    SET confirmed=1
                                    WHERE appId=:appId
                                    ");
        $stmt->bindparam(":appId", $appId);
        $stmt->execute();
    }

    public function associatePhone($token) {
        $stmt = $this->db->prepare("select hId, hBirthDate,appId  
                                    from human
                                    join app
                                    on hId = user
                                    where token=:token
                                    ");
        $stmt->bindparam(":token", $token);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if (sizeof($userRow['hId']) == 0 || sizeof($userRow['hBirthDate']) == 0) {
//            return NULL;
        } else {
        $id = $userRow['hId'];
        $bd = $this->transformDate($userRow['hBirthDate']);
        $bd = $this->transformDate($bd);
//        echo $this->transformDate2($bd);
//            $result = $userRow['hId'] . ";" . $this->transformDate($userRow['hBirthDate']);
            $this->confirmInstance($userRow['appId']);
            $result = $userRow['hId'] . ";" . $this->transformDate($userRow['hBirthDate']);
            echo $result;
//            return $result;
        }
    }

    //    public function executeInsert($query,$paramTitle,$parameters) {
    public function executeInsert2Dim($params2dim) {
        $sz = $params2dim[0];
        $query = "INSERT INTO (";
        for ($i = 0; $i < 2; $i++) {
            if ($i == 1)
                $query += " VALUES(";
            for ($j = 0; $j < sizeof($sz, $mode); $j++) {
                if ($j == sizeof($sz, $mode) - 1) {
                    $query += $params2dim[$i][$j];
                } else {
                    $query += $params2dim[$i][$j] . " ,";
                }
                $query += ")";
            }
        }
        $stmt = $this->db->prepare($query);
        for ($i = 0; $i < sizeof($params2dim[0], $mode); $i++) {
            $stmt->bindparam($params2dim[0][$i], $params2dim[1][$i]);
        }
        $stmt->execute();
    }

    public function executeInsert($tName, $params, $values, $Dim2Arr) {
        $query = "INSERT INTO (";
        for ($i = 0; $i < 2; $i++) {
            if ($i == 1)
                $query += " VALUES(";
            for ($j = 0; $j < sizeof($params, $mode); $j++) {
                if ($j == sizeof($params, $mode) - 1) {
                    $query += $params[$i];
                } else {
                    $query += $params[$i] . " ,";
                }
                $query += ")";
            }
        }
        $stmt = $this->db->prepare($query);
        for ($i = 0; $i < sizeof($params, $mode); $i++) {
            $stmt->bindparam($params[$i], $values[$i]);
        }
    }

    public function executeUpdate($query, $paramTitle, $parameters) {
        
    }

    public function executeSelect($query, $paramTitle, $parameters) {
        $stmt = $this->db->prepare("SELECT * FROM human WHERE hEMail=:hmail");
    }

        //TODO
    public function msgAndRedirect($msg, $url) {
        echo $msg;
//echo "<script>setTimeout(\"location.href = 'http://www.forobd2.com';\",1500);</script>";
        echo "<script>setTimeout(\"location.href = $url;\",1500);</script>";
    }

    //TODO
    public function sendMail() {
        
    }

    //!!!! COMMANDE = ACHAT 
    //!!!! TRANSACTION = RECHARGE

    public function commander($stade, $listeProduits) {
        foreach ($listeProduits as $produit => $pt) {
            $id[] = $pt['produitid'];
            $pnoms[] = $pt['produitnom'];
            $pnoms[] = $pt['produitnom'];
            $total += $pt['prix'];
        }
    }

    public function printArray($anArray){
        foreach ($anArray as $item) {
            
            echo "<div>";
            echo $item;
            echo "</div>";
            
        }
    }

    public function creerCommande() {
        
    }

    public function creerTransaction($date, $montant, $type) {
        $typQ = ($achat == NULL) ? array("", "") : array(", achat", ",:achat)");
        if ($_SESSION["USER_TYPE"] != client) {
            die("You're not a customer!");
        }
        $stmt = $this->db->prepare("INSERT INTO transaction(date, montant" . $typQ[0] . ")"
                . " VALUES(:date,:montant" . $typQ[1] . " ;");
        $stmt->bindparam(":date", $date);
        $stmt->bindparam(":montant", $montant);
        if ($achat == NULL) {
            $stmt->bindparam(":type", $type);
        }
        $stmt->execute();
    }

    public function crediterRecharge($montant) {
        $stmt = $this->db->prepare("select * 
                                    from user
                                    where user = :userId
                                    ");
        $stmt->bindparam(":userId", $_COOKIE['userid']);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $this->db->prepare("INSERT INTO transaction(date, montant, achat)"
                . " VALUES(:date,:montant,:achat);");
        $stmt->bindparam(":date", $date);
        $stmt->bindparam(":montant", $montant);
        $stmt->bindparam(":achat", $achat);
        $stmt->execute();
    }

    public function paiement() {
        $uId = $_SESSION["USER_ID"];
        if ($_SESSION["USER_TYPE"] != client) {
            die("You're not a customer!");
        }
        if (true) {
            
        }
    }

//DONE
    public function getProduit($prodId) {
        $stmt = $this->db->prepare("SELECT * FROM produit WHERE produit = :produitid ;");
        $stmt->bindparam(":produitid", $produitid);
        $stmt->execute();
        $productRow = $stmt->fetch(PDO::FETCH_ASSOC);
        return $productRow;
    }

//DONE
    public function getBalance() {
        if ($_SESSION["USER_TYPE"] != "supporter" && $_SESSION["USER_TYPE"] != "client") {
            die("You're not a customer!");
        }
        $stmt = $this->db->prepare("SELECT balance FROM user WHERE userid = :userId ;");
        $stmt->bindparam(":userId", $_SESSION["USER_ID"]);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userRow['balance'];
    }

//DONE
    public function getUsers() {
        $stmt = $this->db->prepare("select * 
                                    from user
                                    ");
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userRow;
    }
    public function getUser($userid) {
        $stmt = $this->db->prepare("select * 
                                    from user
                                    where user = :userId
                                    ");
        $stmt->bindparam(":userId", $userid);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userRow;
    }

    public function printRecap() {
        
    }

    public function getProduits(){
        $stmt = $this->db->prepare("SELECT * FROM produit");
        $stmt->execute();
        $productRow = $stmt->fetch(PDO::FETCH_ASSOC);
        return $productRow;        
    }


}

class SESS {

    private $sessionID;
    private $isAdmin;
    private $isLogged;

    function __construct($sessionID, $isAdmin, $isLogged) {
        $this->sessionID = $sessionID;
        $this->isAdmin = $isAdmin;
        $this->isLogged = $isLogged;
    }

    function getSessionID() {
        return $this->sessionID;
    }

    function isAdminLogged() {
        return $this->isAdmin && $this->isLogged;
    }

    function getIsAdmin() {
        return $this->isAdmin;
    }

    function getIsLogged() {
        return $this->isLogged;
    }

    function setSessionID($sessionID) {
        $this->sessionID = $sessionID;
    }

    function setIsAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;
    }

    function setIsLogged($isLogged) {
        $this->isLogged = $isLogged;
    }

}

class Pers {

    private $pId;
    private $mail;
    private $lname;
    private $fname;
    private $isadmin;

}

class DEV {

    public static $VERBOSE = FALSE;

}

?>
