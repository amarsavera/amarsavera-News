<?php

require_once 'includes/config.php';

$message='';

if($_SERVER['REQUEST_METHOD']=='POST')
{
    $stmt=$pdo->prepare("
    INSERT INTO contact_messages
    (
        name,
        email,
        subject,
        message,
        created_at
    )
    VALUES
    (
        ?,?,?,?,NOW()
    )
    ");

    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['subject'],
        $_POST['message']
    ]);

    $message='Message Sent Successfully';
}

include 'includes/header.php';

?>

<div class="container mt-4">

<h2>Contact Us</h2>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<form method="POST">

<input
type="text"
name="name"
class="form-control mb-3"
placeholder="Name"
required>

<input
type="email"
name="email"
class="form-control mb-3"
placeholder="Email"
required>

<input
type="text"
name="subject"
class="form-control mb-3"
placeholder="Subject"
required>

<textarea
name="message"
class="form-control mb-3"
rows="5"
required></textarea>

<button
class="btn btn-danger">

Send Message

</button>

</form>

</div>

<?php include 'includes/footer.php'; ?>