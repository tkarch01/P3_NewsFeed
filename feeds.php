<?php

/**
*  survey_view1.php a view page to show a single survey
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
include 'Feed.php';

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

if(isset($_GET['id']) && (int)$_GET['id'] > 0)
{//good data, process!
    
    $id = (int)$_GET['id'];

}
else{//bad data, you go away now!

    //this is redirection in PHP:
    header('Location:index.php');
}

//$CategoryTitle is selected category
$sql_parent = "select Title from news_categories where ID = $id"; 
//$sql are rows from feeds
$sql = "select * from news_feeds where CategoryID=$id";


//END CONFIG AREA ---------------------------------------------------------- 

//get_header(); #defaults to header_inc.php
?>
<!--<h3 align="center">Categories</h3>-->

<?php
#IDB::conn() creates a shareable database connection via a singleton class
$CategoryTitle = mysqli_query(IDB::conn(),$sql_parent) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
$feedParent = mysqli_fetch_assoc($CategoryTitle);

echo '<h3 align="center">News Feeds for ' . $feedParent['Title'] . '</h3>';

$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

$Feed= array();

if(mysqli_num_rows($result) > 0)
{#there are records - present data
	while($row = mysqli_fetch_assoc($result))
	{# pull data from associative array
     $id = $row['ID'];
      $Feed[] = new Feed($id);
	   
	}
}else{#no records
	echo '<div align="center">Sorry, we have no database records for ' . $feedParent['Title'] . '</div>';
}
@mysqli_free_result($result);

//END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to header_inc.php

foreach ($Feed as $object){
    $object->getLink();
}

echo '<p align ="center"><a href="index.php">BACK</a></p>';

get_footer(); #defaults to footer_inc.php



