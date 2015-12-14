<?php /* @var $order \BusinessLogic\Models\Entities\Order */ ?>

<div id="admin_content">

    <table id='main_table' border='0' width='100%' cellpadding='0' cellspacing='0'>
        <tr>

            <?php $this->load->view('admin/left_menu'); ?>

            <td class='content index'>
                <div class="orderDetails">
                    <table  id='list_table' width="100%" ellpadding="0" cellspacing="0">
                        <tr>
                            <th width="5%">Cod Comandă</th>
                            <th width="19%">Informatii Client</th>
                            <th width="10%">Dată Comandă</th>

                        </tr>
                        <tr>
                            <td>
                                <?php echo $order->getOrderNumber() ?>
                            </td>
                            <td>
                                <?php
                                $user = $order->getUser();
                                ?>
                                Nume:<?php echo $user->getLastname() . ' ' . $user->getFirstname() ?><br/>
                                Email:<a href="mailto:<?= $user->getEmail() ?>"><?php echo $user->getEmail() ?></a><br/>
                                Telefon:<?php echo $user->getPhone() ?><br/>

                            </td>
                            <td>
                                <?php echo $order->getOrderedOn() ?>
                            </td>


                        </tr>
                    </table>
                </div>

                <form id="updateOrderForm" method="post" action="<?= base_url() ?>admin/orders/editOrderDo" enctype="multipart/form-data">
                    <input type="hidden" name="id_order" value="<?php $order->getId_order() ?>"/>
                    <div class="categoriesInput"></div>
                    <div id="submit_btn_right">
                       <!-- <input onclick="$('#updateOrderForm').submit()"  type="button" value="Salveaza" /> -->
                    </div>
                    <!-- content -->
                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">Produse Cumpărate</a></li>

                        </ul>
                        <div id="tabs-1">
                            <table class='itemsTable'  width='100%' border='0' id='list_table' cellpadding="0" cellspacing="0">
                                <tr>
                                    <th class='cell_left' width='10%'>

                                    </th>
                                    <th width='30%'>
                                        Produs
                                    </th>
                                    <th width='7%'>
                                        Cantitate
                                    </th>
                                    <th width='7%'>
                                        Total
                                    </th>

                                    <th width='5%'>
                                        Partener
                                    </th>

                                    <th class='cell_right' width='20%'></th>
                                </tr>
                                <?php
                                foreach ($order->getItems() as $orderItem) {
                                    $item = $orderItem->getItem();
                                    ?>
                                    <tr id='<?= $orderItem->getId() ?>'>
                                        <td class='image'>
                                            <a target='_blank' href='<?php echo base_url('oferte/' . $item->getSlug()) ?>'>
                                                <img src='<?php echo base_url($item->getMainImage("thumb")) ?>' width='80'/>
                                            </a>
                                        </td>
                                        <td width="25%" class='item'>
                                            <?php echo $item->getName() . '<br/>'; ?>

                                            <a style="color:#00A9FF ;font-size: 11px;" href="<?= base_url() ?>admin/orders/editVouchersPopup/<?= $orderItem->getId() ?>" class="fancybox.iframe lista_vouchere">Vezi vouchere</a>

                                        </td>
                                        <td width="15%" class='quantity'>
                                            <input type='text' disabled name='quantity' value='<?= $orderItem->getQuantity() ?>'/>
                                        </td>
                                        <td><?php echo $orderItem->getTotal() ?> lei</td>

                                        <td width="20%" style="font-size: 10px;">
                                            <a href="<?= base_url() ?>admin/users/edit_company/<?= $item->getCompany()->getId_user() ?>/popup" class="popupPartener fancybox.iframe">
                                                <?php echo $item->getCompany()->getCompanyDetails()->getCompany_name() ?>
                                            </a>
                                        </td>

                                        <td class="list_buttons cell_right">
                                            <!-- <a href="javascript:updateOrderItemQuantity(<?php echo $orderItem->getId() ?>)">Salvează</a> -->
                                            <a href="<?= base_url() ?>admin/orders/deleteOrderItem/<?php echo $orderItem->getId() ?>">Sterge</a>

                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>

                    </div>
                </form>
                <!-- end content -->
            </td>
        </tr>
    </table>
</div>

<form id='updateOrderItemForm' method='post' action='<?= base_url() ?>admin/orders/updateOrderItemQuantity'>
    <input type='hidden' name='quantity'/>
    <input type='hidden' name='id_orderItem'/>
</form>
<style>.fancybox-skin {background: #FFF;}</style>
<script>
    function updateOrderItemQuantity(id_orderItem) {
        $('#updateOrderItemForm input[name="quantity"]').val($('#' + id_orderItem + " input[name='quantity']").val());
        $('#updateOrderItemForm input[name="id_orderItem"]').val(id_orderItem);
        $('#updateOrderItemForm').submit();

    }

    $(function() {
        $(".popupPartener").fancybox({autoResize: false, height: 500, autoSize: false, width: 900, openEffect: 'none', closeEffect: 'none', afterShow: function() {
            }});
        $(".awbDetails").fancybox({autoResize: false, width: 660, height: 400, autoSize: false, openEffect: 'none', closeEffect: 'none', beforeClose: function() {
                location.reload();
            }});
        $(".lista_vouchere").fancybox({autoResize: false, height: 400, autoSize: false, width: 550, openEffect: 'none', closeEffect: 'none', beforeClose: function() {
                window.location = "";
            }});
        $("#tabs").tabs();
        $("input[type=submit]").button();
        $("input[type=button]").button();
        $(".jqueryButton").button();
        $('.list_buttons').buttonset();

    });

</script>