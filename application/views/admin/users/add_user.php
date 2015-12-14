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

                <div>
                    <?php if (isset($notification)) echo $this->view->show_message($notification) ?>
                </div>
                <div id="submit_btn_right">

                </div>
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">Detalii Utilizator</a></li>
                    </ul>
                    <div id="tabs-1">
                        <form method="post" action="<?php echo base_url() ?>admin/users/add_user_submit"  enctype="multipart/form-data">
                            <table  border='0' width='100%' id='add_table'>
                                <tr>
                                    <td colspan="2" style='padding-bottom: 15px;'>
                                        <input style="float: right; width: 100px;" type="submit" value="Adauga"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Nume </label>
                                    </td>
                                    <td class='input' >
                                        <input type='text'value="<?php echo set_value('lastname') ?>" name='lastname'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Prenume</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text'value="<?php echo set_value('firstname') ?>" name='firstname'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Sex</label>
                                    </td>
                                    <td class='input' >
                                        <select name="gender">
                                            <option vlaue="M">M</option>
                                            <option vlaue="F">F</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Rol</label>
                                    </td>
                                    <td class='input' >
                                        <select name='id_role'>
                                            <?php
                                            foreach ($roles as $role) {
                                                if ($role->getName() == 3)
                                                    continue;
                                                ?>
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
                                        <input type='text' value="<?php echo set_value('email') ?>" name='email'/>
                                    </td>
                                </tr>

                                <tr>
                                    <td class='label'>
                                        <label>Telefon</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' value="<?php echo set_value('phone') ?>" name='phone'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Password(*)</label>
                                    </td>
                                    <td class='input' >
                                        <input type='password' name='password'/>
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