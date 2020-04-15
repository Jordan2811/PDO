<?php
function text_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlentities($data);
    return $data;
}

require_once 'connect.php';
$pdo = new PDO(DSN, USER, PASS);

try {
    $dbh = new PDO(DSN, USER, PASS);
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
}

$lastname = $firstname = "";
$lastnameError = $firstnameError = $errorLenLastname = $errorLenFirstname = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (empty($_POST['firstname'])) {
        $firstnameError = "Please enter your firstname.";
    } elseif (empty($_POST['lastname'])) {
        $lastnameError = 'Please, enter your lastname.';
    } else {
        $firstname = text_input($_POST['firstname']);
        $lastname = text_input($_POST['lastname']);
    }

    if ((strlen($_POST['firstname']) > 45)) {
        $errorLenFirstname = "Your firstname do not exed 45 characters !";
    }
    if ((strlen($_POST['lastname']) > 45)) {
        $errorLenLastname = "Your lastname do not exed 45 characters !";
    }

    if (empty($errorLenLastname) && (empty($errorLenFirstname)) && (empty($lastnameError)) && (empty($firstnameError))) {
        $addProfil = "INSERT INTO `pdo_quest`.`friend` (`firstname`, `lastname`) VALUES (:firstname, :lastname)";
        $statementAddProfil = $pdo->prepare($addProfil);
        $statementAddProfil->execute([
            ':firstname' => $firstname,
            ':lastname' => $lastname
        ]);
        header('location: validate.php');
    }
}

$query = 'SELECT * FROM friend';
$statement = $pdo->query($query);
$friends = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($friends as $friend => $name) {

    ?>
    <ul>
        <li><?= $name['firstname'] . " " . $name['lastname'] ?></li>
    </ul>
    <?php
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="validate.php">
    <title>form</title>
</head>

<body>
<form action="<?=htmlentities($_SERVER["PHP_SELF"]);?>" method="post">
    *Required <br>
    <label for="Firstname">
        *Enter your Firstname:
    </label>
    <input type="text" id="Firstname" name="firstname"><?= $firstnameError . " " . $errorLenFirstname?><br><br>
    <label for="Lastname">
        *Enter your Lastname:
    </label>
    <input type="text" id="Lastname" name="lastname"><?= $lastnameError . " " . $errorLenLastname?><br><br>
    <button type="submit"> Send !</button>
</form>
</body>
