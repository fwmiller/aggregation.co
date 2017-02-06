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
	$a = strtotime($a["itemPubDate"]);
	$b = strtotime($b["itemPubDate"]);
	if ($a == $b)
		return 0;

	if ($a > $b)
		return (-1);
	else
		return 1;
}

/* Returns array of items representing all RSS items in the $rows */
function LoadItems($rows)
{
	/* Load items from each feed link into global $rssItems array */
	$rssItems = array();
	foreach ($rows as $row) {
		if (!($rss = simplexml_load_file($row["link"]))) {
			continue;
		}
		foreach ($rss->channel->item as $item) {
			$item = array(
				"feedTitle" => $rss->channel->title,
				"feedLink" => $rss->channel->link,
				"itemTitle" => $item->title,
				"itemPubDate" => $item->pubDate,
				"itemLink" => $item->link,
				"itemDesc" =>
					RemoveLinks($item->description));
			array_push($rssItems, $item);
		}
	}
	/*
	 * Sort all items from all feeds in reverse chronological
	 * order
	 */
	usort($rssItems, 'RSS_CMP');

	return $rssItems;
}

/*
 * Returns array of items from the cached items table representing all
 * RSS items in the $rows
 */
function LoadCachedItems($rows)
{
	/* Load items from each feed link into global $rssItems array */
	$rssItems = array();
	foreach ($rows as $row) {
		$feedTitle = substr($row["feedTitle"], 1, -1);
		$feedLink = $row["feedLink"];
		$itemTitle = substr($row["itemTitle"], 1, -1);
		$itemPubDate = $row["itemPubDate"];
		$itemLink = substr($row["itemLink"], 1, -1);
		$itemDesc = substr($row["itemDesc"], 1, -1);

		$item = array(
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
