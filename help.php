<?php
require("include/header.php");
require("include/db.php");
require("include/login_check.php");
require("include/nav.php");
?>

<section>

<p>
Welcome to <b>aggregation.co</b>
</p>

<p>
This site is an RSS aggregator.  It brings together multiple RSS feeds and
presents them in a simple way.
</p>

<p>
The site requires a login.  When you first enter the site, you must register
and after that use your login information to access your set of RSS feeds.
</p>

<p>
You password is stored on our site as a <a href="https://en.wikipedia.org/wiki/Salt_%28cryptography%29">salted hash</a>.  This means we have no idea what it is and have no way to recover it if you forget it.
</p>

<p>
Each page contains a header at the top.  The header displays a menu.
The <b>News</b> menu item provides the most recent data provided
by each of your RSS feeds in a combined, reverse chonological list
</p>

<!--
<p>
The <b>Members</b> menu item provides a list of the members currently
registered on the site.  Each member's email address is also available
</p>
-->

<p>
The <b>Settings</b> menu item allows you to configure your RSS feeds.
</p>

<p>
The <b>Help</b> menu item displays this page.
</p>

<p>
The <b>Logout</b> menu logs you out of the site.
</p>

<p>
You can enter multiple RSS feeds as sources of news for your aggregation.
Simply copy the RSS feed URL into the input on the <b>Settings</b> page.
When you visit the <b>News</b> page you'll see the items associated with
your selected RSS feed merged into your aggregation.
</p>

<p><a href="mailto:frank@frankwmiller.net">Support</a></p>

</section>

<?php require("include/footer.php"); ?>
