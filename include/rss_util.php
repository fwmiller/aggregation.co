<?php

/*
 * Returns the string with various external links, images, iframes, and
 * the last "Read more..." paragraph removed
 */
function RemoveLinks($desc)
{
        $desc = preg_replace("/<p.*Read.*more.*<\/p>/", "", $desc);
	$desc = preg_replace( '|#more-[0-9]+|', '', $desc );
        $desc = preg_replace("/Read.*more/", "", $desc);
        $desc = preg_replace("/<br.*>/", "", $desc);
        $desc = preg_replace("/<a\b[^>]*>/", "", $desc);
        $desc = preg_replace("/<\/a>/", "", $desc);
        $desc = preg_replace("/<img\b[^>]*>/", "", $desc);
        $desc = preg_replace("/<\/img>/", "", $desc);
        $desc = preg_replace("/<iframe\b[^>]*>/", "", $desc);
        $desc = preg_replace("/<\/iframe>/", "", $desc);
        return $desc;
}

/* Chronological comparison used for sorting all feed items */
function RSS_CMP($a, $b) {
	date_default_timezone_set("America/Denver");
	$a = strtotime($a['itemPubDate']);
	$b = strtotime($b['itemPubDate']);
	if ($a == $b)
		return 0;

	if ($a > $b)
		return (-1);
	else
		return 1;
}

/*
 * Returns array of items representing all RSS items in the $feed loaded
 * from the feed itself
 */
function LoadItems($id, $feed)
{
	/* Load items into global $rssItems array */
	$rssItems = array();
	try {
		$rss = simplexml_load_file($feed);
	} catch (Exception $e) {
		echo "<div>Load failed \"" . $feed . "\"</div>\n";
		return;
	}
	echo "<div>feedTitle: " . $rss->channel->title . "</div>";
	echo "<div>" . count($rss) . " items</div>";

	if ($rss->channel->item)
		$items = $rss->channel->item;
	else {
		/* Assume the feed is Atom and start over */
		$contents = file_get_contents($feed);
		//$contents = preg_replace("/\<.*:/", "<", $contents);

		try {
			$rss = simplexml_load_string($contents);
		} catch (Exception $e) {
			echo "<div>Load atom feed failed \"" . $feed .
				"\"</div>\n";
			return;
		}
echo "<pre>";
print_r($rss);
echo "</pre>";
		$items = $rss->item;
	}

	foreach ($items as $item) {
		$item = array(
			"id" => $id,
			"feedTitle" => $rss->channel->title,
			"feedLink" => $rss->channel->link,
			"itemTitle" => $item->title,
			"itemPubDate" => $item->pubDate,
			"itemLink" => $item->link,
			"itemDesc" =>
				RemoveLinks($item->description));
		array_push($rssItems, $item);
	}
	/* Sort all items from all feeds in reverse chronological order */
	usort($rssItems, 'RSS_CMP');

	return $rssItems;
}

/* Returns array of items from $items list */
function LoadCachedItems($items)
{
	/* Load items from each feed link into global $rssItems array */
	$rssItems = array();
	foreach ($items as $row) {
		$id = $row['id'];
		$feedTitle = substr($row['feedTitle'], 1, -1);
		$feedLink = $row['feedLink'];
		$itemTitle = substr($row['itemTitle'], 1, -1);
		$itemPubDate = $row['itemPubDate'];
		$itemLink = substr($row['itemLink'], 1, -1);
		$itemDesc = substr($row['itemDesc'], 1, -1);

		$item = array(
			"id" => $id,
			"feedTitle" => $feedTitle,
			"feedLink" => $feedLink,
			"itemTitle" => $itemTitle,
			"itemPubDate" => $itemPubDate,
			"itemLink" => $itemLink,
			"itemDesc" => $itemDesc);

		array_push($rssItems, $item);
	}
	/*
	 * Sort all items from all feeds in reverse chronological
	 * order
	 */
	usort($rssItems, 'RSS_CMP');

	return $rssItems;
}

/* Generate HTML for display of each RSS item's raw storage */
function DisplayRawItems($rssItems)
{
	foreach ($rssItems as $item) {
		echo "Feed title      : " . $item['feedTitle'] . "\n";
		echo "Feed link       : " . $item['feedLink'] . "\n";
		echo "Item title      : " . $item['itemTitle'] . "\n";
		echo "Item pub date   : " . $item['itemPubDate'] . "\n";
		echo "Item link       : " . $item['itemLink'] . "\n";
		echo "Item description: " . $item['itemDesc'] . "\n";
	}
}
