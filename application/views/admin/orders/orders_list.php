<script type="text/javascript">

    $(document).ready(function() {
        $('.list_buttons').buttonset();
        $('.submitSearchbtn').button();
        $(".datepicker").datepicker({dateFormat: "yy-mm-dd"});
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

                <div class="paginator">
                    <form method="get" action="?" class="paginateForm">
                        Pagina: <input style="width: 20px; text-align: center; padding: 2px; font-size: 15px;" type="text" name="page" value="<?php if (isset($_GET['page'])) echo $_GET['page'] ?>"/>
                        din  <?php echo round(count($orders) / 100) ?>
                    </form>

                    <div class="searchForm">
                        <form method="GET" action="<?php echo base_url() ?>admin/orders/searchOrders">
                            <input type="text" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>" name="search" placeholder="Cauta dupa nume/email client sau cod comanda"/>

                            <select style="margin-left: 12px;" name="id_company">
                                <option value="">Toti Partenerii</option>
                                <?php foreach ($companies as $company) { ?>
                                    <option <?php if ($this->input->get("id_company") == $company->getId_user()) echo "selected"; ?> value="<?php echo $company->getId_user() ?>"><?php echo $company->getCompanyDetails()->getCompany_name() ?></option>
                                <?php } ?>
                            </select>
                            <span style="margin-left: 20px;">Interval:</span>
                            <input value="<?php echo $this->input->get('from') ?>" style="width: 80px;"  type="text" class="datepicker" name="from" placeholder="from"/>
                            <input value="<?php echo $this->input->get('to') ?>" style="width: 80px;" type="text" class="datepicker" name="to" placeholder="to"/>
                            <input  class="submitSearchbtn" type="submit" value="GO" style="width: 50px; height: 27px; cursor: pointer;"/>
                            <input type="button" style="width: 50px; height: 27px; cursor: pointer;"  class="submitSearchbtn" onclick="window.location.href = '<?php echo base_url('admin/orders/orders_list') ?>'" value="Clear" />

                        </form>
                    </div>

                </div>
                <table width="100%" border="0" id="list_table" cellpadding="0" cellspacing="0">
                    <tr>
                        <th width="100" class="cell_left">
                            Cod Comanda
                        </th>
                        <th>
                            Data Comanda
                        </th>
                        <th style="padding-left: 20px;">
                            Cumparator
                        </th>

                        <th>Email</th>
                        <th class="cell_right">

                        </th>

                    </tr>
                    <?php
                    /* @var $order \BusinessLogic\Models\Entities\Order */
                    foreach ($orders as $order) {
                        ?>

                        <tr>
                            <td width="20%"><a href="<?= base_url() ?>admin/orders/edit_order/<?= $order->getId_order() ?>"><?php echo $order->getOrderNumber() ?></a></td>
                            <td width="20%"><?php echo $order->getOrderedOn() ?></td>
                            <td style="padding-left: 20px;" width="15%"><?php echo $order->getUser()->getFirstname() . ' ' . $order->getUser()->getLastname() ?></td>
                            <td><?php echo $order->getUser()->getEmail() ?></td>
                            <td width="20%" class="list_buttons cell_right">
                                <a href="<?= base_url() ?>admin/orders/edit_order/<?= $order->getId_order() ?>">Editeaza</a>
                                <a  href="javascript:triggerDeleteConfirm('.delete_<?= $order->getId_order() ?>',1)">Sterge</a>
                                <a style='display: none;'  class="delete_<?= $order->getId_order() ?>"  href="<?= base_url() ?>admin/orders/delete_order/<?= $order->getId_order() ?>">Sterge</a>
                            </td>
                        </tr>
                    <?php } ?>
                </table

                <!-- end content -->
            </td>
        </tr>
    </table>

</div>