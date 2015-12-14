<script>
    $(function() {
        $("#tabs").tabs();
        load_produs_editor();
        $("input[type=submit]").button();
        $(".delete_photo").button();
    });
</script>

<div id="admin_content">

    <table id='main_table' border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>

            <? require_once('views/admin/left_menu.php'); ?> 

            <td class='content index'>
                <!-- content -->
                
                <form method="post" action="<?= URL ?>admin/history/edit_product_do" enctype="multipart/form-data">
                    <input type="hidden" name="id_history" value="<?=$this->product->getid_history()?>"/>
                    <div id="submit_btn_right">
                        <input name="submit" type="submit" value="Salveaza" />
                    </div>
                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">Descriere</a></li>
                            <li><a href="#tabs-2">Galerie Foto</a></li>
                        </ul>
                        <div id="tabs-1">

                            <table  border='0' width='100%' id='add_table'>
                                <tr>
                                    <td class='label'>
                                        <label>An Eveniment</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' name='year'/>
                                    </td>
                                </tr>

                                 <tr>
                                    <td class='label'>
                                        <label>Tip</label>
                                    </td>
                                    <td class='input' >
                                        <select name="type">
                                            <option value="history">History</option>
                                            <option value="biography">Biography</option>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class='label'>
                                        <label>Descriere</label>
                                    </td>
                                    <td class='input'>
                                        <textarea id='description' name='description'></textarea>
                                    </td>
                                </tr>

                            </table>

                        </div>
                        <div id="tabs-2">
                            <div class='add_images'>
                                <div class='image_group'>
                                    <input type='file' name='image[]'/>

                                </div>
                            </div>
                            <div class='new_image' onclick="new_image()">Poza Noua</div>
                            
                            <table id="pictures_table" border="0" width="100%">
                                <? $photos=$this->product->getImages();
                                foreach($photos as $photo ){
                                ?>
                                <tr id="<?=$photo->getId_image()?>">
                                    <td width="400">
                                        <img width="400" src="<?=URL.$photo->getImage()?>"/>
                                    </td>
                                    <td style="vertical-align: top">
                                       <input id="princ_<?=$photo->getId_image()?>" type="radio" <?if($photo->getPrimary()) echo "checked";?> name="primary_image" value="<?=$photo->getId_image()?>"/>  <label for="princ_<?=$photo->getId_image()?>">Principala</label> 
                                        <a class="delete_photo" href="javascript:delete_history_image(<?=$photo->getId_image()?>)">Sterge</a>
                                    </td>
                                </tr>
                                <? } ?>
                            </table>
                            
                        </div>

                    </div>
                </form>
                <!-- end content -->
            </td>
        </tr>
    </table>

</div>