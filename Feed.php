<?php
/* 
 *Feed.php is a file containing the Feed class.
 *
 */
class Feed
{
    public $ID = 0;
    public $Title = '';
    public $FeedObject;
    
    public function __construct($id)
    {
    
        $this->ID = (int)$id;
    
        # SQL statement - PREFIX is optional way to distinguish your app
        $sql = "select * from news_feeds where ID=$this->ID";
    
        #IDB::conn() creates a shareable database connection via a singleton class
        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));


        if(mysqli_num_rows($result) > 0)
        {#there are records - present data
	       while($row = mysqli_fetch_assoc($result))
            {# pull data from associative array
	 
            $this->Title = $row['Title'];

	   }
        }

        @mysqli_free_result($result);
    
    
    }#end feeds constructor 

    
    public function getLink(){
        
        echo '
        
        <p>
	  <a href="news_list.php?id=' . $this->ID . '">' . $this->Title . '</a>
	   </p>';
	}
       
    }
