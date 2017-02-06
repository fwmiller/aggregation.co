<?php

class CmsItem {
	public $first = FALSE;
	public $feedLink = NULL;
	public $feedTitle = NULL;
	public $pubDate = NULL;
	public $itemLink = NULL;
	public $itemTitle = NULL;
	public $itemDesc = NULL;

	public function GenerateHtml($firstForFeedLink)
	{
		$this->Separator($firstForFeedLink);
		$this->FeedLink($firstForFeedLink);
		$this->FeedTitle($firstForFeedLink);
		$this->PubDate();
		$this->ItemTitle();
		$this->ItemDesc();
	}

	private function Separator($firstForFeedLink)
	{
		if (!$firstForFeedLink) {
			echo "<div class=\"itemSepSameFeed\"></div>";
			return;
		}
		if ($this->first == TRUE)
			echo "<div class=\"itemSepTop\"></div>";
		else
			echo "<div class=\"itemSep\"></div>";
	}

	private function FeedLink($firstForFeedLink)
	{
		if (!$firstForFeedLink) {
			//echo "<div></div>";
			return;
		}

		// Feed favicon.ico
		$url = parse_url($this->feedLink);
		$favicon = $url["scheme"] .  "://" .
			$url["host"] .  "/favicon.ico";

		try {
			$array = get_headers($favicon);
			$string = $array[0];
			if(!strpos($string,"200")) {
				echo "<div class=\"feedIcon\"><img src=\"" .
					"http://aggregation.co/favicon.ico" .
					"\" type=\"image/x-icon\"></div>";

				return;
			}

		} catch (Exception $e) {

		}
		echo "<div class=\"feedIcon\"><img src=\"" .
			$favicon .
			"\" type=\"image/x-icon\"></div>";
	}

	private function FeedTitle($firstForFeedLink)
	{
		if (!$firstForFeedLink) {
			//echo "<div></div>";
			return;
		}

		if (($this->feedTitle != NULL) &&
		    (strlen($this->feedTitle) > 0)) {
			echo "<div class=\"feedTitle\">" .
				$this->feedTitle . "</div>";
		}
	}

	private function PubDate()
	{
		if ($this->pubDate == NULL)
			return;

		date_default_timezone_set("America/Denver");
		$itemPubDate = date("M j  g:ia", strtotime($this->pubDate));
		echo "<div class=\"itemPubDate\">" .  $itemPubDate . "</div>";
	}

	private function ItemTitle()
	{
		echo "<div class=\"itemTitle\">";

		if ($this->itemLink != NULL)
			echo "<a href=\"" . $this->itemLink . "\">";

		if (($this->itemTitle != NULL) && 
		    (strlen($this->itemTitle) > 0))
			echo $this->itemTitle;
		else if ($this->itemDesc != NULL)
			echo $this->itemDesc;

		if ($this->itemLink != NULL)
			echo "</a></div>";
		else
			echo "</div>";
	}

	private function ItemDesc()
	{
		if (($this->itemTitle != NULL) &&
		    (strlen($this->itemTitle) > 0) &&
		    ($this->itemDesc != NULL)) {
			$pItemDesc = addParagraphs($this->itemDesc);
//			$pItemDesc = $this->itemDesc;
			echo "<div class=\"itemDesc\">" .
				$pItemDesc . "</div>";
		}
	}
}

function addParagraphs($text)
{
	// Add paragraph elements
	$lf = chr(10);
	return preg_replace(
		'/\n(.*)\n/Ux',
		$lf.'<p>'.$lf.'$1'.$lf.'</p>'.$lf,
		$text);
}

