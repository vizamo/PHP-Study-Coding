<?php
require_once "function.php";
$conn = getConnection();

$valueback = $conn->prepare("select id, title, author1, author2, grade from books ORDER BY id DESC");
$valueback->execute();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Raamatud</title>
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
        <td class="pk_text">Pealkiri</td>
        <td class="pk_text">Autorid</td>
        <td class="pk_mark">Hinne</td>
    </tr>
</table>

<table class="fill_list">
    <?php foreach ($valueback as $row){
        $grade = $row[4];
        $aut1 = $row[2];
        $aut2 = $row[3];
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
        }
        $precheck1 = $conn->prepare("SELECT count(*) FROM authors WHERE id = '$aut1';");
        $precheck1->execute();
        foreach ($precheck1 as $pr1) {
            $pr1 = $pr1[0];
            if ($pr1 > 0) {
                $author1 = $conn->prepare("select id, firstName, lastName, grade from authors where id = '$aut1'");
                $author1->execute();
                foreach ($author1 as $a1) {
                    $id1 = $aut1;
                    $firstName = $a1[1];
                    $lastName = $a1[2];
                    $name1 = $firstName . " " . $lastName;
                }
            }
            else {
                $aut1 = 0;
            }
        }
        $precheck2 = $conn->prepare("SELECT count(*) FROM authors WHERE id = '$aut2';");
        $precheck2->execute();
        foreach ($precheck2 as $pr2) {
            $pr2 = $pr2[0];
            if ($pr2 > 0) {
                $author2 = $conn->prepare("select id, firstName, lastName, grade from authors where id = '$aut2'");
                $author2->execute();
                foreach ($author2 as $a2) {
                    $id2 = $aut2;
                    $firstName = $a2[1];
                    $lastName = $a2[2];
                    $name2 = $firstName . " " . $lastName;
                }
            } else {
                $aut2 = 0;
            }
        }
        ?>
        <tr>
            <td class="pk_text"><a href="01-2ChangeRaamat.php?id=<?= $row[0] ?>"><?= $row[1]?></a></td>
            <td class="pk_text"><?php
                if ($aut1 > 0 and $aut2 > 0) {
                    echo $name1 . ", " . $name2;
                }
                if ($aut1 > 0 and $aut2 <= 0) {
                    echo $name1;
                }
                if ($aut1 <= 0 and $aut2 > 0) {
                    echo $name2;
                }
                ?></td>
            <td class="pk_mark"><span class="hinne-full"><?= $grade?></span><span class="hinne-empty"><?= $gradeF?></span></td>
        </tr>
    <?php }?>
</table>
    <footer>
        ICD0007 Näidisrakendus
    </footer>
</body>
</html>

<?php
require_once "00sql-connection.php";
$conn = getConnection();

$valueback = $conn->prepare("select id, title, author1, author2, grade from books ORDER BY id DESC");
$valueback->execute();

$data = $conn->prepare("SELECT
    books.id,
    books.title,
    CONCAT(a1.firstName , ' ' , a1.lastName) as a1,
    CONCAT(a2.firstName , ' ' , a2.lastName) as a2,
    books.grade
FROM (books
         LEFT JOIN authors a1
                    ON a1.id = books.author1
         LEFT JOIN authors a2
                   ON a2.id = books.author2
)ORDER BY books.id DESC;");
$data->execute();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Raamatud</title>
    <link href="styles.css" rel="stylesheet">
</head>

<body>
<div class="header_of_site">
    <a class="clear_link" href="01RaamatList.php" id="book-list-link">Raamatud</a>|
    <a class="clear_link" href="index.php" id="book-form-link">Lisa oma raamat</a>|
    <a class="clear_link" href="03AutorList.php" id="author-list-link">Autorid</a>|
    <a class="clear_link" href="04AddAutor.php" id="author-form-link">Lisa autor</a>|
</div>
<?php
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $call = isset($_GET["call"]) ? $_GET["call"] : "";
    if ($call == 1) { ?>
        <div class="suc-add-box" id="message-block">Lisatud!</div>
    <?php }
    if ($call == 2) { ?>
        <div class="suc-change-box" id="message-block">Muudetud!</div>
    <?php }
    if ($call == 3) { ?>
        <div class="suc-del-box" id="message-block">Kustutatud!</div>
    <?php }
}
?>

<table class="table_heading">
    <tr>
        <td class="table_values_text">Pealkiri</td>
        <td class="table_values_text">Autorid</td>
        <td class="table_values_grade">Hinne</td>
    </tr>
</table>

<table class="table_values_data">
    <?php foreach ($valueback as $row){
        $grade = $row[4];
        $aut1 = $row[2];
        $aut2 = $row[3];
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
        }
        $precheck1 = $conn->prepare("SELECT count(*) FROM authors WHERE id = '$aut1';");
        $precheck1->execute();
        foreach ($precheck1 as $pr1) {
            $pr1 = $pr1[0];
            if ($pr1 > 0) {
                $author1 = $conn->prepare("select id, firstName, lastName, grade from authors where id = '$aut1'");
                $author1->execute();
                foreach ($author1 as $a1) {
                    $id1 = $aut1;
                    $firstName = $a1[1];
                    $lastName = $a1[2];
                    $name1 = $firstName . " " . $lastName;
                }
            }
            else {
                $aut1 = 0;
            }
        }
        $precheck2 = $conn->prepare("SELECT count(*) FROM authors WHERE id = '$aut2';");
        $precheck2->execute();
        foreach ($precheck2 as $pr2) {
            $pr2 = $pr2[0];
            if ($pr2 > 0) {
                $author2 = $conn->prepare("select id, firstName, lastName, grade from authors where id = '$aut2'");
                $author2->execute();
                foreach ($author2 as $a2) {
                    $id2 = $aut2;
                    $firstName = $a2[1];
                    $lastName = $a2[2];
                    $name2 = $firstName . " " . $lastName;
                }
            } else {
                $aut2 = 0;
            }
        }
        ?>
        <?php foreach ($data as $line) {
            $grade = $row[4];
            $aut1 = $row[2];
            $aut2 = $row[3];
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
            }
        }
        ?>
        <tr>
            <td class="table_values_text"><a href="01-2ChangeRaamat.php?id=<?= $line[0] ?>"><?= $line[1]?></a></td>
            <td class="table_values_text"><?php
                if ($aut1 != 0 and $aut2 != 0) {
                    echo $line[2] . ", " . $line[3];
                }
                if ($aut1 != 0 and $aut2 === 0) {
                    echo $line[2];
                }
                if ($aut1 === 0 and $aut2 != 0) {
                    echo $line[3];
                }
                ?></td>
            <td class="table_values_grade"><span class="grade-full"><?= $grade?></span><span class="grade-empty"><?= $gradeF?></span></td>
        </tr>
    <?php }?>
</table>
<footer>
    ICD0007 Näidisrakendus
</footer>
</body>
</html>