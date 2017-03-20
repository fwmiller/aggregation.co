<?php
require("include/header.php");
require("include/db.php");
require("include/login_check.php");
require("include/rss_util.php");
require("include/cms_util.php");
require("include/nav.php");

/*
 * If a specific feed is specified then only get the RSS items
 * associated with that feed
 */


/* Otherwise, load all RSS items associated with the user's feeds list */
/*$rssItems = FetchRSSItems($db, $_SESSION['id']); */
/*$rssItems = FetchCachedRSSItems($db, $_SESSION['id'], "http://avc.com");*/

if (isset($_GET['feed'])) {
	$rssItems = FetchCachedRSSItems($db, $_SESSION['id'], $_GET['feed']);
} else {
	$rssItems = FetchCachedRSSItems($db, $_SESSION['id'], NULL);
}

/* Display each item */
$rcnt = count($rssItems);
for ($r = 0; $r < $rcnt; $r++)
{
	if ($r == 0) {
		DisplayItem($r, $rssItems[$r], NULL);
	} else {
		DisplayItem($r, $rssItems[$r], $rssItems[$r - 1]);
	}
}

/*
 * Load RSS items by fetching them from their Internet RSS feed directly.
 * This is more time consuming but provides fresh results.
 */
function FetchRSSItems($db, $id)
{
	$query = "SELECT * FROM feeds WHERE id=" . $id;
	$rows = Query($db, $query);
	return LoadItems($rows);
}

/*
 * Load RSS items by fetching them the local cache of RSS items.  The
 * server maintains a cache of all the items associated with all the
 * feeds of all the users locally.  This routine queries this global
 * database of items based on the user's identity.  This is quicker
 * but the cache may be out of date by a short period of time with various
 * feeds.
 */
function FetchCachedRSSItems($db, $id, $feedLink)
{
	if ($feedLink != NULL) {
		$query = "SELECT * FROM items WHERE id=" . $id . " AND feedLink=\"" . $feedLink . "\"";
		$rows = Query($db, $query);
	} else {
		$query = "SELECT * FROM items WHERE id=" . $id;
		$rows = Query($db, $query);
	}
	return LoadCachedItems($rows);
}

/* Display item based on internal format */
function DisplayItem($count, $item, $previtem)
{
	echo "<div id=\"item\">";

	$cmsItem = new CmsItem();
	if ($count == 0)
		$cmsItem->first = TRUE;

	$cmsItem->feedLink = $item["feedLink"];
	$cmsItem->feedTitle = $item["feedTitle"];

	date_default_timezone_set("America/Denver");
	$cmsItem->pubDate = date("M j  g:ia", strtotime($item["itemPubDate"]));

	$cmsItem->itemLink = $item["itemLink"];
	$cmsItem->itemTitle = $item["itemTitle"];
	$cmsItem->itemDesc = $item["itemDesc"];

	if ($previtem == NULL ||
	    $item["feedLink"] != $previtem["feedLink"]) {
		$cmsItem->GenerateHtml(TRUE);
	} else {
		$cmsItem->GenerateHtml(FALSE);
	}
	echo "</div>";
}

require("include/footer.php");
?>
