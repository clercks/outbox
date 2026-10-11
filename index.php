<?php
include "additions/connect.php";


$posts = $db->query("SELECT * FROM posts ORDER BY created_at DESC, id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UICT Outbox - Home</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>


<div class="navbar">
<div class="container">
<a href="index.php" class="logo">UICT <span>Outbox</span></a>
<div class="links">
<a href="index.php" class="active">Home</a>
<a href="apply.html">Apply</a>
<a href="contact.html">Contact</a>
<a href="suggestion.php">Suggestion Box</a>
</div>
</div>
</div>


<div class="banner">
<h1>Welcome to UICT Outbox</h1>
<p>School news, notices and updates in one place</p>
</div>


<div class="container news">
<h2 class="page-title">Latest News</h2>

<?php if (count($posts) == 0) { ?>
<p>No news has been posted yet. Check back soon!</p>
<?php } ?>

<?php foreach ($posts as $post) { ?>
<div class="post">
<span class="tag <?php echo htmlspecialchars(strtolower($post['category'])); ?>"><?php echo htmlspecialchars($post['category']); ?></span>
<span class="date"><?php echo date("d M Y", strtotime($post['created_at'])); ?></span>
<h3><?php echo htmlspecialchars($post['title']); ?></h3>
<p><?php echo nl2br(htmlspecialchars($post['body'])); ?></p>
</div>
<?php } ?>
</div>


<div class="footer">
<div class="container footer-boxes">
<div>
<h3>UICT Outbox</h3>
<p>Your place for school news, notices and updates.</p>
</div>
<div>
<h3>Quick Links</h3>
<p><a href="index.php">Home</a></p>
<p><a href="apply.html">Apply</a></p>
<p><a href="contact.html">Contact</a></p>
<p><a href="suggestion.php">Suggestion Box</a></p>
</div>
<div>
<h3>Contact</h3>
<p>Kampala, Uganda</p>
<p>+256 700 000 000</p>
<p>info@uict.ac.ug</p>
</div>
</div>
<div class="copyright">
<p>&copy; 2026 UICT Outbox. All rights reserved.</p>
</div>
</div>

</body>
</html>
