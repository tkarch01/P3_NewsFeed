<?php
/**
*  news_list.php a list page to show a News List
*
*  based on demo_shared.php
*
 * demo_idb.php is both a test page for your IDB shared mysqli connection, and a starting point for 
 * building DB applications using IDB connections
 *
 * @package nmCommon
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.09 2011/05/09
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see config_inc.php  
 * @see header_inc.php
 * @see footer_inc.php 
 * @todo none
 */
# '../' works for a sub-folder.  use './' for the root
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
include 'NewsFeed.php';
include 'Feed.php'; #class

$config->titleTag = smartTitle(); #Fills <title> tag. If left empty will fallback to $config->titleTag in config_inc.php
$config->metaDescription = smartTitle() . ' - ' . $config->metaDescription; 
/*
$config->metaDescription = 'Web Database ITC281 class website.'; #Fills <meta> tags.
$config->metaKeywords = 'SCCC,Seattle Central,ITC281,database,mysql,php';
$config->metaRobots = 'no index, no follow';
$config->loadhead = ''; #load page specific JS
$config->banner = ''; #goes inside header
$config->copyright = ''; #goes inside footer
$config->sidebar1 = ''; #goes inside left side of page
$config->sidebar2 = ''; #goes inside right side of page
$config->nav1["page.php"] = "New Page!"; #add a new page to end of nav1 (viewable this page only)!!
$config->nav1 = array("page.php"=>"New Page!") + $config->nav1; #add a new page to beginning of nav1 (viewable this page only)!!
*/
//END CONFIG AREA ---------------------------------------------------------- 

/*
 *Check to see if the $_GET is set and if it is of the right data type
 */
if(isset($_GET['id']) && (int)$_GET['id'] > 0)
{
    //everything is in order
    $id = (int)$_GET['id'];
}
else{
    //not in order - redirect to index.php
    header('Location:index.php');
}

//everything is in order - continue here from previous conditional
get_header(); #defaults to header_inc.php

echo '
    <h3 align="center">News Feed</h3>
    ';

//create instance of Feed object
$myFeed = new Feed($id);

       $request = $myFeed->URL;
//$request = "https://news.google.com/news?cf=all&hl=en&pz=1&ned=us&topic=tc&output=rss";
//$request = "http://news.google.com/news?cf=all&hl=en&pz=1&ned=us&q=Albert+Einstein&output=rss";
  $response = file_get_contents($request);
  $xml = simplexml_load_string($response);
 //print '<h1>' . $xml->channel->title . '</h1>';

$newsFeedObjects = array();

foreach($xml->channel->item as $story)
  {
    echo '<a href="' . $story->link . '">' . $story->title . '</a><br />'; 
    echo '<p>' . $story->description . '</p><br /><br />';
  }

/*foreach($xml->channel->item as $story)
  {
    $newsFeedObjects[] = new NewsFeed($story);
}

foreach($newsFeedObjects as $feed){
    
    $feed->getFeed();
}*/


//dumpDie($mySurvey);  //dump die is var_dump and die function Bill built for us

echo '<p><a href="index.php">BACK</a></p>';

get_footer(); #defaults to footer_inc.php

