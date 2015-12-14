<?php

class GenerateAclResources {

    private $arrModules = array();
    private $arrControllers = array();
    private $arrActions = array();
    private $arrIgnore = array('.', '..', '.svn');
    private $acl = array();

    public function buildAllArrays() {
        $this->buildModulesArray();
        $this->buildControllerArrays();
        $this->buildActionArrays();
        return $this->acl;
    }

    public function buildModulesArray() {

        $dstApplicationModules = opendir(APPPATH . '/controllers');

        while (($dstFile = readdir($dstApplicationModules)) !== false) {

            if (!in_array($dstFile, $this->arrIgnore)) {

                if (is_dir(APPPATH . '/controllers/' . $dstFile)) {
                    $this->arrModules[] = $dstFile;
                }
            }
        }
        //front end controllers
        $this->arrModules[] = '';
        closedir($dstApplicationModules);
    }

    public function buildControllerArrays() {

        foreach ($this->arrModules as $strModuleName) {
            $datControllerFolder = opendir(APPPATH . '/controllers/' . $strModuleName);

            while (($dstFile = readdir($datControllerFolder) ) !== false) {

                if (!in_array($dstFile, $this->arrIgnore)) {

                    if (preg_match('/.php/', $dstFile)) {
                        $this->arrControllers[$strModuleName][] = $dstFile;
                    }
                }
            }
            closedir($datControllerFolder);
        }
    }

    public function buildActionArrays() {
        if (count($this->arrControllers) > 0) {
            
            $this->acl['admin']='Modul Administrare';
            foreach ($this->arrControllers as $strModule => $arrController) {

                foreach ($arrController as $strController) {


                    $fileName = APPPATH . 'controllers/' . $strModule . '/' . $strController;

                    require_once($fileName);

                    if (file_exists($fileName)) {
                        $strClassName = explode(".php", $fileName);
                        $class = basename($strClassName[0]);

                        $reflector = new ReflectionClass($class);
                        $methods = array_filter($reflector->getMethods(ReflectionMethod::IS_PUBLIC), function($prop) use($reflector) {
                            return $prop->getDeclaringClass()->getName() == $reflector->getName();
                        });
                        foreach ($methods as $method) {
                            $annotationReader = new AnnotationReader($class, $method->name);
                            $annotations = $annotationReader->getParameter("AclResource");
                            if ($annotations) {
                                if ($strModule)
                                    $this->acl[$strModule . '/' . $class . '/' . $method->name] = $annotations;
                                else
                                    $this->acl[$class . '/' . $method->name] = $annotations;
                            }
                        }
                    } else {
                        
                    }
                }
            }
        }
        //modules
        
       
        return $this->acl;
    }

}
