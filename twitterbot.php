<?php
// twitteroauth setup
require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

require_once('secretFile');

// simplepie setup
require_once('php/autoloader.php');

// array of bot comments
$comment = array("Was not expecting that","Not sure what to make of it","Found this today", "From the news vault", "another thing happened", "just saw this", "in the news recently");

// create SimplePie object and get random feed item
$feed = new SimplePie();
$feed->set_feed_url('http://news.google.com/?output=atom');
$feed->init();
$feed->handle_content_type();
$number = rand(1,7);
$item = $feed->get_item($number);

// pull down title and review first. if blocker found, don't add comment

$newsTitle = $item->get_title();
$block = array("dead","killed","murder","rape");
$flag = 0;

// convert news to lowercase for comparison
$newsTitleLower = strtolower($newsTitle);

foreach($block as $blocker){
 if ( strpos($newsTitleLower,$blocker) ) {
   $flag = 1;
   //no comment
 }
}

// Add link to source content, not Google link
$link = $item->get_link();
$linkArray = explode('url=',$link);
$offLink = array_pop($linkArray);

if ($flag == 0) { $newsTitle = $comment[--$number] . "... " . $newsTitle; }
$newsTitle = $newsTitle . " " . $offLink;

// Separate credentials from code
$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $ACCESS_TOKEN, $ACCESS_TOKEN_SECRET);


// test connection to account
//$content = $connection->get("account/verify_credentials");

// post news to Twitter account
$statuses = $connection->post("statuses/update", ["status" => $newsTitle]);

// check output of response in human readable format
//print_r($statuses);
//print_r($content);

?>