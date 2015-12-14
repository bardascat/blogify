<?php
/* @var $invoice \BusinessLogic\Models\Entities\Invoice */
$company = json_decode($invoice->getComapany_info());
$supplier = json_decode($invoice->getSupplier_info());
$product = json_decode($invoice->getProducts());

?>
<style>
    body {font-size: 14px;  }
    .product_table td{border-top: 0.2px solid #000; border-bottom: 0.2px solid #000;  padding-bottom:4px; padding-top: 4px;}
    .companyTable td{padding-bottom: 10px;}
</style>
<html>
    <body>
        <table width="1000"  cellspacing="0" style="padding-left: 10px">
            <tr>
                <td width="300" style="border-bottom: 0.2px solid #000; text-align: center; vertical-align: top;">
                    <table border="0" width="300" style="text-align: center;">
                        <tr>
                            <td style="padding-bottom: 40px; border-bottom: 0.2px solid #000; padding-top: 20px;">
                                <img width="200" src="<?php echo base_url('assets/images/pk_logo.png'); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 20px; padding-top: 60px; font-family:'Verdana'">
                                <b style="font-size: 19px;">FACTURA FISCALA</b><br/><br/>
                            </td>
                        </tr>
                        <tr>
                            <td  style="padding-bottom: 10px;"> 
                                Serie: <?php echo $invoice->getSeries() ?> <br/><br/>
                            </td>
                        </tr>
                        <tr>
                            <td  style="padding-bottom: 10px;"> 
                                Numar:<?php echo $invoice->getNumber() ?><br/><br/>
                            </td>
                        </tr>
                        <tr>
                            <td  style="padding-bottom: 10px;"> 
                                Data: <?php echo $invoice->getGenerate_date()->format("d-m-Y") ?><br/><br/>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="70%" style="padding-left:80px; padding-top: 30px; padding-right: 30px; ">
                    <table width="100%" class="companyTable">
                        <tr>
                            <td style="border-top:0.2px solid #000; padding-top: 20px;">
                                <table class="infoTable" style="padding-left: 20px;" width="500">
                                    <tr>
                                        <td width="130">   <div class="left_label"><b>Furnizor</b></div></td>
                                        <td ><div class="right_label"><?php echo $supplier->name ?></div></td>
                                    </tr>
                                    <tr>
                                        <td>   <div class="left_label">Reg. com</div></td>
                                        <td><div class="right_label"><?php echo $supplier->reg_com ?></div></td>
                                    </tr>

                                    <tr>
                                        <td>   <div class="left_label">CUI</div></td>
                                        <td><div class="right_label"><?php echo $supplier->cui; ?></div></td>
                                    </tr>
                                    <tr>
                                        <td>   <div class="left_label">Adresa</div></td>
                                        <td><div class="right_label"><?php echo $supplier->adresa ?></div></td>
                                    </tr>
                                    <tr>
                                        <td>   <div class="left_label">Cont</div></td>
                                        <td><div class="right_label"><?php echo $supplier->iban ?></div></td>
                                    </tr>
                                    <tr>
                                        <td>   <div class="left_label">Banca</div></td>
                                        <td><div class="right_label"><?php echo $supplier->banca ?></div></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px; border-bottom:0.2px solid #000;">

                                <table style="padding-left: 20px;">
                                    <tr>
                                        <td width="130">   <div class="left_label"><b>Cumparator</b></div></td>
                                        <td><div class="right_label"></div></td>
                                    </tr>
                                    <tr>
                                        <td width="130">   <div class="left_label">Reg. com</div></td>
                                        <td><div class="right_label"><?php echo $company->name ?></div></td>
                                    </tr>
                                    <tr>
                                        <td>   <div class="left_label">CUI</div></td>
                                        <td><div class="right_label"><?php echo $company->cui ?></div></td>
                                    </tr>
                                    <tr>
                                        <td >   <div class="left_label">Adresa</div></td>
                                        <td><div class="right_label"><?php echo $company->adresa ?></div></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <table class="product_table" cellspacing="0" width="1000" border="0" style="padding-top: 150px; text-align: center">
                        <tr>
                            <th style="padding-bottom: 10px;">Nr.</th>
                            <th  style="padding-bottom: 10px;"width="35%">Denumirea produselor</th>
                            <th style="padding-bottom: 10px;">UM</th>
                            <th style="padding-bottom: 10px;">Cantitate</th>
                            <th width="18%" style="padding-bottom: 10px;">Pret Unitar</th>
                            <th width="12%" style="padding-bottom: 10px;">Total</th>
                            <th style="padding-bottom: 10px;">Cota TVA</th>
                            <th style="padding-bottom: 10px;">Valoare TVA</th>
                        </tr>
                        <?php
                        $step = 1;

                        $step++;
                        ?>
                        <tr>
                            <td style="padding-top: 10px;">
                                <?php echo $step ?>
                            </td>
                            <td style="padding-top: 10px;">
                                <?php echo $product->nume; ?>
                            </td>
                            <td style="padding-top: 10px; padding-left: 20px; padding-right: 20px;">
                                buc
                            </td>
                            <td style="padding-top: 10px;">
                                <?php echo $product->quantity; ?>
                            </td>
                            <td style="padding-top: 10px;">
                                <?php echo $product->price; ?> ron
                            </td>
                            <td style="padding-top: 10px;">
                                <?php echo $product->price * $product->quantity; ?> ron
                            </td>

                            <td style="padding-top: 10px;">
                                <?php echo $invoice->getTva(); ?>
                            </td>

                            <td style="padding-top: 10px;">
                                <?php echo round($product->price - ($product->price / 1.24), 1) ?> ron
                            </td>
                        </tr>

                    </table>

                </td>
            </tr>

            <tr>
                <td colspan="2" style="padding-left: 530px;">
                    <table width="1000" class="bottom_table" style="padding-top: 190px;">
                        <tr>
                            <td style=" padding-left: 55px;">
                                <table cellpadding="0" cellspacing="0" width="230">
                                    <tr>
                                        <td  width="200" style="border: 1px dotted #000; width: 230px; padding-top: 3px; height: 130px; padding-left:10px; text-align: left;">
                                            <table  width="200">
                                                <tr>
                                                    <td style="padding-top: 10px; padding-bottom: 10px;">
                                                        Total fara TVA:  <?php echo $invoice->getTotal() - round($product->price - ($product->price / 1.24), 1) ?> RON
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-top: 10px; padding-bottom: 10px; border-top: 0.2px solid #000; ">
                                                        Total  TVA:  <?php echo round($product->price - ($product->price / 1.24), 1)?>RON
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-top: 10px; padding-bottom: 10px; border-top: 0.2px solid #000;   border-bottom: 0.2px solid #000;font-weight: bold">
                                                        Total de plata: <?php echo $invoice->getTotal() ?> RON
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>