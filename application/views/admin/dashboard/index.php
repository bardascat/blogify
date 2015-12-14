
<div id="admin_content">

    <table id='main_table' border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>

            <?php 
                $this->load->view('admin/left_menu');
            ?> 
            
            <td class='content index'>
                <!-- content -->
                <div class='welcome'>
                    Bine ai venit administrator <?=$this->view->getUser()['lastname'].' '.$this->view->getUser()['firstname']?>
                </div>
                <!-- end content -->
            </td>
        </tr>
    </table>

</div>