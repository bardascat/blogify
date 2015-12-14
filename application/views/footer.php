
<div class="subfooter_outer">
    <div class="subfooter inner">
        <div class="casuta contact">
            <div class="img">
                <div class="title">
                    <a href="<?php echo base_url("contact") ?>">
                        Contact
                    </a>
                </div>
                <div class="info">
                    <div class="row">
                        <div class="label"><?php echo \BusinessLogic\Util\Language::output("telefon") ?>:</div>
                        <div class="value"><?php echo App_constants::$WEBSITE_PHONE ?> /0722 187 188 </div>
                    </div>
                    <div class="row">
                        <div class="label">E-mail:</div>
                        <div class="value"><?php echo App_constants::$OFFICE_EMAIl ?></div>
                    </div>
                    <div class="row">
                        <div class="label">Skype:</div>
                        <div class="value">helpie.ro</div>
                    </div>
                    <div class="row">
                        <div class="label">Facebook:</div>
                        <div class="value"> <a href="<?php echo BusinessLogic\Util\Language::output("facebook_link") ?>" style="color: #5d5f60">https://www.facebook.com/Helpie.ro</a></div>
                    </div>
                    <div class="row">
                        <div class="label"></div>
                        <div class="value"><?php echo \BusinessLogic\Util\Language::output("adresa")?></div>
                    </div>
                </div>
                <img src="<?php echo base_url('assets/frontend/layout/subfooter_contact.png') ?>"/>
            </div>

        </div>
        <div class="casuta echipa">
            <div class="img">
                <div class="title">
                    <?php echo \BusinessLogic\Util\Language::output("echipa_helpie") ?>
                    <div class="slogan">
                        <?php echo \BusinessLogic\Util\Language::output("echipa_slogan") ?>
                    </div>
                </div>

                <img src="<?php echo base_url('assets/frontend/layout/subfooter_echipa.png') ?>"/>

            </div>
        </div>
        <div class="casuta blog" style="margin-right: 0em;">
            <div class="img">
                <div class="title blog">
                    <a target="_blank" style="color:#5d5f60" href="<?php echo \BusinessLogic\Util\Language::output("blog_link")?>">Blog</a>
                </div>
                <div class="info"style="text-align: center; padding-right: 1em;">
                    <?php echo \BusinessLogic\Util\Language::output("blog_desc") ?>
                </div>
                <img src="<?php echo base_url('assets/frontend/layout/subfooter_blog.png') ?>"/>
            </div>

        </div>
        <div id="clear"></div>
    </div>
</div>
<div class="footer_outer">
    <div class="footer inner">
        <div class="left">
            <div class="follow">
                <span>Follow us:</span> 
                <div class="soccial">
                    <a target="_blank" href="<?php echo BusinessLogic\Util\Language::output("twitter_link") ?>">
                        <img src="<?php echo base_url('assets/frontend/layout/twitter.png') ?>"/>
                    </a>
                    <a target="_blank" href="<?php echo BusinessLogic\Util\Language::output("facebook_link") ?>">
                        <img src="<?php echo base_url('assets/frontend/layout/facebook.png') ?>"/>
                    </a>
                    <a target="_blank" href="<?php echo BusinessLogic\Util\Language::output("google_link") ?>">
                        <img src="<?php echo base_url('assets/frontend/layout/google_plus.png') ?>"/>
                    </a>
                    <a target="_blank" href="<?php echo BusinessLogic\Util\Language::output("pinterest_link") ?>">
                        <img src="<?php echo base_url('assets/frontend/layout/pinterest_logo.png') ?>"/>
                    </a>
                    <a target="_blank" href="<?php echo BusinessLogic\Util\Language::output("linked_link") ?>">
                        <img src="<?php echo base_url('assets/frontend/layout/linked.png') ?>"/>
                    </a>
                    <a target="_blank" href="<?php echo BusinessLogic\Util\Language::output("youtube_link") ?>">
                        <img class="youtube" src="<?php echo base_url('assets/frontend/layout/youtube.png') ?>"/>
                    </a>
                    <div id="clear"></div>

                </div>
                <div id="clear"></div>

            </div>

            <div class="download no-desktop">

                <div class="google">
                    <a target="_blank" href="#">
                        <img src="<?php echo base_url('assets/frontend/layout/google.png') ?>"/>
                    </a>
                </div>
                <div class="ios">
                    <a target="_blank" href="#">
                        <img src="<?php echo base_url('assets/frontend/layout/ios.png') ?>"/>
                    </a>
                </div>
            </div>

            <div class="nav">
                <ul class="left_ul">
                    <li>
                        <a href="<?php echo base_url('contact') ?>">Contact</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('pachete') ?>">
                            <?php echo ucfirst(strtolower(\BusinessLogic\Util\Language::output("cum_functioneaza_footer"))) ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('contact') ?>"><?php echo \BusinessLogic\Util\Language::output("echipa_helpie_buton") ?></a>
                    </li>
                </ul>
                <ul class="right_ul">
                    <li>
                        <a target="_blank" href="<?php echo \BusinessLogic\Util\Language::output("blog_link")?>">Blog</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('termeni') ?>"><?php echo \BusinessLogic\Util\Language::output("termeni_conditii_buton") ?></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('servicii') ?>"><?php echo \BusinessLogic\Util\Language::output("servicii") ?></a>
                    </li>
                </ul>
            </div>

            <div id="clear"></div>
        </div>
        <div class="right">
            <div class="download no-mobile">
                <span>Download our app</span>
                <div id="clear"></div>
                <div class="google">
                    <a target="_blank" href="">
                        <img src="<?php echo base_url('assets/frontend/layout/google.png') ?>"/>
                    </a>
                </div>
                <div class="ios">
                    <a target="_blank" href="">
                        <img src="<?php echo base_url('assets/frontend/layout/ios.png') ?>"/>
                    </a>
                </div>
            </div>

            <div class="image_app no-mobile">
                <img src="<?php echo base_url('assets/frontend/layout/phone.png') ?>"/>
            </div>
        </div>
        <div id="clear"></div>
    </div>
</div>


</div>
</div>
</body>
</html>





