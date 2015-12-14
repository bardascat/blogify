<script>
    $(function() {
        $("#tabs").tabs();

        $("input[type=submit]").button();
    });
</script>

<div id="admin_content">

    <table id='main_table' border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>

            <?php $this->load->view('admin/left_menu'); ?>

            <td class='content index'>
                <!-- content -->


                <div id="submit_btn_right">

                </div>
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">Detalii Utilizator</a></li>
                        <li><a href="#tabs-2">Modifica Parola</a></li>
                    </ul>
                    <div id="tabs-1">
                        <form method="post" action="<?php echo base_url() ?>admin/users/edit_user_submit"  enctype="multipart/form-data">
                            <input type="hidden" name="id_user" value="<?php echo $user->getId_user() ?>"/>
                            <table  border='0' width='100%' id='add_table'>
                                <tr>
                                    <td colspan="2" style='padding-bottom: 15px;'>
                                        <input style="float: right; width: 100px;" type="submit" value="Salveaza"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Nume </label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' name='lastname'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Prenume</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' name='firstname'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Rol</label>
                                    </td>
                                    <td class='input' >
                                        <select name='id_role'>
                                            <?php foreach ($roles as $role) { ?>
                                                <option value='<?php echo $role->getId_role() ?>'><?php echo $role->getName() ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Email(*)</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' name='email'/>
                                    </td>
                                </tr>

                                <tr>
                                    <td class='label'>
                                        <label>Telefon</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' name='phone'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Dată creare</label>
                                    </td>
                                    <td class='input' >
                                        <input disabled="" type='text' name='created_date'/>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div id="tabs-2">
                        <form method="post" action="<?php echo base_url() ?>admin/users/changePassword"  enctype="multipart/form-data">
                            <input type="hidden" name="id_user" value="<?php echo $user->getId_user() ?>"/>

                            <table  border='0' width='100%' id='add_table'>
                                <tr>
                                    <td colspan="2" style='padding-bottom: 15px;'>
                                        <input style="float: right; width: 100px;" type="submit" value="Salveaza"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Parola Noua</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' name='new_password'/>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>


                </div>
                </form>
                <!-- end content -->
            </td>
        </tr>
    </table>

</div>