<?php

require("include/header.php");
require("include/db.php");
require("include/rss_util.php");

// Get feeds
$query = "SELECT * FROM feeds";
$rows = Query($db, $query);

// Load the items for each feed
foreach ($rows as $feed) {
	// Load items for all feeds
	echo "<div>Feed id " . $feed['id'] . " link: ";
	echo $feed['link'] . "</div>\n";

	$rssItems = LoadItems($feed['id'], $feed['link']);

	// Display each RSS item
	foreach ($rssItems as $item) {
		echo "<pre>\n";
		echo "Feed id         : " . $item['id'] . "\n";
		echo "Feed title      : " . $item['feedTitle'] . "\n";
		echo "Feed link       : " . $item['feedLink'] . "\n";
		echo "Item title      : " . $item['itemTitle'] . "\n";
		echo "Item pub date   : " . $item['itemPubDate'] . "\n";
		echo "Item link       : " . $item['itemLink'] . "\n";
		echo "Item description: " . $item['itemDesc'] . "\n";
		echo "</pre>\n";
	}
}

require("include/footer.php");
