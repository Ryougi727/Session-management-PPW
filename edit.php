<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$jsonFile = __DIR__ . "/uploads/contacts.json";
$contacts = json_decode(file_get_contents($jsonFile), true);

if (!isset($_GET['id']) || !array_key_exists($_GET['id'], $contacts)) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$contact = $contacts[$id];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if ($name === "") $errors[] = "Nama harus diisi";
    if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email tidak valid";

    if (empty($errors)) {
        $contacts[$id] = [
            "name" => $name,
            "email" => $email,
            "phone" => $phone
        ];

        file_put_contents($jsonFile, json_encode($contacts, JSON_PRETTY_PRINT));
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Kontak</title></head>
<body>

<h2>Edit Kontak</h2>
<a href="index.php">Kembali</a><br><br>

<?php foreach ($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>

<form method="POST">
    Nama: <input type="text" name="name" value="<?= $contact['name'] ?>" required><br><br>
    Email: <input type="text" name="email" value="<?= $contact['email'] ?>"><br><br>
    Telepon: <input type="text" name="phone" value="<?= $contact['phone'] ?>"><br><br>
    <button type="submit">Update</button>
</form>

</body>
</html>
