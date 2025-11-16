<?php
session_start();

// cek login
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

// file json untuk menyimpan data kontak
$jsonFile = __DIR__ . "/uploads/contacts.json";

// buat folder dan file json jika belum ada
if (!file_exists(__DIR__ . "/uploads")) {
    mkdir(__DIR__ . "/uploads", 0777, true);
}
if (!file_exists($jsonFile)) {
    file_put_contents($jsonFile, json_encode([]));
}

// baca data json
$contacts = json_decode(file_get_contents($jsonFile), true);
if (!is_array($contacts)) $contacts = [];

// tambah kontak
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if ($name === "") $errors[] = "Nama harus diisi";
    if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email tidak valid";

    if (empty($errors)) {
        $contacts[] = [
            "name" => $name,
            "email" => $email,
            "phone" => $phone
        ];

        file_put_contents($jsonFile, json_encode($contacts, JSON_PRETTY_PRINT));
        header("Location: index.php");
        exit;
    }
}

// hapus kontak
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    unset($contacts[$id]);
    $contacts = array_values($contacts);
    file_put_contents($jsonFile, json_encode($contacts, JSON_PRETTY_PRINT));
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Manajemen Kontak</title></head>
<body>

<a href="login.php?logout=1">Logout</a>

<h2>Tambah Kontak</h2>

<?php foreach ($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>

<form method="POST">
    Nama: <input type="text" name="name" required><br><br>
    Email: <input type="text" name="email"><br><br>
    Telepon: <input type="text" name="phone"><br><br>
    <button type="submit" name="add">Simpan</button>
</form>

<hr>

<h2>Daftar Kontak</h2>

<table border="1" cellpadding="6">
<tr><th>Nama</th><th>Email</th><th>Telepon</th><th>Aksi</th></tr>

<?php foreach ($contacts as $id => $c): ?>
<tr>
    <td><?= htmlspecialchars($c['name']) ?></td>
    <td><?= htmlspecialchars($c['email']) ?></td>
    <td><?= htmlspecialchars($c['phone']) ?></td>
    <td>
        <a href="edit.php?id=<?= $id ?>">Edit</a> |
        <a href="index.php?delete=<?= $id ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
