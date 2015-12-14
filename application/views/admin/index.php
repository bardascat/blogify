<div id="admin_content">

    <table id='main_table' border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>

            <? require_once('views/admin/left_menu.php'); ?> 
            
            <td class='content index'>
                <!-- content -->
                <div class='welcome'>
                    Bine ai venit administrator <? echo $this->logged_user['orm']->getNume() . ' '.$this->logged_user['orm']->getPrenume()?>
                </div>
                <!-- end content -->
            </td>
        </tr>
    </table>

</div>