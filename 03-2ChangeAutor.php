<?php
require_once "function.php";
$conn = getConnection();


$errors = [];
$firstName = "";
$lastName = "";
$grade = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" and $_REQUEST["submitButton"]) {
    $id = isset($_POST["id"]) ? $_POST["id"] : "";
    $firstName = isset($_POST["firstName"]) ? $_POST["firstName"] : "";
    $lastName = isset($_POST["lastName"]) ? $_POST["lastName"] : "";
    $grade = isset($_POST["grade"]) ? $_POST["grade"] : 0;

    if (strlen($firstName) < 1 or strlen($firstName) > 21 ) {
        $errors[] = "Eesnimi peab olema 1-21 tahte";
    }
    if (strlen($lastName) < 2 or strlen($lastName) > 22 ) {
        $errors[] = "Pealkiri peab olema 2-22 tahte";
    }
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE authors SET firstName ='$firstName', lastName = '$lastName', grade = '$grade' where id = '$id';");
        $stmt->bindValue(':firstName', $firstName);
        $stmt->bindValue(':lastName', $lastName);
        $stmt->bindValue(':grade', $grade);
        $stmt->execute();
        header("Location: 03AutorList.php?call=1");
    }
}
elseif ($_SERVER["REQUEST_METHOD"] === "POST" and $_REQUEST["deleteButton"]) {
    $id = isset($_POST["id"]) ? $_POST["id"] : "";
    $stmt = $conn->prepare("DELETE FROM authors WHERE id = '$id';");
    $stmt->execute();
    header("Location: 03AutorList.php?call=1");
}
else {
    $autID = intval($_GET["id"]);
    $stmt = $conn->prepare("select id, firstName, lastName, grade from authors where id = '$autID'");
    $stmt->execute();
    foreach ($stmt as $row){
        $id = $row[0];
        $firstName = $row[1];
        $lastName = $row[2];
        $grade = $row[3];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lisa autor</title>
    <link href="styles.css" rel="stylesheet">
</head>

<body>
<div id="header_of_site">
    <a class="clear_link" href="01RaamatList.php" id="book-list-link">Raamatud</a>
    |
    <a class="clear_link" href="index.php" id="book-form-link">Lisa oma raamat</a>
    |
    <a class="clear_link" href="03AutorList.php" id="author-list-link">Autorid</a>
    |
    <a class="clear_link" href="04AddAutor.php" id="author-form-link">Lisa autor</a>
    |
</div>
<span class="alert" id="error-block">
    <?php foreach ($errors as $error): ?>
        <li><?= $error ?></li>
    <?php endforeach; ?>
</span>
<form   method="post"
        action="03-2ChangeAutor.php?call=1"
        id="fill_form">
    <input type="hidden" name="id" value="<?= $id?>"/>
    <table class="fill_table">
        <tr>
            <td><label for="pk">Eesnimi:</label></td>
            <td> <input type="text" placeholder="Kirjuta autori eesnimi" value="<?= $firstName ?>" id="pk" name="firstName"></td>
        </tr>
        <tr>
            <td><label for="pk2">Perekonnanimi:</label></td>
            <td> <input type="text" placeholder="Kirjuta autori perekonnanimi" value="<?= $lastName ?>" id="pk2" name="lastName"></td>
        </tr>
        <tr>
            <td>Hinne</td>
            <td>
                <label>1<input type="radio" name="grade" id="grade" value="1"
                        <?php if ($grade == 1) { ?> checked <?php }?>>
                </label>&nbsp;
                <label>2<input type="radio" name="grade" id="grade" value="2"
                        <?php if ($grade == 2) { ?> checked <?php }?>>
                </label>&nbsp;
                <label>3<input type="radio" name="grade" id="grade" value="3"
                        <?php if ($grade == 3) { ?> checked <?php }?>>
                </label>&nbsp;
                <label>4<input type="radio" name="grade" id="grade" value="4"
                        <?php if ($grade == 4) { ?> checked <?php }?>>
                </label>&nbsp;
                <label>5<input type="radio" name="grade" id="grade" value="5"
                        <?php if ($grade == 5) { ?> checked <?php }?>>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" value="Delete" id="delete_button" name="deleteButton">
            </td>
            <td>
                <input type="submit" value="Muuta" id="saada_button" name="submitButton">
            </td>
        </tr>
    </table>
</form>
<footer>
    ICD0007 Näidisrakendus
</footer>
</body>
</html>
