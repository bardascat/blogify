<?php

class crawler extends PUBLIC_Controller {

    public function __construct() {

        parent::__construct();
    }

    public function index() {
        include('application/libraries/SimplePieLibrary/autoloader.php');
        
        
        $feed = new SimplePie();
 
        $feed->set_cache_location('application/libraries/SimplePieLibrary/cache');
 
        // Set which feed URL to process.
        $feed->set_feed_url("http://www.callainmotion.com/calla-in-motion?format=RSS");
         
        // Run SimplePie.
        $status=$feed->init();

        $feed->handle_content_type(); 

        
  
       
       $channel = $feed->get_feed_tags('', 'channel');
	  //  print_r($channel);
	
	/**
	 * 
	 * TODO: so far we built a first version of the data model. Now we should try using it by writing some code for the crawler.
	 */
	echo 'doing something';
        
        foreach ($feed->get_items() as $item) {
           
            if($item->get_title()=="New Year New Gear"){
                
              
                
                echo '<pre>';
                print_r($item->get_enclosures());
        
                exit();
            }
            echo '<br/>';
        
        }
        
        
        exit('hello');
    }

    public function doSomething() {
        
    }

}
