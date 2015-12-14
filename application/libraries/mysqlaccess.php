<?
/* Library_name class */
// working draft
class mysqlaccess {

    function mysqlaccess()
    {
        //$this->CI =& get_instance();
        
        $this->aError = array(
            'error' => TRUE,
            'type' => 'access',
            'description' => 'Acces tabela refuzat'
        );
    }
    
    function _getTableAllowed($sTable){
        return false;
    }
    
    function create($sTable){
        
    }
    
    function read($sTable){
        
    }
    
    function update($sTable){
        
    }
    
    function delete($sTable){
        
    }
    
    
    
}
    /* End of code */