
<div class="outer">
    <div class="corporate inner_small simple_page">
        <h1>
            <?php echo \BusinessLogic\Util\Language::output("corporate_title") ?>
        </h1>
       

        <div class="left" >
            <div class="page_content">
                <div class="text" style="margin-bottom: 2em; color: #686868">
                    <?php echo \BusinessLogic\Util\Language::output("corporate_desc") ?>                 

                </div>

                <form method="post" action="<?php echo base_url('corporate/send') ?>">
                    <div class="group">
                        <div class="lastname">
                            <label>  <?php echo \BusinessLogic\Util\Language::output("nume") ?></label>
                            <input type="text" name="lastname"/>
                        </div>
                        <div class="firstname">
                            <label>  <?php echo \BusinessLogic\Util\Language::output("prenume") ?></label>
                            <input type="text" name="firstname"/>
                        </div>
                    </div>

                    <div class="group">
                        <div class="email">
                            <label>E-mail</label>
                            <input type="text" name="email"/>
                        </div>
                        <div class="phone">
                            <label>  <?php echo \BusinessLogic\Util\Language::output("telefon") ?></label>
                            <input type="text" name="phone"/>
                        </div>
                    </div>
                    <div class="companie">
                        <label>  <?php echo \BusinessLogic\Util\Language::output("compania") ?></label>
                        <input type="text" name="company"/>
                    </div>
                    <div id="clear"></div>
                    <a  class="blueBtn"  href="javascript:void(0)" onclick="$('.corporate form').submit()">  <?php echo \BusinessLogic\Util\Language::output("cere_oferta") ?></a>
                </form>
            </div>
        </div>
        <div class="right" style="display: block;">
            <img width="100%" src="<?php echo base_url('assets/frontend/layout/corporate.jpg') ?>"/> 
        </div>

        <div id="clear"></div>
    </div>

</div>