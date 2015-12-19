<?php

use Doctrine\Common\ClassLoader;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class Doctrine {

    /**
     * @var EntityManager $em
     */
    protected $em = null;

    /**
     *
     * @var Doctrine
     */
    private static $instance;

    /**
     * Singleton pattern, returneaza conexiunea
     * @return EntityManger
     */
    public static function getInstance() {

        if (!self::$instance) {
            self::$instance = new Doctrine();
        }
        return self::$instance;
    }

    public function Doctrine() {

        require_once APPPATH . 'libraries/vendor/autoload.php';
        $doctrineClassLoader = new ClassLoader('Doctrine', APPPATH . 'libraries');
        $doctrineClassLoader->register();
        $entitiesClassLoader = new ClassLoader('BusinessLogic', rtrim(APPPATH, "/"));

        $classLoader = new \Doctrine\Common\ClassLoader('DoctrineExtensions', APPPATH . 'libraries');
        $classLoader->register();

        $entitiesClassLoader->register();
        $this->initDB();
    }

    private function initDB() {
        require_once APPPATH . 'config/database.php';


        // Database connection information
        $dbParams = array(
            'driver' => 'pdo_mysql',
            'user' => "bardascat",
            'password' => "",
            'host' => "localhost",
            'dbname' => "c9"
        );


        $path = array(APPPATH . 'BusinessLogic/Models/Entities');


        $config = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($path, true);

        $config->addEntityNamespace("Entities", 'BusinessLogic\Models\Entities');

        // $config->setResultCacheImpl(new \Doctrine\Common\Cache\ApcCache());
        // $config->setQueryCacheImpl(new \Doctrine\Common\Cache\ApcCache());
        // $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ApcCache());
        //$config->setProxyDir("NeoMvc/Proxy");
        //$config->setProxyNamespace("Proxy");
        // $config->setResultCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
        // $config->setQueryCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
        // $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache());

        require_once(APPPATH . "libraries/UVd/DoctrineFunction/DateFormat.php");

        $config->addCustomStringFunction("DATE_FORMAT", "\UVd\DoctrineFunction\DateFormat");
        $config->setAutoGenerateProxyClasses(true);

        $em = EntityManager::create($dbParams, $config);

        try {
          //$this->updateSchema($em);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return $em;
    }

    public function getEm() {
        if (!$this->em)
            $this->em = $this->initDB();
        return $this->em;
    }

    public function updateSchema($em) {


        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $classes = array(
            $em->getClassMetadata("Entities:User"),
            $em->getClassMetadata("Entities:Partener"),
            $em->getClassMetadata("Entities:Transaction"),
            $em->getClassMetadata("Entities:UserNotification"),
            $em->getClassMetadata("Entities:JobLog"),
            $em->getClassMetadata("Entities:Email"),
            $em->getClassMetadata("Entities:Task"),
            $em->getClassMetadata("Entities:TaskNotes"),
            $em->getClassMetadata("Entities:TaskList"),
            $em->getClassMetadata("Entities:TaskReminder"),
            $em->getClassMetadata("Entities:Rol"),
            $em->getClassMetadata("Entities:Permisiune"),
            $em->getClassMetadata("Entities:RolPermisiune"),
            $em->getClassMetadata("Entities:Pachet"),
            $em->getClassMetadata("Entities:Serviciu"),
            $em->getClassMetadata("Entities:CISession"),
            $em->getClassMetadata("Entities:Order"),
            $em->getClassMetadata("Entities:OrderItem"),
            $em->getClassMetadata("Entities:SimplePage"),
            $em->getClassMetadata("Entities:News"),
            $em->getClassMetadata("Entities:NewsletterSubscriber")
        );

        $tool->updateSchema($classes);
        exit("done");
    }

}

?>