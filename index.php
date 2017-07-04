<?php
require("include/db.php");
require("include/header.php");
require("include/nav.php");
require("include/rss_util.php");

echo "<div id=\"content\">";

// Display each RSS item
echo "<div id=\"content-left\">";

$query = "SELECT * FROM items";
if (isset($_GET['feed'])) {
	$query .= " WHERE id=" . $_GET['feed'];
}
$rows = Query($db, $query);
$rssItems = LoadCachedItems($rows);

$prev = NULL;
foreach ($rssItems as $item) {
	DisplayItem($prev, $item);
	$prev = $item;
}

echo "</div>";
echo "<div id=\"content-middle\">";

$query = "SELECT * FROM items";
if (isset($_GET['feed'])) {
	$query .= " WHERE id=" . $_GET['feed'];
}
$rows = Query($db, $query);
$rssItems = LoadCachedItems($rows);

$prev = NULL;
foreach ($rssItems as $item) {
	DisplayItem($prev, $item);
	$prev = $item;
}

echo "</div>";
echo "<div id=\"content-right\">";

$query = "SELECT * FROM items";
if (isset($_GET['feed'])) {
	$query .= " WHERE id=" . $_GET['feed'];
}
$rows = Query($db, $query);
$rssItems = LoadCachedItems($rows);

$prev = NULL;
foreach ($rssItems as $item) {
	DisplayItem($prev, $item);
	$prev = $item;
}

echo "</div>";

echo "</div>";


function DisplayItem($prev, $item)
{
    echo "<article>";

    // Separator (or not) and feed title
    if ($prev == NULL || $prev['feedTitle'] != $item['feedTitle'] ) {
	echo "<div class=\"itemSep\"></div>\n";

	// Feed favicon.ico
	$url = preg_replace('/^https?:\/\//', '', $item['feedLink']);
	if ($url != "") {
		$imgurl = "https://www.google.com/s2/favicons?domain=";
		$imgurl .= $url;

		echo "<div class=\"feedIcon\">";
		"\" type=\"image/x-icon\"></div>\n";
		echo '<img src="';
		echo $imgurl;
		echo '" width="16" height="16" />';
		echo "</div>\n";
	}

	// Feed title
	if (($item['feedTitle'] != NULL) &&
	    (strlen($item['feedTitle']) > 0)) {
		echo "<span class=\"feedTitle\">" .
			"<a href=\"http://aggregation.co?feed=" .
			$item['id'] . "\">" .  $item['feedTitle'] .
			"</a></span>";
	}
    }
    // Item pub date
    date_default_timezone_set("America/Denver");
    echo "<span class=\"itemPubDate\">" .
	date("M j  g:ia", strtotime($item['itemPubDate'])) .
	"</span>\n";

    // Item title
    echo "<div class=\"itemTitle\">";

    if (($item['itemTitle'] != NULL) && (strlen($item['itemTitle']) > 0)) {

        if ($item['itemLink'] != NULL)
	    echo "<a href=\"" . $item['itemLink'] . "\">";

	echo $item['itemTitle'];

        if ($item['itemLink'] != NULL)
	    echo "</a>";

    }
    echo "</div>";

    // Item description
    echo "<div class=\"itemDesc\">" . $item['itemDesc'] . "</div>\n";

    echo "</article>\n";
}

require("include/footer.php");
