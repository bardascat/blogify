<?php
/* @var $user BusinessLogic\Models\Entities\User */
/* @var $view View  */
?>

<div class='inner_small'>
    <div class="account messages">

        <?php require_once 'application/views/user/user_menu.php' ?>


        <div class='right_side'>


            <?php if (isset($notification)) echo $this->view->show_message($notification) ?>

            <div class="transaction_list">


                <div class="filter_div">
                    <form id="filterTransactionForm" method="get" action="">
                        <table class="filter_table" width="100%" border="0">
                            <tr>
                                <td width="40%">

                                    <table width="100%" border="0">
                                        <tr>
                                            <td>
                                                <label><?php echo \BusinessLogic\Util\Language::output("tip_tranzactie")?></label>

                                                <select name="transaction_type">
                                                    <option value=""><?php echo \BusinessLogic\Util\Language::output("toate")?></option>
                                                    <option <?php if ($this->input->get("transaction_type") == "1") echo "selected" ?> value="1"><?php echo \BusinessLogic\Util\Language::output("alimentare")?></option>
                                                    <option <?php if ($this->input->get("transaction_type") == "2") echo "selected" ?> value="2"><?php echo \BusinessLogic\Util\Language::output("cheltuieli")?></option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                                <td width="60%">

                                    <table width="100%" border="0">
                                        <tr>
                                            <td>
                                                <label><?php echo \BusinessLogic\Util\Language::output("interval")?>:</label>
                                            </td>
                                            <td style="padding-right: 1em;">
                                                <input class="datePicker" type="text" value="<?php echo $this->input->get("from") ?>" name="from" placeholder="<?php echo \BusinessLogic\Util\Language::output("de_la")?>"/>
                                            </td>
                                            <td>
                                                <input type="text" class="datePicker" value="<?php echo $this->input->get("to") ?>" name="to" placeholder="<?php echo \BusinessLogic\Util\Language::output("pana_la")?>"/>
                                            </td>
                                            <td style="padding-left: 1em;">
                                                <div onclick="$('#filterTransactionForm').submit()" style="width: 3em; padding:0.2em;padding-top: 6px;padding-bottom: 6px;border-radius: 6px;" class="blueBtn">GO</div>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="list">
                    <?php if (count($transactions)) { ?>
                        <table id="example" class="display" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="18%"><?php echo \BusinessLogic\Util\Language::output("data")?></th>
                                    <th width="50%"><?php echo \BusinessLogic\Util\Language::output("detalii")?></th>
                                    <th width="10%"><?php echo \BusinessLogic\Util\Language::output("alimentare")?>/<?php echo \BusinessLogic\Util\Language::output("cheltuieli")?></th>
                                    <th width="24%"><?php echo \BusinessLogic\Util\Language::output("sold_ramas")?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction) { ?>
                                    <tr>
                                        <td><?php echo $transaction->getStamp()->format("d-m-Y") ?></td>
                                        <td  style="text-align: justify; font-size: 1em;"><?php echo $transaction->getDetails() ?></td>
                                        <td  style="text-align: center; font-size: 1.2em;">
                                            <?php
                                            switch ($transaction->getType()) {
                                                case App_constants::$TRANZACTIE_CHELTUIELI: {
                                                        echo "<span style='color:red'>-" . $transaction->getValue() . '</span>';
                                                    }break;
                                                default: {
                                                        echo "<span style='color:green'>+" . $transaction->getValue() . '</span>';
                                                    }break;
                                            }
                                            ?>
                                        </td>
                                        <td style="text-align: center; font-size: 1.2em;"><?php echo $transaction->getCurrent_sold() ?> lei</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>

                    <div class="footer_details">
                        <div class="sold">
                            <?php echo \BusinessLogic\Util\Language::output("sold")?>: <?php echo $user->getSold() ?> RON
                        </div>
                        <div  style="margin-top: 0.5em;" class="extras">
                            
                            <a href="<?php echo base_url('account/extras?from='.$this->input->get("from").'&to='.$this->input->get("to").'')?>"><?php echo \BusinessLogic\Util\Language::output("extras_cont")?></a>
                        </div>
                    </div>

                    <div class="bottom_actions">
                        <a href="<?php echo base_url('account/alimentareCont') ?>">
                            <div class="blueBtn left"><?php echo \BusinessLogic\Util\Language::output("alimenteaza_cont")?></div>
                        </a>

                        <div style="float: right;" onclick="checkReinoiestePachet()" class="blueBtn right"><?php echo \BusinessLogic\Util\Language::output("reinoieste_pachet")?></div>

                    </div>

                </div>
                <div id="clear"></div>
            </div>
            <div id="clear"></div>
        </div>
        <div id="clear"></div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#example').dataTable();
        $(".datePicker").datepicker({dateFormat: 'dd-mm-yy'});
    });
    function checkReinoiestePachet() {
        $.ajax({
            type: "POST",
            url: ' <?php echo base_url('account/checkReinoiestePachet') ?>',
            dataType: 'json',
            success: function(result) {


                var dynamicDialog = $("<div id='MyDialog'> " + result.msg + " </div>");
                if (result.type == "2") {

                    dynamicDialog.dialog({title: "Atentie",
                        modal: true,
                        buttons: [{text: "Da", click: function() {

                                    $(this).dialog("close");
                                    $.ajax({
                                        type: "POST",
                                        url: ' <?php echo base_url('account/reinoirePachet') ?>',
                                        dataType: 'json',
                                        success: function(result) {


                                            var dynamicDialog = $("<div id='MyDialog'> " + result.msg + " </div>");
                                            dynamicDialog.dialog({title: "Atentie",
                                                modal: true,
                                                buttons: [{text: "Ok", click: function() {
                                                            location.reload();
                                                            $(this).dialog("close");
                                                        }}]
                                            });
                                        }});

                                }}, {text: "Nu", click: function() {
                                    $(this).dialog("close");
                                }}]
                    });
                }
                else if (result.type == "1") {

                    dynamicDialog.dialog({title: "Atentie",
                        modal: true,
                        buttons: [{text: "Ok", click: function() {
                                    $(this).dialog("close");
                                }}]
                    });

                } else {
                    alert("Eroare, va rugam reincercati");
                }

            }})
    }

</script>