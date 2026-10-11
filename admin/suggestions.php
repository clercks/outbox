<?php
session_start();


if (!isset($_SESSION['admin'])) {
header("Location: index.php");
exit;
}

include "../additions/connect.php";


if (isset($_POST['delete'])) {
$stmt = $db->prepare("DELETE FROM suggestions WHERE id = ?");
$stmt->execute([$_POST['id']]);
header("Location: suggestions.php?msg=deleted");
exit;
}

$suggestions = $db->query("SELECT * FROM suggestions ORDER BY created_at DESC, id DESC")->fetchAll();


$db->exec("UPDATE suggestions SET is_read = 1");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UICT Outbox - Suggestions</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>


<div class="navbar">
<div class="container">
<a href="dashboard.php" class="logo">UICT <span>Outbox</span> <small>Admin</small></a>
<div class="links">
<a href="dashboard.php">Posts</a>
<a href="suggestions.php" class="active">Suggestions</a>
<a href="index.php?logout=1">Logout</a>
</div>
</div>
</div>

<div class="container page">
<h2 class="page-title">Anonymous Suggestions</h2>

<?php if (isset($_GET['msg']) && $_GET['msg'] == "deleted") { ?>
<div class="alert success">Suggestion deleted.</div>
<?php } ?>

<?php if (count($suggestions) == 0) { ?>
<div class="box"><p>No suggestions yet.</p></div>
<?php } ?>

<?php foreach ($suggestions as $s) { ?>
<div class="box suggestion">
<div class="suggestion-top">
<div>
<strong><?php echo htmlspecialchars($s['topic']); ?></strong>
<?php if ($s['is_read'] == 0) { ?><span class="tag general">NEW</span><?php } ?>
<span class="date"><?php echo date("d M Y, H:i", strtotime($s['created_at'])); ?></span>
</div>
<form method="post" action="suggestions.php" onsubmit="return confirm('Delete this suggestion?');">
<input type="hidden" name="id" value="<?php echo $s['id']; ?>">
<button type="submit" name="delete" class="button small red">Delete</button>
</form>
</div>
<p><?php echo nl2br(htmlspecialchars($s['message'])); ?></p>
<p class="from">Sent by: Anonymous</p>
</div>
<?php } ?>
</div>

<div class="footer">
<div class="copyright">
<p>UICT Outbox Admin</p>
</div>
</div>

</body>
</html>
