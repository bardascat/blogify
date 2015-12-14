
<div class="servicii_outer">
    <div class="contact inner_small simple_page">
        <h1>Contact</h1>

        <div class="page_content">

            

            <div class="left_side">


                <div class="phone">
                    <?php echo App_constants::$WEBSITE_PHONE ?> / 0722 187 188
                </div>

                <div class="email">
                    <?php echo App_constants::$OFFICE_EMAIl ?>
                </div>
                <div class="skype">
                    helpie.ro
                </div>
                <div class="fb" >
                    <a style="color: #a6a5a7" href="<?php echo BusinessLogic\Util\Language::output("facebook_link") ?>">helpie.ro</a>
                </div>
                <div class="map">
                    <?php echo \BusinessLogic\Util\Language::output("adresa")?>
                </div>

            </div>

            <div class="right_side">
                <img src="http://maps.googleapis.com/maps/api/staticmap?center=44.455286,+26.105235&zoom=15&scale=false&size=600x300&maptype=roadmap&sensor=false&format=png&visual_refresh=true&markers=size:mid%7Ccolor:red%7Clabel:%7C44.455286,+26.105235" alt="Google Map of 44.452937, 26.105748">
            </div>



            <div class="echipa">
                <h1 style=""><?php echo \BusinessLogic\Util\Language::output("echipa_helpie") ?></h1>
                <div class="member">
                    <img src="<?php echo base_url("assets/frontend/layout/alex.png") ?>"/>
                </div>
                <div class="member">
                    <img src="<?php echo base_url("assets/frontend/layout/ruxandra.png") ?>"/>
                </div>
                <div class="member">
                    <img src="<?php echo base_url("assets/frontend/layout/laura.png") ?>"/>
                </div>
                <div class="member" style="margin: 0em;">
                    <img src="<?php echo base_url("assets/frontend/layout/iulian.png") ?>"/>
                </div>

            </div>


            <div class="mesaj">

                <h1 style="text-transform: none"><?php echo \BusinessLogic\Util\Language::output("lasa_ne_mesaj") ?></h1>


                <div class="form">
                    <form onsubmit="return send_mail()" method="post" action="<?php echo base_url('contact/submit') ?>">
                        <div class="row">
                            <label><?php echo \BusinessLogic\Util\Language::output("contact_nume") ?></label>
                            <input id="name" type="text" name="name"/>
                        </div>
                        <div class="row">
                            <label>E-mail</label>
                            <input id="email" type="text" name="email"/>
                        </div>
                        <div class="row">
                            <label><?php echo \BusinessLogic\Util\Language::output("mesaj") ?></label>
                            <textarea id="mesaj" type="text" name="mesaj"></textarea>
                        </div>
                        <div onclick="$('.form form').submit()" class="blueBtn"><?php echo \BusinessLogic\Util\Language::output("trimite") ?></div>
                    </form>
                </div>

            </div>

        </div>
        <div id="clear"></div>
    </div>

</div>