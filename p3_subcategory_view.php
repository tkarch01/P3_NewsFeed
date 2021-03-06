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


$sql = "select * from wn16_subcategories where CategoryID=$id";


//END CONFIG AREA ---------------------------------------------------------- 

//get_header(); #defaults to header_inc.php
?>
<!--<h3 align="center">Categories</h3>-->

<?php
#IDB::conn() creates a shareable database connection via a singleton class
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

$subCategoryObjects= array();

if(mysqli_num_rows($result) > 0)
{#there are records - present data
	while($row = mysqli_fetch_assoc($result))
	{# pull data from associative array
     $id = $row['SubcategoryID'];
      $subCategoryObjects[] = new Subcategory($id);
	   
	}
}else{#no records
	echo '<div align="center">Sorry, there are no records that match this query</div>';
}
@mysqli_free_result($result);







//END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to header_inc.php
?>
<h3 align="center">SubCategories</h3>

<?php

//$mySubcategory = new Subcategory($id);
//dumpDie($mySubcategory);  //dump die is var_dump and die function Bill built for us
foreach ($subCategoryObjects as $object){
    
    $object->getLink();
}

echo '<p><a href="index.php">BACK</a></p>';

get_footer(); #defaults to footer_inc.php


class Subcategory
{

    public $SubcategoryID = 0;
    
    public $Name = '';
    
    public $Description = '';
    
    public $FeedObject;
    
    public function __construct($id)
    {
    
        $this->SubcategoryID = (int)$id;
    
        # SQL statement - PREFIX is optional way to distinguish your app
        $sql = "select * from wn16_subcategories where SubcategoryID=$this->SubcategoryID";
    
        #IDB::conn() creates a shareable database connection via a singleton class
        $result = mysqli_query(IDB::conn(),$sql) or                                                 die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));


        if(mysqli_num_rows($result) > 0)
        {#there are records - present data
	       while($row = mysqli_fetch_assoc($result))
            {# pull data from associative array
	 
            $this->Name = $row['Name'];
            $this->Description = $row['Description'];

	   }
        }

        

        @mysqli_free_result($result);
    
    
    }#end Subcategory constructor 

    
    public function getLink(){
        
        echo '
        
        <p>
	  <a href="p3_feed-view.php?id=' . $this->SubcategoryID . '">' . $this->Name . '</a><br />
	   Description: <b>' . $this->Description . '</b><br />
	   </p>';
	}
        
        
        
        
        
        
        
        
        
        
    }

