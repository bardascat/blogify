<?php

namespace BusinessLogic\Models;

class LandingModel extends AbstractModel {

    /**
     * 
     * @param type $slug
     * @return \BusinessLogic\Models\Entities\SimplePage
     * 
     */
    public function getPage($slug) {
        $simplePage = $this->em->getRepository("Entities:SimplePage");
        $page = $simplePage->findOneBy(array("slug" => $slug));
        return $page;
    }

}
?>

