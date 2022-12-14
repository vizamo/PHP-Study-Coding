<?php
require_once "function.php";
$conn = getConnection();

$selectlist = $conn->prepare("select id, firstName, lastName, grade from authors ORDER BY id DESC");
$selectlist->execute();

$selectlist2 = $conn->prepare("select id, firstName, lastName, grade from authors ORDER BY id DESC");
$selectlist2->execute();

$errors = [];
$title = "";
$author1 = 0;
$author2 = 0;
$grade = 0;
$isRead = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" and $_REQUEST["submitButton"]) {
    $id = isset($_POST["id"]) ? $_POST["id"] : "";
    $title = isset($_POST["title"]) ? $_POST["title"] : "";
    $author1 = isset($_POST["author1"]) ? $_POST["author1"] : 0;
    $author2 = isset($_POST["author2"]) ? $_POST["author2"] : 0;
    $grade = isset($_POST["grade"]) ? $_POST["grade"] : 0;
    $isRead = isset($_POST["isRead"]) ? $_POST["isRead"] : "";

    if ($author1 === $author2) {
        $author2 = 0;
    }

    if (strlen($title) < 3 or strlen($title) > 23) {
        $errors[] = "Pealkiri peab olema 3-23 tahte";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE books SET title ='$title', author1 = '$author1', author2 = '$author2', grade = '$grade' where id = '$id';");
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':author1', $author1);
        $stmt->bindValue(':author2', $author2);
        $stmt->bindValue(':grade', $grade);
        $stmt->execute();
        header("Location: 01RaamatList.php?call=1");
    }
}
elseif ($_SERVER["REQUEST_METHOD"] === "POST" and $_REQUEST["deleteButton"]) {
    $id = isset($_POST["id"]) ? $_POST["id"] : "";
    $stmt = $conn->prepare("DELETE FROM books WHERE id = '$id';");
    $stmt->execute();
    header("Location: 01RaamatList.php?call=1");
}
else {
    $raamatID = intval($_GET["id"]);
    $stmt = $conn->prepare("select id, title, author1, author2, grade from books where id = '$raamatID'");
    $stmt->execute();
    foreach ($stmt as $row){
        $id = $row[0];
        $title = $row[1];
        $author1 = $row[2];
        $author2 = $row[3];
        $grade = $row[4];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Muuta raamat</title>
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
        action="01-2ChangeRaamat.php?call=1"
        id="fill_form">
    <input type="hidden" name="id" value="<?= $id?>"/>
    <table class="fill_table">
        <tr>
            <td><label for="title">Pealkiri:</label></td>
            <td> <input type="text" id="title" name="title"
                        value="<?= $title?>" placeholder="Kirjuta raamutu pealkirja"></td>
        </tr>
        <tr>
            <td><label for="author1">Autor #1</label> </td>
            <td>
                <select id="author1" name="author1">
                    <option value=0></option>
                    <?php foreach ($selectlist as $row){
                        $id = $row[0]?>
                        <option <?php if ($id == $author1) { ?> selected="selected" <?php }?>value="<?= $id ?>" ><?= $row[1]?> <?= $row[2]?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="author2">Author #2</label> </td>
            <td>
                <select id="author2" name="author2">
                    <option value=0></option>
                    <?php foreach ($selectlist2 as $row2) {
                        $id2 = $row2[0]?>
                        <option <?php if ($id2 == $author2) { ?> selected="selected" <?php }?> value="<?= $id2 ?>" ><?= $row2[1]?> <?= $row2[2]?></option>
                    <?php } ?>
                </select>
            </td>
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

            <td><label for="isRead">Loetud</label></td>
            <td>
                <input type="checkbox" id="isRead" name="isRead" value="1">
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
    ICD0007 N??idisrakendus
</footer>
</body>
</html>