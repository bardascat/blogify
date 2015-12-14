<script type="text/javascript">

    $(document).ready(function() {
        $('.list_buttons').buttonset();
    });
</script>
<div id="admin_content">

    <table id='main_table' border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>

            <?php $this->load->view('admin/left_menu'); ?>

            <td class='content index'>
                <!-- content -->
                <div class="paginator">
                    <form method="get" action="?" class="paginateForm">
                        Pagina: <input style="width: 20px; text-align: center; padding: 2px; font-size: 15px;" type="text" name="page" value="<?php if (isset($_GET['page'])) echo $_GET['page'] ?>"/>
                        din  <?php echo round(count($users) / 30) ?>
                    </form>

                    <div class="searchForm">
                        <form method="get" action="<?php echo base_url() ?>admin/users/searchUser">
                            <input type="text" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>" name="search" placeholder="Cauta dupa nume sau email user"/>
                        </form>
                    </div>

                </div>

                <table width="100%" border="0" id="list_table" cellpadding="0" cellspcing="0">
                    <tr>
                        <th width="100" class="cell_left">
                            Id User
                        </th>
                        <th>
                            Nume User
                        </th>
                        <th>
                            email
                        </th>
                        <th>
                            Rol
                        </th>
                        <th >
                            Data Creare
                        </th>
                        <th class="cell_right">

                        </th>

                    </tr>
                    <?php
                    /* @var $user \BusinessLogic\Models\Entities\User */
                    foreach ($users as $user) {?>
                        <tr>
                            <td width="7%"><a href="<?php echo base_url() ?>admin/users/edit_user/<?php echo $user->getId_user() ?>"><?php echo $user->getId_user() ?></a></td>
                            <td width="20%"><?php echo $user->getLastname() . ' ' . $user->getFirstname() ?></td>
                            <td width="20%"><?php echo $user->getEmail() ?></td>
                           
                            <td width="15%"><?php echo $user->getAclRole()->getName() ?></td>
                             <td width="15%"><?php echo $user->getCreated_date() ?></td>

                            <td width="20%" class="list_buttons cell_right">
                                <a href="<?php echo base_url() ?>admin/users/edit_user/<?php echo $user->getId_user() ?>">Vizualizeaza</a>
                                <a href="javascript:triggerDeleteConfirm('.delete_<?= $user->getId_user() ?>',1)">Sterge</a>

                                <a style='display: none' class='delete_<?= $user->getId_user() ?>' href="<?php echo base_url() ?>admin/users/delete_user/<?php echo $user->getId_user() ?>">Sterge</a>
                            </td>
                        </tr>
                    <?php } ?>
                </table

                <!-- end content -->
            </td>
        </tr>
    </table>

</div>