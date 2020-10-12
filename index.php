<?php

require_once 'connec.php';

$pdo = new \PDO(DSN, USER, PASS);

$query = 'SELECT * FROM friend;';
$statement = $pdo->query($query);
$friends = $statement->fetchAll(PDO::FETCH_ASSOC);

if (!empty($_POST) && isset($_POST['btnForm'])) {

    $errors = [];

    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);

    if (empty($firstname))
        $errors['firstname'] = 'Required';
    if (strlen($firstname) >= 45)
        $errors['firstname'] = 'Firstname too long';
    if (empty($lastname))
        $errors['lastname'] = 'Required';
    if (strlen($lastname) >= 45)
        $errors['lastname'] = 'Lastname too long';

    if (empty($errors)) {

        $query = 'INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname);';
        $statement = $pdo->prepare($query);

        $statement->bindValue(':firstname', $firstname, \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $lastname, \PDO::PARAM_STR);

        $statement->execute();

        header("Location: /index.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <ul>
        <?php foreach ($friends as $friend) { ?>
            <li><?= $friend['firstname'] . ' ' . $friend['lastname'] ?></li>
        <?php } ?>
    </ul>
    <form action="index.php" method="post" novalidate>
        <div>
            <label for="firstname">Firstname</label>
            <input type="text" id="firstname" name="firstname" required>
            <?php if (isset($errors['firstname'])): ?>
                <span style="color:red;"><?= $errors['firstname'] ?></span>
            <?php endif; ?>
        </div>
        <div>
            <label for="lastname">Lastname</label>
            <input type="text" id="lastname" name="lastname" required>
            <?php if (isset($errors['lastname'])): ?>
                <span style="color:red;"><?= $errors['lastname'] ?></span>
            <?php endif; ?>
        </div>
        <div>
            <button type="submit" name="btnForm">Send</button>
        </div>
    </form>
</body>
</html>
