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
        $feed->set_feed_url("http://www.tolo.ro/feed/");
         
        // Run SimplePie.
        $status=$feed->init();

        echo ($status===false);
        
        $feed->handle_content_type(); 

        
        echo '<pre>';
        echo $feed->get_title();
        echo $feed->get_image_url();
       
       $channel = $feed->get_feed_tags('', 'channel');
	    print_r($channel);
	
        
        foreach ($feed->get_items() as $item) {
            print_r($item->get_source());
            echo '<br/>';
        
        }
        
        
        exit('hello');
    }

    public function doSomething() {
        
    }

}
