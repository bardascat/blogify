<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
set_include_path("application/libraries");
require_once(APPPATH . '/libraries/Zend/Acl.php');
require_once(APPPATH . '/libraries/Zend/GenerateAclResources.php');
require_once(APPPATH . '/libraries/Zend/AnnotationReader.php');

class Zacl extends Zend_Acl {

    
    private $CI;
    private $AclModel;
    function __construct() {
        $this->CI = & get_instance();
        $this->AclModel = new BusinessLogic\Models\AclModel();
        //$this->AclModel->setAclResources($this->generateResource());
        $this->setAclRules();
    }

    public function generateResource() {
        $resourceGenerator = new GenerateAclResources();
        $acl = $resourceGenerator->buildAllArrays();
        return $acl;
    }

    private function setAclRules() {
        ob_start();
        $resources = $this->AclModel->getAclResources();
        //adaugam resursele
        foreach ($resources as $resource)
            $this->add(new Zend_Acl_Resource($resource->getName()));

        $roles = $this->AclModel->getRoles();
        foreach ($roles as $role) {
            //adaugam rolurile
            $this->addRole(new Zend_Acl_Role($role->getName()));
            //echo $role->getName() . '<br/>';
            $acl = $this->AclModel->getAclForRole($role->getId_role());
            foreach ($acl as $rule) {
                // echo $rule->getResource()->getName() . ' ' . $rule->getAction() . '<br/>';
                //adaugam regulile
                switch ($rule->getAction()) {
                    case "allow": {
                            $this->allow($role->getName(), $rule->getResource()->getName());
                        }break;
                    default: {
                            $this->deny($role->getName(), $rule->getResource()->getName());
                        }break;
                }
            }
            // echo '--------------end role-----------------<br/>';
        }
    }

    /**
     * Determina daca un role are acces la o resursa
     * @param type $resource
     * @param type $role optional
     * @return boolean
     */
    public function check_acl($resource, $role) {
        if (!$this->has($resource)) {
            return true;
        }
        if (empty($role)) {
            return false;
        }
        return $this->isAllowed($role, $resource);
    }

}
