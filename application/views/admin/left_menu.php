<td width='160' class='menu'>
    <!-- menu -->

    <table border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>
            <th>Oferte</th>
        </tr>
        <tr>
            <td>
                <a href='<?= base_url() ?>admin/categories/categories_list/offer'>Categorii Oferte</a>
            </td>
        </tr>
        <tr>
            <td>
                <a href='<?= base_url() ?>admin/offer/offers_list'>Listă Oferte</a>
            </td>
        </tr>
        <tr>
            <td>
                <a href='<?= base_url() ?>admin/offer/add_offer'>Adauga Oferta</a>
            </td>
        </tr>

    </table>

    <table border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>
            <th>Comenzi Vouchere</th>
        </tr>
        <tr>
            <td>
                <a href='<?= base_url() ?>admin/orders/orders_list'>Listă Comenzi</a>
            </td>
        </tr>  
    </table>
    <table border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>
            <th>Abonamente</th>
        </tr>
        <tr>
            <td>
                <a href='<?= base_url() ?>admin/subscriptions'>Listă Optiuni</a>
            </td>
        </tr>
        <tr>
            <td>
                <a href='<?= base_url() ?>admin/subscriptions/orders'>Comenzi Optiuni</a>
            </td>
        </tr>  
    </table>
    <table border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>
            <th>Useri</th>
        </tr>
        <tr>
            <td>
                <a href='<?= base_url() ?>admin/users/users_list'>Lista Useri</a>
            </td>
        </tr>
        <tr>
            <td>
                <a href='<?= base_url() ?>admin/users/add_user'>Adauga Utilizator</a>
            </td>
        </tr>
        <tr>
            <td>
                <a href='<?= base_url() ?>admin/users/company_list'>Listă Parteneri</a>
            </td>
        </tr>
        <tr>
            <td>
                <a href='<?= base_url() ?>admin/users/add_company'>Adauga Partener</a>
            </td>
        </tr>

    </table>

   
    <table border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>
            <th>Pagini</th>
        </tr>
        <?php
        $pages = $this->view->getPages();
        if ($pages)
            foreach ($pages as $page) {
                ?>

                <tr>
                    <td>
                        <a href='<?= base_url() ?>admin/pages/updatePage/<?php echo $page->getId_page() ?>'><?php echo $page->getName() ?></a>
                    </td>
                </tr>

    <?php } ?>
    </table>
    

    <table border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>
            <th>Administrative</th>
        </tr>
        <tr>
            <td>
                <a href='<?= base_url() ?>admin/index/logout'>Logout</a>
            </td>
        </tr>
        <tr>
            <td>
                <a href='<?= base_url() ?>admin/acl_settings'>Permisiuni</a>
            </td>
        </tr>


    </table>

    <!-- end menu -->

</td>