
<div class="servicii_outer">
    <div class="servicii inner_small simple_page">
        <h1> <?php echo \BusinessLogic\Util\Language::output("servicii")?></h1>

        <div class="page_content">
            <div class="text">
                <?php echo \BusinessLogic\Util\Language::output("servicii_desc") ?>
            </div>
            <h2>

                <?php echo \BusinessLogic\Util\Language::output("alege_fericirea") ?>
            </h2>

            <div class="buttons">
                <div class="pasi">
                    <div class="pas1">
                        <img src="<?php echo base_url(\BusinessLogic\Util\Language::output("alege_fericirea_photo_1")); ?>"/>
                    </div>
                    <div class="pas2">
                        <img src="<?php echo base_url(\BusinessLogic\Util\Language::output("alege_fericirea_photo_2")); ?>"/>
                    </div>
                    <div class="pas3">
                        <img src="<?php echo base_url(\BusinessLogic\Util\Language::output("alege_fericirea_photo_3")); ?>"/>
                    </div>
                    <div id="clear"></div>
                </div>
                <div id="clear"></div>
            </div>

            <div class="cum_functioneaza_btn">
                <a href="<?php echo base_url('pachete') ?>"><?php echo \BusinessLogic\Util\Language::output("vezi_pachete_btn")?></a>
            </div>
        </div>
        <div id="clear"></div>
    </div>

</div>