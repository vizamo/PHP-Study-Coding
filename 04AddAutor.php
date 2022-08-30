<?php
require_once "function.php";
$conn = getConnection();

$errors = [];
$firstName = "";
$lastName = "";
$grade = 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
        $stmt = $conn->prepare("insert into authors (firstName, lastName, grade) values (:firstName, :lastName, :grade);");
        $stmt->bindValue(':firstName', $firstName);
        $stmt->bindValue(':lastName', $lastName);
        $stmt->bindValue(':grade', $grade);
        $stmt->execute();
        header("Location: 03AutorList.php?call=1");
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
        action="04AddAutor.php"
        id="fill_form">
    <table class="fill_table">
        <tr>
            <td><label for="pk">Eesnimi:</label></td>
            <td> <input type="text" placeholder="Kirjuta autori eesnimi" id="pk" name="firstName"
                        value="<?= $firstName?>"></td>
        </tr>
        <tr>
            <td><label for="pk2">Perekonnanimi:</label></td>
            <td> <input type="text" placeholder="Kirjuta autori perekonnanimi." id="pk2" name="lastName"
                        value="<?= $lastName?>"></td>
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
            <td></td>
            <td>
                <input type="submit" value="Saada" id="saada_button" name="submitButton">
            </td>
        </tr>
    </table>
</form>
<footer>
    ICD0007 Näidisrakendus
</footer>
</body>
</html>