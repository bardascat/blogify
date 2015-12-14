<?php

class categorii extends CI_Controller {

    private $OffersModel = null;

    public function __construct() {
        parent::__construct();
        
        $this->OffersModel = new BusinessLogic\Models\OffersModel();
    }

    public function load_categories() {
        $category_slug = null;


        if ($this->uri->segment(2)) {
            $parent_category_slug = $this->uri->segment(2);

            $parent_category = $this->CategoriesModel->getCategoryBySlug($parent_category_slug);
            if (!$parent_category)
                exit('Page not found');
        } else
            exit('Page not found');

        if ($this->uri->segment(3)) {
            $child_category_slug = $this->uri->segment(3);

            $child_category = $this->CategoriesModel->getCategoryBySlug($child_category_slug);
            if (!$child_category)
                exit('Page not found');
        }

        if (isset($child_category))
            $category = $child_category;
        else
            $category = $parent_category;
        $offers = $this->OffersModel->getOffersByParentCategory($category->getSlug());

        $data = array(
            "category"=>$category,
            "offers" => $offers
        );
        if (!$offers)
            $data['no_data'] = "Momentan nu sunt oferte adaugate";


        $this->load_view('oferte/categorie', $data);
    }

    public function getSubcategory() {
        $id_category = $_GET['id_category'];
        if (!$id_category)
            show_404();
        $subcategories = $this->CategoriesModel->getChilds($id_category, -1, false);
        unset($subcategories[0]);
        if (count($subcategories) < 1) {
            echo json_encode(array("type" => "success", "data" => "<option value='-1'>Fara subcategorii</option>"));
        } else {
            $data = '';

            foreach ($subcategories as $subcategory) {

                $data.='<option ' . ($_GET['selectedSubcategory'] == $subcategory['id_category'] ? 'selected' : false) . ' value="' . $subcategory['id_category'] . '">' . $subcategory['category_name'] . '</option>';
            }
            echo json_encode(array("type" => "success", "data" => $data));
        }
        exit();
    }

}
