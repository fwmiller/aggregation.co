<?php

require("include/header.php");
require("include/db.php");
require("include/rss_util.php");

echo "<pre>";

// Get user id
echo "User ID: " . $_SESSION['id'] . "\n\n";

// Get feeds
$query = "SELECT * FROM feeds WHERE id=" . $_SESSION['id'];
$rows = Query($db, $query);

// Load items for all feeds
$rssItems = LoadItems($rows);

// Display each RSS item
foreach ($rssItems as $item) {
	echo "Feed title      : " . $item['feedTitle'] . "\n";
	echo "Feed link       : " . $item['feedLink'] . "\n";
	echo "Item title      : " . $item['itemTitle'] . "\n";
	echo "Item pub date   : " . $item['itemPubDate'] . "\n";
	echo "Item link       : " . $item['itemLink'] . "\n";
	echo "Item description: " . $item['itemDesc'] . "\n";
}

echo "</pre>";

require("include/footer.php");
