<?php

namespace BusinessLogic\Models;

use Doctrine\ORM\EntityManager;
use BusinessLogic\Models\Entities as Entities;

class TransactionModel extends AbstractModel {

    private $CI;

    function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
    }
    
    public function addIncasare(){
        
    }
    
    public function getUserTransaction(){
        
    }

}

?>
