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

                <div class="paginator">
                    <form method="get" action="?" class="paginateForm">
                        Pagina: <input style="width: 20px; text-align: center; padding: 2px; font-size: 15px;" type="text" name="page" value="<?php if (isset($_GET['page'])) echo $_GET['page'] ?>"/>
                        din  <?php echo $totalPages ?>
                    </form>


                    <div class="searchForm">
                        <form method="get" action="<?php echo base_url("admin/offer/searchOffers") ?>">
                            <input type="text" value="<?php echo $this->input->get('keywords') ?>" name="keywords" placeholder="Cauta dupa nume oferta"/>
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
                            <input type="button" style="width: 50px; height: 27px; cursor: pointer;"  class="submitSearchbtn" onclick="window.location.href='<?php echo base_url('admin/offer/offers_list')?>'" value="Clear" />
                        </form>
                    </div>

                </div>
                <?php if (count($offers)) { ?>
                    <table width="100%" border="0" id="list_table" cellpadding="0" cellspcing="0">
                        <tr>
                            <th width="100" class="cell_left">
                                Id
                            </th>
                            <th>
                                Nume
                            </th>
                            <th>
                                Partener
                            </th>
                            <th>
                                Pret Redus
                            </th>
                            <th>
                                Autor
                            </th>
                            <th >
                                Adaugat La
                            </th>
                            <th class="cell_right">

                            </th>

                        </tr>
                        <?php
                        foreach ($offers as $offer) {
                            $offerDetails = $offer;
                            if (!$offerDetails) {
                                exit("<b>EROARE: Item-ul " . $offer->getId_item() . ' nu are niciun produs/oferta asociata</b>');
                            }
                            ?>
                            <tr>
                                <td width="5%"><a href="<?= base_url() ?>admin/offer/editOffer/<?= $offer->getId_item() ?>"><?= $offer->getId_item() ?></a></td>
                                <td width="30%"><?= $offer->getName() ?></td>
                                <td width="15%"><?= $offer->getCompany()->getCompanyDetails()->getCompany_name() ?></td>
                                <td width="10%"> <?php echo $offer->getSale_price() ?> ron</td>
                                <td width="15%"> <?php echo $offer->getAuthorName() ?></td>
                                <td wdith="10%"><?= $offer->getCreatedDate() ?></td>

                                <td width="15%" class="list_buttons cell_right">
                                    <a href="<?= base_url() ?>admin/offer/editOffer/<?php echo $offer->getId_item() ?>">Editeaza</a>
                                    <a  href="javascript:triggerDeleteConfirm('.delete_<?php echo $offer->getId_item() ?>',1)">Sterge</a>
                                    <a style='display: none;' class="delete_<?php echo $offer->getId_item() ?>" href="<?= base_url() ?>admin/offer/delete_offer/<?= $offer->getId_item() ?>">Sterge</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table
                <?php } else { ?>
                    <h2>Momentan nu exista nicio oferta.</h2>  
                <?php } ?>

            </td>
        </tr>
    </table>

</div>