<script type="text/javascript">

    $(document).ready(function() {
        $('.list_buttons').buttonset();
    });
</script>
<style>
    #list_table .expires td{
        background-color: #ffa8a8
    }

</style>
<div id="admin_content">

    <table id='main_table' border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>

            <?php $this->load->view('admin/left_menu'); ?>

            <td class='content index'>
                <!-- content -->
                <div>
                    <?php echo $this->session->flashdata('form_message'); ?>
                </div>
                <table width="100%" border="0" id="list_table" cellpadding="0" cellspcing="0">
                    <tr>
                        <th width="100" class="cell_left">
                            Id
                        </th>
                        <th>
                            Nume Companie
                        </th>
                        <th>
                            email
                        </th>
                        <th>
                            Data Creare
                        </th>
                        <th>
                            Valabilitate
                        </th>
                        <th class="cell_right">

                        </th>

                    </tr>
                    <?php
                    /* @var $company BusinessLogic\Models\Entities\User */
                    /* @var $companyDetails BusinessLogic\Models\Entities\Company */
                    foreach ($companies as $company) {
                        $companyDetails = $company->getCompanyDetails();
                        ?>

                        <tr class="<?php if ($companyDetails->getAvailable_to()) {
                        if (date("Y-m-d", strtotime(date("Y-m-d") . ' +10 days')) >= $companyDetails->getAvailable_to()->format("Y-m-d")) echo 'expires';
                    } ?>">
                            <td width="5%"><a href="<?= base_url(); ?>admin/users/edit_company/<?=$company->getId_user()?>"><?=$company->getId_user()?></a></td>
                            <td width="20%"><?=$companyDetails->getCompany_name()?></td>
                            <td width="20%"><?=$company->getEmail()?></td>
                            <td width="20%"><?=$company->getCreated_date()?></td>
                            <td width="20%">
    <?php echo ($companyDetails->getAvailable_from() ? "<b>" . $companyDetails->getAvailable_from()->format("d-m-Y") . '</b>-<b>' . $companyDetails->getAvailable_to()->format("d-m-Y") . '</b>' : "inactiv") ?>
                            </td>

                            <td width="20%" class="list_buttons cell_right">
                                <a href="<?= base_url(); ?>admin/users/edit_company/<?=$company->getId_user()?>">Editeaza</a>
                                <a href="javascript:triggerDeleteConfirm('.delete_<?=$company->getId_user()?>',1)">Sterge</a>

                                <a style='display: none' class='delete_<?=$company->getId_user()?>' href="<?= base_url(); ?>admin/users/delete_user/<?=$company->getId_user()?>">Sterge</a>
                            </td>
                        </tr>
<?php } ?>
                </table

                <!-- end content -->
            </td>
        </tr>
    </table>

</div>