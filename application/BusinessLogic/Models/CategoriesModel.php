<?php

/**
 * @author Bardas Catalin
 * date: 05 feb 2014
 */

namespace BusinessLogic\Models;

class CategoriesModel extends AbstractModel {

    /**
     * Cauta category dupa id.
     * @param  id int
     * @return Entities\Category
     */
    public function getCategoryByPk($id) {
        $cat = $this->em->find("Entities:Category", $id);

        if (!$cat)
            return false;
        else
            return $cat;
    }

    /**
     * Cauta categoria dupa slug
     * @param  id int
     * @return Entities/Category

     */
    public function getCategoryBySlug($slug) {
        $rep = $this->em->getRepository("Entities:Category");
        $cat = $rep->findBy(array("slug" => $slug));

        if (!$cat)
            return false;
        else
            return $cat[0];
    }

    /**
     * Verificam daca o categorie are subcategorii
     * @param type $id_category
     * @return boolean
     */
    public function hasChilds($id_category) {
        $result = $this->em->createQuery("select 1 from Entities:Category c where c.id_parent=:id_parent")
                ->setParameter("id_parent", $id_category)
                ->getResult();
        if (empty($result))
            return false;
        else
            return true;
    }

    public function categoryExists($slug, $item_type = "offer") {
        $dql = $this->em->createQuery("select 1 from Entities:Category c where c.slug=:slug and c.item_type=:item_type");
        $dql->setParameter(':slug', $slug);
        $dql->setParameter(':item_type', $item_type);
        $res = $dql->getResult();

        return !empty($res);
    }

    public function addCategory($post) {

        $id_parent = $post['id_parent'];
        $parent = $this->em->find("Entities:Category", $id_parent);
        $category = new Entities\Category();

        $category->setName($post['category_name']);
        if ($id_parent)
            $category->setId_parent($id_parent);

        $id = $this->getNextId("categories", "id_category");

        $category->setSlug(\BusinessLogic\Util\NeoUtil::makeSlugs($post['category_name'] . '-' . $id));

        if (isset($post['thumb']))
            $category->setThumb($post['thumb'][0]['thumb']);

        $category->setParent($parent);
        $this->em->persist($category);
        $this->em->flush($category);

        return true;
    }

    public function deleteCategory($id_category) {
        $this->em->createQuery("delete from Entities:Category c where c.id_category='$id_category'")->execute();
        return true;
    }

    /**
     * 
     * @param type $id_category
     * @return Entities\Category
     */
    public function get_ajax_category_data($id_category) {
        $cat = $this->em->find("Entities:Category", $id_category);
        return $cat;
    }

