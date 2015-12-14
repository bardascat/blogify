<script>
    $(function() {
        $("#tabs").tabs();
        load_partner_editor();
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
                <form method="post" action="<?= base_url() ?>admin/users/editCompanySubmit"  enctype="multipart/form-data">
                    <input type="hidden" name="id_user" value="<?php echo $user->getId_user(); ?>"/>
                    <div id="submit_btn_right">
                        <input name="submit" type="submit" value="Salveaza" />
                    </div>
                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">Detalii Partener</a></li>
                            <li><a href="#tabs-2">Detalii Companie</a></li>
                        </ul>
                        <div id="tabs-1">

                            <table  border='0' width='100%' id='add_table'>
                                <tr>
                                    <td class='label'>
                                        <label>Status</label>
                                    </td>
                                    <td class='input' >
                                        <select name="status">
                                            <option value="<?php echo DLConstants::$PARTNER_ACTIVE ?>">Activ</option>
                                            <option value="<?php echo DLConstants::$PARTNER_PENDING ?>">Pending</option>
                                            <option value="<?php echo DLConstants::$PARTNER_SUSPENDED ?>">Suspendat</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Adresa Feed</label>
                                    </td>
                                    <td class='input' >
                                        <input title="Introduceti feed-ul partenerului" type='text' value="<?php echo set_value('feed_url') ?>" name='feed_url'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Nume pers. contact</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' value="<?php echo set_value('nume') ?>" name='lastname'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Prenume pers. contact</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' value="<?php echo set_value('prenume') ?>" name='firstname'/>
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
                                        <label>Email(*)</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' value="<?php echo set_value('email') ?>" name='email'/>
                                    </td>
                                </tr>
                                <!--
                                <tr>
                                    <td class='label'>
                                        <label>Username(*)</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' value="<?php echo set_value('username') ?>" name='username'/>
                                    </td>
                                </tr>
                                -->
                            </table>

                        </div>
                        <div id="tabs-2">
                            <table  border='0' width='100%' id='add_table'>
                                <tr>
                                    <td class='label'>
                                        <label>Logo Companie</label>
                                    </td>
                                    <td class='input' >
                                        <input type='file' name='image[]'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Nume Companie(*)</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' value="<?php echo set_value('company_name') ?>" name='company_name'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Nume Comercial(*)</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' value="<?php echo set_value('commercial_name') ?>" name='commercial_name'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Website</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' value="<?php echo set_value('website') ?>" name='website'/>
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
                                        <label>CIF</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' value="<?php echo set_value('cif') ?>" name='cif'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Registrul Comertului</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' value="<?php echo set_value('regcom') ?>" name='regCom'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Oras</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' value="<?php echo set_value('city') ?>" name='city'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Adresa</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' value="<?php echo set_value('address') ?>" name='address'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>IBAN</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' value="<?php echo set_value('iban') ?>" name='iban'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Bank</label>
                                    </td>
                                    <td class='input' >
                                        <input type='text' value="<?php echo set_value('bank') ?>" name='bank'/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>
                                        <label>Descriere</label>
                                    </td>
                                    <td class='input' >
                                        <textarea id="description" name="description"><?php echo set_value('description') ?></textarea>
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