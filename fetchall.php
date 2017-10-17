<?php

require("include/header.php");
require("include/db.php");
require("include/rss_util.php");

require_once('php/autoloader.php');

date_default_timezone_set('America/Denver');

// Get feeds
$query = "SELECT * FROM feeds";
$rows = Query($db, $query);

// Load the items for each feed
foreach ($rows as $feed) {
	// Load items for all feeds
	echo "<div><b>Feed id " . $feed['id'] . " link: ";
	echo $feed['link'] . "</b></div>\n";

	$content = new SimplePie();
	$content->set_feed_url($feed['link']);
	$content->enable_order_by_date(false);
	$content->set_cache_location($_SERVER['DOCUMENT_ROOT'] . '/cache');
	$content->init();

	echo "<div>";
	echo $content->get_title();
	echo "</div>\n";

        // Display each RSS item
	foreach ($content->get_items() as $item) {

		// Check whether item already exists in the items table
		$itemquery =
			"SELECT * FROM items WHERE id=" .
			$feed['id'] .
			" AND feedLink=\"" .
			$item->get_feed()->get_permalink() .
			"\" AND itemLink=\"" .
			$item->get_permalink() .
			"\"";

		echo "itemquery=\"" . $itemquery . "\"\n";

		$itemrows = Query($db, $itemquery);
		if (count($itemrows) == 0) {
			echo "<div><b>";
			echo $item->get_title();
			echo "</b></div>";

			echo "<div>";
			echo $item->get_local_date();
			echo "</div>";

			echo "<div>";
			echo $item->get_description();
			echo "</div>";

			// Insert the item in the items table
			if ($item->get_title() == NULL) {

			$insertquery =
				"INSERT INTO items (id,feedTitle,feedLink,itemPubDate,itemLink,itemDesc) VALUES (" .
				$feed['id'] . ",'" . 
				$item->get_feed()->get_title() .
				"','" .
				$item->get_feed()->get_permalink() .
				"','" .
				$item->get_local_date() .
				"','" .
				$item->get_permalink() .
				"','" .
				RemoveLinks($item->get_description()) .
				"')";

			} else {

			$insertquery =
				"INSERT INTO items (id,feedTitle,feedLink,itemTitle,itemPubDate,itemLink,itemDesc) VALUES (" .
				$feed['id'] . ",'" . 
				$item->get_feed()->get_title() .
				"','" .
				$item->get_feed()->get_permalink() .
				"','" .
				$item->get_title() .
				"','" .
				$item->get_local_date() .
				"','" .
				$item->get_permalink() .
				"','" .
				RemoveLinks($item->get_description()) .
				"')";

			}

		echo "insertquery=\"" . $insertquery . "\"\n";

			Query($db, $insertquery);
		}
	}
}

require("include/footer.php");
