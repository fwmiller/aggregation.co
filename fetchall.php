<?php

require("include/header.php");
require("include/db.php");
require("include/rss_util.php");

echo "<pre>";

// Get list of users
$query = "SELECT * FROM users";
$rows = Query($db, $query);
foreach ($rows as $user) {
	$id = $user['id'];
	echo "\nUser ID: " . $id . "\n";

	// Get list of feeds for this user
	$feedsquery = "SELECT * FROM feeds WHERE id=" . $id;
	$feedsrows = Query($db, $feedsquery);

	foreach ($feedsrows as $feed) {
		echo "Feed: " . $feed['link'] . "\n";
	}
	// Load items for all feeds for this user
	$rssItems = LoadItems($feedsrows);

	foreach ($rssItems as $item) {
		// Check whether item already exists in the items table
		$itemquery =
			"SELECT * FROM items WHERE id=" .
			$id .
			" AND feedLink=\"" .
			$item['feedLink'] .
			"\" AND itemLink=\"" .
			RealEscapeString($db, $item['itemLink']) .
			"\"";

		$itemrows = Query($db, $itemquery);
		if (count($itemrows) == 0) {
			// Insert the item in the items table
			$insertquery =
				"INSERT INTO items (id, feedTitle, feedLink, itemTitle, itemPubDate, itemLink, itemDesc) VALUES (" .
				$id .
			        ",\"" . 
				RealEscapeString($db, $item['feedTitle']) . 
				"\",\"" .
				$item['feedLink'] . 
				"\",\"" .
				RealEscapeString($db, $item['itemTitle']) . 
				"\",\"" .
				$item['itemPubDate'] . 
				"\",\"" .
				RealEscapeString($db, $item['itemLink']) . 
				"\",\"" .
				RealEscapeString($db, $item['itemDesc']) . 
				"\")";
			Query($db, $insertquery);
		}
	}
}

echo "</pre>";

require("include/footer.php");
