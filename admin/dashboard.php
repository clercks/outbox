<?php
session_start();


if (!isset($_SESSION['admin'])) {
header("Location: index.php");
exit;
}

include "../additions/connect.php";

$categories = ["Notice", "Event", "Exam", "General"];

$error = "";
$id = "";
$title = "";
$category = "";
$body = "";


if (isset($_POST['delete'])) {
$stmt = $db->prepare("DELETE FROM posts WHERE id = ?");
$stmt->execute([$_POST['id']]);
header("Location: dashboard.php?msg=deleted");
exit;
}


if (isset($_GET['edit'])) {
$stmt = $db->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$_GET['edit']]);
$old = $stmt->fetch();

if ($old) {
$id = $old['id'];
$title = $old['title'];
$category = $old['category'];
$body = $old['body'];
}
}


if (isset($_POST['save'])) {
$id = $_POST['id'];
$title = trim($_POST['title']);
$category = $_POST['category'];
$body = trim($_POST['body']);

if ($title == "" || $category == "" || $body == "") {
$error = "Please fill in all the fields.";
} else {
if ($id == "") {
$stmt = $db->prepare("INSERT INTO posts (title, category, body, created_at) VALUES (?, ?, ?, ?)");
$stmt->execute([$title, $category, $body, date("Y-m-d H:i:s")]);
} else {
$stmt = $db->prepare("UPDATE posts SET title = ?, category = ?, body = ? WHERE id = ?");
$stmt->execute([$title, $category, $body, $id]);
}
header("Location: dashboard.php?msg=saved");
exit;
}
}


$posts = $db->query("SELECT * FROM posts ORDER BY created_at DESC, id DESC")->fetchAll();
$unread = $db->query("SELECT COUNT(*) FROM suggestions WHERE is_read = 0")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UICT Outbox - Admin</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>


<div class="navbar">
<div class="container">
<a href="dashboard.php" class="logo">UICT <span>Outbox</span> <small>Admin</small></a>
<div class="links">
<a href="dashboard.php" class="active">Posts</a>
<a href="suggestions.php">Suggestions<?php if ($unread > 0) { echo ' <span class="count">' . $unread . '</span>'; } ?></a>
<a href="index.php?logout=1">Logout</a>
</div>
</div>
</div>

<div class="container page">

<?php if (isset($_GET['msg']) && $_GET['msg'] == "saved") { ?>
<div class="alert success">Post saved.</div>
<?php } ?>
<?php if (isset($_GET['msg']) && $_GET['msg'] == "deleted") { ?>
<div class="alert success">Post deleted.</div>
<?php } ?>


<div class="box">
<h2><?php if ($id == "") { echo "Add New Post"; } else { echo "Edit Post"; } ?></h2>

<?php if ($error != "") { ?>
<div class="alert error"><?php echo htmlspecialchars($error); ?></div>
<?php } ?>

<form method="post" action="dashboard.php">
<input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

<label>Title</label>
<input type="text" name="title" maxlength="150" value="<?php echo htmlspecialchars($title); ?>">

<label>Category</label>
<select name="category">
<option value="">-- Choose a category --</option>
<?php foreach ($categories as $c) { ?>
<option value="<?php echo $c; ?>" <?php if ($category == $c) { echo "selected"; } ?>><?php echo $c; ?></option>
<?php } ?>
</select>

<label>Message</label>
<textarea name="body" rows="8"><?php echo htmlspecialchars($body); ?></textarea>

<button type="submit" name="save" class="button">Save Post</button>
<?php if ($id != "") { ?>
<a href="dashboard.php" class="cancel">Cancel</a>
<?php } ?>
</form>
</div>


<div class="box">
<h2>All Posts</h2>

<?php if (count($posts) == 0) { ?>
<p>No posts yet.</p>
<?php } else { ?>
<div class="table-box">
<table>
<tr>
<th>Title</th>
<th>Category</th>
<th>Date</th>
<th>Action</th>
</tr>
<?php foreach ($posts as $post) { ?>
<tr>
<td><?php echo htmlspecialchars($post['title']); ?></td>
<td><?php echo htmlspecialchars($post['category']); ?></td>
<td><?php echo date("d M Y", strtotime($post['created_at'])); ?></td>
<td class="actions">
<a href="dashboard.php?edit=<?php echo $post['id']; ?>" class="button small">Edit</a>
<form method="post" action="dashboard.php" onsubmit="return confirm('Delete this post?');">
<input type="hidden" name="id" value="<?php echo $post['id']; ?>">
<button type="submit" name="delete" class="button small red">Delete</button>
</form>
</td>
</tr>
<?php } ?>
</table>
</div>
<?php } ?>
</div>

</div>

<div class="footer">
<div class="copyright">
<p>UICT Outbox Admin</p>
</div>
</div>

</body>
</html>
