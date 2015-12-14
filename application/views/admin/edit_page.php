<script>
    $(function() {
        $("#tabs").tabs();
        load_produs_editor("90%","300");
        $("input[type=submit]").button();
        $("input[type=button]").button();

    });
</script>

<div id="admin_content">

    <table id='main_table' border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>

            <? require_once('views/admin/left_menu.php'); ?> 

            <td class='content index'>
                <!-- content -->

                <form id="addProductForm" method="post" action="<?= URL ?>admin/pages/updatePageSubmit  " enctype="multipart/form-data">
                    <input type="hidden" name="id_page" value="<?= $this->page->getId_page() ?>"/>
                    <div class="categoriesInput"></div>
                    <div id="submit_btn_right">
                        <input onclick="return addProduct()"  type="button" value="Salveaza" />
                    </div>
                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">Editeaza Pagina</a></li>

                        </ul>
                        <div id="tabs-1">

                            <table  border='0' width='100%' id='add_table'>
                                <tr>
                                    <td class='label'>
                                        <label>Nume</label>
                                    </td>
                                    <td class='input' >
                                        <input id="name" type='text' name='name'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Descriere</label>
                                    </td>
                                    <td class='input'>
                                        <textarea id='description' name='content'></textarea>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    

                    </div>
                </form>
                <!-- end content -->
            </td>
        </tr>
    </table>


</div>
