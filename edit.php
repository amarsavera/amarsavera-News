<?php

require_once '../includes/config.php';

$id = $_GET['id'] ?? 0;

$title='';
$link='';
$position='header';

if($id)
{
    $stmt=$pdo->prepare("
    SELECT *
    FROM advertisements
    WHERE id=?
    ");

    $stmt->execute([$id]);

    $row=$stmt->fetch();

    if($row)
    {
        $title=$row['title'];
        $link=$row['link'];
        $position=$row['position'];
    }
}

if(isset($_POST['save']))
{
    $title=$_POST['title'];
    $link=$_POST['link'];
    $position=$_POST['position'];

    $image='';

    if(!empty($_FILES['image']['name']))
    {
        $filename=
        time().'_'.
        $_FILES['image']['name'];

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            '../assets/uploads/ads/'.$filename
        );

        $image=
        'assets/uploads/ads/'.$filename;
    }

    if($id)
    {
        $stmt=$pdo->prepare("
        UPDATE advertisements
        SET
        title=?,
        link=?,
        position=?
        WHERE id=?
        ");

        $stmt->execute([
            $title,
            $link,
            $position,
            $id
        ]);
    }
    else
    {
        $stmt=$pdo->prepare("
        INSERT INTO advertisements
        (
            title,
            image,
            link,
            position
        )
        VALUES
        (
            ?,?,?,?
        )
        ");

        $stmt->execute([
            $title,
            $image,
            $link,
            $position
        ]);
    }

    header("Location:index.php");
    exit;
}

?>

<form
method="post"
enctype="multipart/form-data">

<input
type="text"
name="title"
placeholder="Title"
value="<?= htmlspecialchars($title); ?>"
required>

<br><br>

<input
type="text"
name="link"
placeholder="URL"
value="<?= htmlspecialchars($link); ?>">

<br><br>

<select name="position">

<option value="header">
Header
</option>

<option value="sidebar">
Sidebar
</option>

<option value="footer">
Footer
</option>

</select>

<br><br>

<input
type="file"
name="image">

<br><br>

<button
type="submit"
name="save">

Save

</button>

</form>