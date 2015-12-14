<style>
    table td,th{
        line-height: 40px;
        border:1px solid #ccc;
      
        padding: 5px;
    }
</style>
<?php
if (!$from)
    $from = $data[0]->getStamp()->format("d-m-Y");
?>
<div style="padding-top: 20px;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="90%">
        <tr>
            <td style="border:0px;">
                Extras de cont <b>Helpie</b>.<br/>
                Interval: <?php echo $from . " - " . $to ?> <br/>
                Client: <?php echo $user->getFirstname() . " " . $user->getLastname() ?> <br/>
                Sold curent: <b><?php echo $user->getSold(); ?></b> lei
            </td>
            <td  style="text-align: right; border:0px; vertical-align: top; font-size: 10px;">
                Data generare: <?php echo date("d-m-Y H:i:s") ?>
            </td>
        </tr>

        <tr>

            <td style="border:0px; padding-top: 30px;" colspan="2">

                <table  align="center" border="0" cellpadding="0" cellspacing="0" width="100%">

                    <tr>
                        <th width="10%">Data</th>
                        <th width="60%">Detalii</th>
                        <th width="10%">Alimentare/Cheltuieli</th>
                        <th width="20%">Sold Ramas</th>
                    </tr>

                    <tbody>
                        <?php foreach ($data as $transaction) { ?>
                            <tr>
                                <td><?php echo $transaction->getStamp()->format("d-m-Y") ?></td>
                                <td  style="text-align: justify; font-size: 1em;"><?php echo $transaction->getDetails() ?></td>
                                <td  style="text-align: center; font-size: 1em;">
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
                                    lei
                                </td>
                                <td style="text-align: center; font-size: 1em;"><?php echo $transaction->getCurrent_sold() ?> lei</td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>

            </td>

        </tr>
    </table>
</div>