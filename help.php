<?php
require("include/header.php");
require("include/nav.php");
?>

<div class="help">This is a curated RSS aggregator.</div>

<div class="help">As the curator, I choose which feeds are included. I
also decide when they change, without notice.  I live in Denver, Colorado
so the local news will be from there.  I am not responsible for the content,
I simply aggregate feeds.</div>

<div class="help">I can't find any websites I like but I'd like to have
an online newspaper I can go to.  I basically wrote it for my own personal
use but anyone can look at it anytime.  No Javascript, no ads, no tracking
of anything.  That's old school, but, enjoy!</div>

<div class="help">The top of each page contains a navigation menu.  The
<b>News</b> link takes you to the main news page.  The <b>Feeds</b> link
takes you to a list of the feeds that are merged into the main news
page.</div>

<div class="help">The following figure is an excerpt from an example of
the main news page, there is a list of four news items.  Each item has
a <b>Feed Filter Link</b>.  If you click this link, the main news page
will be filtered to have only stories from this feed.  The first item
is an example of an item with no title.</div>

<div class="help">
<center><img width="400px" src="image/MainNewsPage.png"/></center>
</div>

<div class="help">The remaining items in this example have titles that
are presented as <b>Item Title Links</b>.  If you click on these links,
you will go to the story on the site that hosts the RSS feed.  This
means you leave the aggregation.co site and must use the browser back
arrow to return to the news feed.</div>

<div class="help">Note the last two items in this example are from the
same feed.  When this occurs chronologically in the aggregation, no
horizontal separator is used and the Feed Filter Link is not repeated.</div>

<?php
require("include/footer.php");
?>
