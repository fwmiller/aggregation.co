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

		// Check whether item already exists in the items table
		$itemquery =
			"SELECT * FROM items WHERE id=" .
			$feed['id'] .
			" AND feedLink=\"" .
			$item['feedLink'] .
			"\" AND itemLink=\"" .
			RealEscapeString($db, $item['itemLink']) .
			"\"";

		$itemrows = Query($db, $itemquery);
		if (count($itemrows) == 0) {
			// Insert the item in the items table
			$insertquery =
				"INSERT INTO items (id,feedTitle,feedLink,itemTitle,itemPubDate,itemLink,itemDesc) VALUES (" .
				$feed['id'] . ",\"" . 
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

require("include/footer.php");
