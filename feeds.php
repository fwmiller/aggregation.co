<?php
require("include/header.php");
require("include/db.php");
require("include/login_check.php");
require("include/nav.php");

// Check for an add feed operation
if (isset($_GET['add'])) {
	$url = $_GET['add'];

	// Check whether feed is valid
	if ($rss = simplexml_load_file($url)) {
		// XXX Check whether feed is a duplicate

		// Insert feed into database for this user
		$query = "INSERT INTO feeds (id, link, title) VALUES (" .
			 $_SESSION['id'] .
			 ",\"" .
			 $url .
			 "\",\"" .
			RealEscapeString($db, $rss->channel->title) .
			"\")";

		Query($db, $query);
	}
	header("location: feeds.php");
}

// Get all the user's feeds
$query = "SELECT * FROM feeds WHERE id=" . $_SESSION['id'];
$rows = Query($db, $query);

// Create an array of links and titles
$titles = array();
foreach ($rows as $row) {
	$rss = simplexml_load_file($row['link']);
	if ($rss) {
		if (strlen($rss->channel->title) == 0) {
			array_push($titles,
				   array("link" => $row['link'],
					 "title" => $row['link']));
		} else {
			array_push($titles,
				   array("link" => $row['link'],
					 "title" => $rss->channel->title));
		}
	}
}

// Check for a delete feed operation
if (isset($_GET['delete'])) {
	$url = $_GET['delete'];

	echo "<p>url: " . $url . "</p>";

	foreach ($titles as $t) {

		echo "<p>link: " . $t['link'] . "</p>";

		if ($t['link'] === $url) {
			$query = "DELETE FROM feeds WHERE id=" .
				$_SESSION['id'] .  " AND link='" .
				$t['link'] .  "'";
			Query($db, $query);
			header("location: feeds.php");
		}
	}
	// header("location: feeds.php");
}

?> 

<center>

<form id="addFeed" action="feeds.php" method="get">
<table>
  <tr valign="middle">
    <td align="right">Feed URL:</td>
    <td align="left"><input type="text" name="add" value="" /></td>
    <td align="left"><input type="submit" value="Add Feed" /></td>
  </tr>
</table>
</form>

<table>

<?php foreach ($titles as $title) { ?>
  <tr>
    <td><div class="cellSep"></div>
    <div class="cellTitle"><?php echo $title['title']; ?></div></td>

    <td rowspan="2" valign="center"><div class="cellButton">
      <form action="feeds.php" method="get">
        <input type="hidden" name="delete"
		value="<?php echo $title['link'] ?>" />
        <input type="submit" value="Delete" />
      </form>
    </div></td>
  </tr>
  <tr>
    <td><div class="cellLink"><?php echo $title['link']; ?></div></td>
    <td></td>
  </tr>
<?php } ?>

</table>
</center>

<?php require("include/footer.php"); ?>
