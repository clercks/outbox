<?php

$admin_username = "admin";
$admin_password = "admin123";

date_default_timezone_set("Africa/Kampala");

try {
$db = new PDO("sqlite:" . __DIR__ . "/outbox.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
die("Could not open the database. Check that pdo_sqlite is enabled in php.ini");
}


$db->exec("CREATE TABLE IF NOT EXISTS posts (
id INTEGER PRIMARY KEY AUTOINCREMENT,
title TEXT NOT NULL,
category TEXT NOT NULL,
body TEXT NOT NULL,
created_at TEXT NOT NULL
)");


$db->exec("CREATE TABLE IF NOT EXISTS suggestions (
id INTEGER PRIMARY KEY AUTOINCREMENT,
topic TEXT NOT NULL,
message TEXT NOT NULL,
is_read INTEGER NOT NULL DEFAULT 0,
created_at TEXT NOT NULL
)");


$count = $db->query("SELECT COUNT(*) FROM posts")->fetchColumn();

if ($count == 0) {
$stmt = $db->prepare("INSERT INTO posts (title, category, body, created_at) VALUES (?, ?, ?, ?)");

$stmt->execute([
"Welcome to UICT Outbox",
"General",
"This is the new place for school news and communication.\n\nCheck this page often for notices, events and exam information. If you have an idea to improve the school, use the Suggestion Box. It is completely anonymous.",
date("Y-m-d H:i:s", strtotime("-4 days"))
]);
$stmt->execute([
"Semester One Registration Reminder",
"Notice",
"All first year students should complete their semester one registration at the registrar's office.\n\nBring your admission letter and a valid ID. Students who do not register on time may not be allowed to sit for exams.",
date("Y-m-d H:i:s", strtotime("-3 days"))
]);
$stmt->execute([
"Freshers Welcome Event",
"Event",
"Come and meet your classmates and lecturers at the freshers welcome event.\n\nThere will be games, music and short talks about student clubs and activities. Everyone is invited.",
date("Y-m-d H:i:s", strtotime("-2 days"))
]);
$stmt->execute([
"Continuous Assessment Notice",
"Exam",
"Continuous assessment tests will start soon. Please check with your course lecturer for the exact dates and topics.\n\nCome early and carry your student ID to the exam room.",
date("Y-m-d H:i:s", strtotime("-1 days"))
]);
}
