<?php
include "additions/connect.php";

$topics = ["Academics", "Facilities", "Fees", "Student Life", "Other"];

$error = "";
$topic = "";
$message = "";


if (isset($_POST['send'])) {
$topic = $_POST['topic'];
$message = trim($_POST['message']);

if ($topic == "") {
$error = "Please choose a topic.";
} elseif (strlen($message) < 10) {
$error = "Your suggestion is too short. Please write at least 10 characters.";
} elseif (strlen($message) > 1000) {
$error = "Your suggestion is too long. Please keep it under 1000 characters.";
} else {
$stmt = $db->prepare("INSERT INTO suggestions (topic, message, created_at) VALUES (?, ?, ?)");
$stmt->execute([$topic, $message, date("Y-m-d H:i:s")]);


header("Location: suggestion.php?sent=1");
exit;
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UICT Outbox - Suggestion Box</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>


<div class="navbar">
<div class="container">
<a href="index.php" class="logo">UICT <span>Outbox</span></a>
<div class="links">
<a href="index.php">Home</a>
<a href="apply.html">Apply</a>
<a href="contact.html">Contact</a>
<a href="suggestion.php" class="active">Suggestion Box</a>
</div>
</div>
</div>


<div class="container page">
<h2 class="page-title">Suggestion Box</h2>
<p class="intro">Share your idea, complaint or advice. Your message is anonymous. We do not save your name, email or any personal details.</p>

<div class="box form-box">

<?php if (isset($_GET['sent'])) { ?>
<div class="alert success">Thank you! Your suggestion was sent anonymously.</div>
<?php } ?>

<?php if ($error != "") { ?>
<div class="alert error"><?php echo htmlspecialchars($error); ?></div>
<?php } ?>

<form method="post" action="suggestion.php">
<label>Topic</label>
<select name="topic">
<option value="">-- Choose a topic --</option>
<?php foreach ($topics as $t) { ?>
<option value="<?php echo htmlspecialchars($t); ?>" <?php if ($topic == $t) { echo "selected"; } ?>><?php echo htmlspecialchars($t); ?></option>
<?php } ?>
</select>

<label>Your suggestion</label>
<textarea name="message" rows="7" placeholder="Type your suggestion here..."><?php echo htmlspecialchars($message); ?></textarea>

<button type="submit" name="send" class="button">Send Anonymously</button>
</form>
</div>
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
