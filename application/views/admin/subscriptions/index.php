
<div id="admin_content">

    <table id='main_table' border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>

            <?php
            $this->load->view('admin/left_menu');
            ?> 

            <td class='content index' style="background-color: #FFF;   border:1px solid #f0f0f0;">

                <!-- content -->
                <div class="inner_content" style="width: 900px;">
                    <table>
                        <tr>
                            <td width="143">
                                Alege Optiune
                            </td>
                            <td>
                                <form method="get" id="selectOptionForm" action="">
                                    <select onchange="$('#selectOptionForm').submit()" name="id_option">
                                        <option value="0"></option>
                                        <?php foreach ($subscriptions as $sub) { ?>
                                            <option <?php if ($this->input->get('id_option') == $sub->getId_option()) echo "selected"; ?> value="<?php echo $sub->getId_option() ?>"><?php echo $sub->getName() ?></option>
                                        <?php } ?>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    </table>

                    <?php if (isset($subscription)) { ?>
                        <div class="edit_sub">
                            <form method="post" action="<?php echo base_url('admin/subscriptions/update_option') ?>">
                                <input type="hidden" name="id_option" value="<?php echo $subscription->getId_option() ?>"/>
                                <table  border='0' width='100%' id='add_table'>
                                    <tr>
                                        <td class="label">
                                            <label>Denumire</label> 
                                        </td>
                                        <td class="input">
                                            <input type="text" name="name"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">
                                            <label>Pret</label> 
                                        </td>
                                        <td class="input">
                                            <input type="text" name="price"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">
                                            <label>Pret Vanzare</label> 
                                        </td>
                                        <td class="input">
                                            <input type="text" name="sale_price"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">
                                            <label>Pot fi cumparate maxim</label> 
                                        </td>
                                        <td class="input">
                                            <input type="text" name="max_bought"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">
                                            <label>Nr. Randuri Promo:</label> 
                                        </td>
                                        <td class="input">
                                            <input type="text" name="available_rows"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">
                                            <label>Descriere</label> 
                                        </td>
                                        <td class="input">
                                            <textarea id='description' name='description'></textarea>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <input style="width: 100px;" type="submit" value="Salveaza"/>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    <?php } ?>

                </div>
            </td>
        </tr>
    </table>

</div>

<script>
                                        $(function() {
                                            load_partner_editor();
                                            $("input[type=submit]").button();
                                        });
</script>