    public function updateCategory($post) {
        try {
            $category = $this->em->find("Entities:Category", $post['id_category']);
            $category->setName($post['category_name']);
            $category->setSlug(\BusinessLogic\Util\NeoUtil::makeSlugs($post['category_name'] . '-' . $post['id_category']));

            if (isset($post['thumb']))
                $category->setThumb($post['thumb'][0]['thumb']);

            $this->em->persist($category);
            $this->em->flush();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return true;
    }

    /**
     * Intorcea lista copiilor nodului id_parent
     * @param integer $id_parent
     * @param integer $max_depth (adancimea maxima in arbore, -1 pentru adancime maxima)
     * @return Array Entities\Category
     */
    public function getChilds($id_parent, $max_depth = -1, $orm = true) {

        if ($orm) {
            $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
            $rsm->addEntityResult("Entities:Category", "c");
            $rsm->addFieldResult("c", "id_category", "id_category");
            $rsm->addFieldResult("c", "category_name", "name");
            $rsm->addFieldResult("c", "is_aggregate", "aggregate");
            $rsm->addFieldResult("c", "layout", "layout");
            $rsm->addFieldResult("c", "id_parent", "id_parent");
            $rsm->addFieldResult("c", "category_slug", "slug");
            $rsm->addScalarResult("depth", "depth");
            $rsm->addFieldResult("c", "thumb", "thumb");
            $rsm->addFieldResult("c", "cover", "cover");

            $query = $this->em->createNativeQuery("call category_hierarchy(:id_parent,:max_depth)", $rsm);
            $query->setParameter(":id_parent", $id_parent);
            $query->setParameter(":max_depth", $max_depth);

            $categories = $query->getResult();
        } else {
            $query = $this->em->getConnection()->prepare("call category_hierarchy(:id_parent,:max_depth)");
            $query->bindParam(":id_parent", $id_parent);
            $query->bindParam(":max_depth", $max_depth);
            $query->execute();
            $categories = $query->fetchAll();
        }

        return $categories;
    }

    /**
     * Intoarce frunzele unui arbore de categorii
     * @param type $id_parent
     */
    public function getLeafCategories($id_parent) {
        $leafs = $this->em->getConnection()->executeQuery("call category_hierarchy($id_parent,-1)")->fetchAll();
        print_r($leafs);
        exit();
    }

    /**
     * Intoarce lista parintilor in ordine de jos in sus.
     * IMPORTANT: Procedura intoarce pe ultima pozitie si categoria data ca parametru
     * @return \NeoMvc\Models\Entities\Category
     */
    public function getParents($id_category) {
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addEntityResult("Entities:Category", "c");
        $rsm->addFieldResult("c", "id_category", "id_category");
        $rsm->addFieldResult("c", "category_name", "name");
        $rsm->addFieldResult("c", "id_parent", "id_parent");
        $rsm->addFieldResult("c", "slug", "slug");
        $rsm->addScalarResult("v_depth", "depth");
        $rsm->addFieldResult("c", "thumb", "thumb");
        $rsm->addFieldResult("c", "cover", "cover");
        $query = $this->em->createNativeQuery("call get_root_categories(:id_category)", $rsm);
        $query->setParameter(":id_category", $id_category);
        $query->setResultCacheDriver(new \Doctrine\Common\Cache\ApcCache());
        $query->useResultCache(true, 3600);

        $categories = $query->getResult();

        return $categories;
    }

    /**
     * Intoarce array cu categoriile principale
     * @param string  $item_type (product sau offer)
     * @return \BusinessLogic\Models\Entities\Category
     */
    public function getRootCategories($child = false, $orm = false) {
        $conn = $this->em->getConnection();

        if (!$orm) {
            //$stm = $conn->executeCacheQuery("select * from categories where item_type='" . $item_type . "' and id_parent is null order by position asc", array(), array(), new \Doctrine\DBAL\Cache\QueryCacheProfile(0, "root_cats", new \Doctrine\Common\Cache\ApcCache()));
            $stm = $conn->executeQuery("select * from categories where  id_parent is null order by position asc");
            $categories = $stm->fetchAll();
        } else {
            $catRep = $this->em->getRepository("Entities:Category");
            $categories = $catRep->findBy(array("id_parent" => NULL), array("position" => "asc"));
        }
        $full_categories = array();


        //adaugam si copii daca e necesar
        if ($child) {
            foreach ($categories as $category) {
                $cat = array();
                $cat['parent'] = $category;
                if (is_object($category))
                    $cat['childs'] = $this->getChilds($category->getId_category(), 1, $orm);
                else
                    $cat['childs'] = $this->getChilds($category['id_category'], 1, $orm);
                unset($cat['childs'][0]);
                $full_categories[] = $cat;
            }

            return $full_categories;
        }


        return $categories;
    }

    /**
     * Genereaza lista categoriilor pentru administrarea ofertei
     * @param string $item_type Offer
     * @param integer id_item -> id-ul ofertei pentru care se doreste bifarea checkboxului in cazul unui update
     * 
     */
    public function createCheckboxList($item_type, $id_item = false, $id_category = false) {

        $pdoObject = $this->em->getConnection();
        $stm = $pdoObject->prepare("select c.*,(select 1 from categories where id_parent=c.id_category limit 1) as has_childs from categories c where c.item_type='offer' order by c.name");
        
        $stm->bindValue(":item_type", $item_type);
        $data = $stm->execute();

        $data = $stm->fetchAll();
        if (count($data) < 1)
            return false;
        foreach ($data as $row) {

            $this->menu_array[$row['id_category']] = array('name' => ucfirst($row['name']), 'slug' => $row['slug'], 'parent' => $row['id_parent'],'has_childs'=>$row['has_childs']);
        }
        //daca avem produs ca parametrul trebuie sa setam niste categorii checked
        $cRep = $this->em->getRepository("Entities:ItemCategories");
        if ($id_item) {
            $itemCategories = $cRep->findBy(array("id_item" => $id_item));
            $this->itemCategories = $itemCategories;
        } elseif ($id_category) {
            $this->checkedCategory = $id_category;
        }


        //generate menu starting with parent categories (that have a 0 parent)	
        ob_start();
        $this->generateCheckboxList(0);
        $menu = ob_get_clean();

        return $menu;
    }

    private function generateCheckboxList($parent) {

        $has_childs = false;
        //this prevents printing 'ul' if we don't have subcategories for this category
        //use global array variable instead of a local variable to lower stack memory requierment


        foreach ($this->menu_array as $key => $value) {

            if ($key == 0) {
                //main parent
                $main_parent_name = $value['name'];
            }

            if ($value['parent'] == $parent) {

                //if this is the first child print '<ul>'                       

                if ($has_childs === false) {

                    //don't print '<ul>' multiple times                             

                    $has_childs = true;
                    if ($parent == 0)
                        echo ' <ul id="browser" class="filetree">';
                    else
                        echo "\n<ul> \n";
                }
                $checked = "";

                if (isset($this->itemCategories))
                    foreach ($this->itemCategories as $itemCategory) {
                        if ($itemCategory->getId_category() == $key)
                            $checked = "checked";
                    }
                if (isset($this->checkedCategory)) {
                    if ($key == $this->checkedCategory)
                        $checked = "checked";
                }
                // class="'. ? "folder":"file").'
                echo '<li> <span class="'.(!$value['has_childs'] ? "no_childs" : false).'">';
                if(!$value['has_childs'])
                    echo '<span  class="input"><input category_name="'.$value['name'].'" id="'.$value['slug'].'" ' . $checked . '  type="checkbox" class="checkbox"  name="categories[]" value="' . $key . '"></span>';
                echo '<label for="'.$value['slug'].'" class="name">' . $value["name"] . '</label></span>';

                $this->generateCheckboxList($key);

                //call function again to generate nested list for subcategories belonging to this category

                echo "</li>\n";
            }
        }

        if ($has_childs === true)
            echo "\n</ul> \n\n";
    }

    /**
     * Genereaza lista html  a categorilor pentru administrare
     * @param string $item_type Offer
     * 
     */
    public function createAdminList($item_type = "offer") {

        $pdoObject = $this->em->getConnection();
        $stm = $pdoObject->prepare("select * from categories where item_type=:item_type order by name ");
        $stm->bindValue(":item_type", $item_type);
        $data = $stm->execute();

        $data = $stm->fetchAll();

        if (count($data) < 1)
            return false;
        foreach ($data as $row) {

            $this->menu_array[$row['id_category']] = array('name' => ucfirst($row['name']), 'nr_items' => $row['nr_items'], 'slug' => $row['slug'], 'parent' => $row['id_parent']);
        }

        //generate menu starting with parent categories (that have a 0 parent)	

        ob_start();
        $this->generateAdminList(0);
        $menu = ob_get_clean();

        return $menu;
    }

    private function generateAdminList($parent) {

        $has_childs = false;
        //this prevents printing 'ul' if we don't have subcategories for this category
        //use global array variable instead of a local variable to lower stack memory requierment


        foreach ($this->menu_array as $key => $value) {

            if ($key == 0) {
                //main parent
                $main_parent_name = $value['name'];
            }

            if ($value['parent'] == $parent) {

                //if this is the first child print '<ul>'                       

                if ($has_childs === false) {

                    //don't print '<ul>' multiple times                             

                    $has_childs = true;
                    if ($parent == 0)
                        echo ' <ul>';
                    else
                        echo "\n<ul> \n";
                }

                echo "<li><div class='item'>
                    <div class='name'>ID=" . $key . ", {$value['name']} ({$value['nr_items']})</div>
                    <div class='add' onclick='add_category(" . $key . ")'></div>
                    <div class='edit' onclick='update_category(" . $key . ")' ></div>
                        
                    <div  class='remove' onclick='javascript:triggerDeleteConfirm(\".remove_" . $key . "\")'></div>
                        
                    <div style='display:none' class='remove_" . $key . "' onclick='remove_category(" . $key . ")'></div>
                    </div>";


                $this->generateAdminList($key);

                //call function again to generate nested list for subcategories belonging to this category

                echo "</li>\n";
            }
        }

        if ($has_childs === true)
            echo "\n</ul> \n\n";
    }

    public function setNrItemsCategories() {
        $conn = $this->em->getConnection();

        //setam numarul de produse in frunze
        $stm = "update categories c
                set c.nr_items=(select count(*) from item_categories where id_category=c.id_category)
            ";
        $stm = $conn->prepare($stm);
        $stm->execute();

        //facem update  mai sus in arborele de categorii
        $categories = $conn->executeQuery("select id_category from categories")->fetchAll();

        foreach ($categories as $category) {
            $id_category = $category['id_category'];
            $frunze = $conn->executeQuery("call category_hierarchy($id_category,-1)")->fetchAll();
            $total_items = 0;
            if (count($frunze) > 0)
                foreach ($frunze as $frunze) {
                    $total_items+=$frunze['nr_items'];
                }
            $conn->executeQuery("update categories set nr_items=$total_items where id_category=$id_category");
            // echo "Am setat categoria ".$id_category.' nr:'.$total_items.'<br/><br/>';
            $total_items = 0;
        }
    }

}

?>
