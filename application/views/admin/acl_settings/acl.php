<script>
    $(function() {
        $("#tabs").tabs();
        $("#tabs_add").tabs();
        $("input[type=submit]").button();
        $(".update_category_trigger").fancybox({
            maxHeight: 500,
            height: 500,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none'
        });

    });
</script>
<style>
    .acl_ul{
        list-style-type: none;
        margin-top:10px;
    }
    .acl_ul li{margin-bottom: 5px;}
</style>
<div id="admin_content">

    <table id='main_table' border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>

            <?php $this->load->view('admin/left_menu'); ?>

            <td class='content index' style="background-color: #FFF;   border:1px solid #f0f0f0;">

                <!-- content -->
                <div class="inner_content" style="width: 80%">
                    <table border="0" id='add_table' wdith="100%">
                        <tr>
                            <td cass="label" style="width: 170px;">
                                Seteaza permisiun pentru
                            </td>
                            <td>
                                <form method="get" id="role_form" action="<?php echo base_url('admin/acl_settings/load_acl') ?>">
                                    <select name="role" onchange="$('#role_form').submit()">
                                        <option value="">Alege rol</option>
                                        <?php foreach ($roles as $role) { ?>
                                            <option <?php if (isset($selected_role) && $selected_role == $role->getId_role()) echo "selected"; ?> value="<?php echo $role->getId_role() ?>"><?php echo $role->getName() ?></option>
                                        <?php } ?>
                                    </select>
                                </form>

                            </td>
                        </tr>
                    </table>

                    <?php if (isset($aclResources)) { ?>
                        <form method="post" style="margin-top: 10px;" action="<?php echo base_url('admin/acl_settings/set_acl') ?>">
                            <table>
                                <tr>
                                    <td onclick="$('input:checkbox').attr('checked','checked')"><a href="#">Check All</a></td>
                                    <td style="padding-left: 20px;" onclick="$('input:checkbox').removeAttr('checked')"><a href="#">Uncheck All</a></td>
                                </tr>
                            </table>
                            <input type="hidden" name="id_role" value="<?php echo $selected_role?>"/>
                            <ul class="acl_ul">
                                <?php foreach ($aclResources as $aclResource) { ?>
                                    <li>
                                        <table>
                                            <tr>
                                                <td>
                                                    <input id="<?php echo $aclResource->getId_resource()?>" <?php if (isset($aclResource->checked)) echo 'checked'; ?> type="checkbox" value="<?php echo $aclResource->getId_resource()?>" name="acl_rule[]"/>
                                                </td>
                                                <td>
                                                    <label for="<?php echo $aclResource->getId_resource()?>"><?php echo $aclResource->getAlias() ?> - <span style="font-size: 11px; color: #ababab"><?php echo $aclResource->getName() ?></span></label>
                                                </td>
                                            </tr>
                                        </table>

                                    </li>
                                <?php } ?>
                            </ul>
                             <div style="padding:20px; padding-left: 0px;">
                                <input  type="submit" value="Salveaza" />
                            </div>
                        </form>
                    <?php } ?>

                </div>
                <!-- end content -->
            </td>
        </tr>
    </table>

</div>