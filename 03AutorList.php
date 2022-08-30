<?php
require_once "function.php";
$conn = getConnection();

$stmt = $conn->prepare("select id, firstName, lastName, grade from authors ORDER BY id DESC");
$stmt->execute();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Autorid</title>
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
<?php
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $call = isset($_GET["call"]) ? $_GET["call"] : "";
    if ($call == 1) { ?>
        <div class="msgbox" id="message-block">Lisatud!</div>
    <?php }
}
?>
<table id="fill_pk">
    <tr>
        <td class="pk_text">Eesnimi</td>
        <td class="pk_text">Perekonnanimi</td>
        <td class="pk_mark">Hinne</td>
    </tr>
</table>


<table class="fill_list">
    <?php foreach ($stmt as $row){
        $grade = $row[3];
        if ($grade == 0) {
            $grade = "";
            $gradeF = "";
        }
        if ($grade == 1) {
            $grade = "★";
            $gradeF = "★★★★";
        }
        if ($grade == 2) {
            $grade = "★★";
            $gradeF = "★★★";
        }
        if ($grade == 3) {
            $grade = "★★★";
            $gradeF = "★★";
        }
        if ($grade == 4) {
            $grade = "★★★★";
            $gradeF = "★";
        }
        if ($grade == 5) {
            $grade = "★★★★★";
            $gradeF = "";
        }?>
        <tr>
            <td class="pk_text"><a href="03-2ChangeAutor.php?id=<?= $row[0] ?>"><?= $row[1]?></a></td>
            <td class="pk_text"><?= $row[2]?></td>
            <td class="pk_mark"><span class="hinne-full"><?= $grade?></span><span class="hinne-empty"><?= $gradeF?></span></td>
        </tr>
    <?php }?>
</table>
<footer>
    ICD0007 Näidisrakendus
</footer>
</body>
</html>