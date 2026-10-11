<?php

session_start();


if (isset($_GET['logout'])) {
session_destroy();
header("Location: index.php");
exit;
}


if (isset($_SESSION['admin'])) {
header("Location: dashboard.php");
exit;
}

include "../additions/connect.php";

$error = "";

if (isset($_POST['login'])) {
if ($_POST['username'] === $admin_username && $_POST['password'] === $admin_password) {
session_regenerate_id(true);
$_SESSION['admin'] = true;
header("Location: dashboard.php");
exit;
} else {
$error = "Wrong username or password.";
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UICT Outbox - Admin Login</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body class="login">

<div class="login-box">
<h1>UICT <span>Outbox</span></h1>

<?php if ($error != "") { ?>
<div class="alert error"><?php echo htmlspecialchars($error); ?></div>
<?php } ?>

<form method="post" action="index.php">
<label>Username</label>
<input type="text" name="username" required>

<label>Password</label>
<input type="password" name="password" required>

<button type="submit" name="login" class="button full">Login</button>
</form>
</div>

</body>
</html>
