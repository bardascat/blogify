<?php
namespace BusinessLogic\Models;
use Doctrine\ORM\EntityManager;
use NeoMvc\Models\Entities as Entities;

class PagesModel extends AbstractModel {

    function __construct() {
        parent::__construct();
    }

    public function getPages() {
        $qb = $this->em->createQueryBuilder();
        $qb->select("p")
                ->from("Entities:SimplePage", "p")
                ->orderBy("p.id_page", "desc");

        $query = $qb->getQuery();
        //  $query->setQueryCacheDriver(new \Doctrine\Common\Cache\ApcCache());
        //  $query->useQueryCache(true);
        $query->execute();
        return $query->getResult();
    }

    
    public function getPageByPk($id_page) {
        return $this->em->find("Entities:SimplePage", $id_page);
    }

    public function getPageBySlug($slug) {
       
        $pages=$this->em->getRepository("Entities:SimplePage")->findBy(array("slug" => $slug));
        return $pages[0];
    }

    public function updatePage($post) {
        /* @var $page \NeoMvc\Models\Entities\SimplePage */
        $page = $this->em->find("Entities:SimplePage", $post['id_page']);
        $page->postHydrate($post);
        $this->em->persist($page);
        $this->em->flush($page);
        return true;
    }
    
    public function initPages(){
        return false;
        $con=$this->em->getConnection();
        $con->executeQuery("truncate simple_pages");
        
        $page=new \BusinessLogic\Models\Entities\SimplePage();
        $page->setSlug("despre-noi");
        $page->setName("Despre noi");
        $this->em->persist($page);
        
        $page2=new \BusinessLogic\Models\Entities\SimplePage();
        $page2->setSlug("termeni-conditii");
        $page2->setName("Termeni");
        $this->em->persist($page2);
        
        $page3=new \BusinessLogic\Models\Entities\SimplePage();
        $page3->setSlug("parteneri");
        $page3->setName("Parteneri");
        $this->em->persist($page3);
        
          
        $page4=new \BusinessLogic\Models\Entities\SimplePage();
        $page4->setSlug("contact");
        $page4->setName("Contact");
        $this->em->persist($page4);
        
        $this->em->flush();
        
    }

}

?>
