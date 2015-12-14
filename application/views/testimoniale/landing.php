
<div class="servicii_outer">
    <div class="testimoniale inner_small simple_page">
        <h1><?php echo \BusinessLogic\Util\Language::output("testimoniale_title")?></h1>

        <div class="left_vid">
            <div class="vid-container">
                <iframe frameborder="0" width="560" height="315" src="http://youtube.com/embed/RlpdzHguLAg?autoplay=0&amp;rel=0&amp;showinfo=0&amp;autohide=0" id="vid_frame"></iframe>
            </div>
            <div id="clear"></div>
        </div>

        <div class="right_vid" style="overflow:hidden">

            <div class="vid_list">



                <div class="vid-item" onclick="document.getElementById('vid_frame').src = 'http://youtube.com/embed/RlpdzHguLAg?autoplay=1&rel=0&showinfo=0&autohide=1'">
                    <div class="thumb">
                        <img src="http://img.youtube.com/vi/RlpdzHguLAg/0.jpg">
                    </div>
                    <div class="desc">P. Alexandru - Cu Helpie, pe Elbrus</div>

                </div>
                
                <div class="vid-item" onclick="document.getElementById('vid_frame').src = '<?php echo \BusinessLogic\Util\Language::output("homepage_testimonial_2_embed")?>'">
                    <div class="thumb">
                        <img src="http://img.youtube.com/vi/NYvrL5QrlGQ/0.jpg">
                    </div>
                    <div class="desc">A.Nicoleta, Despre Helpie</div>

                </div>




            </div>

            <div id="clear"></div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            console.log($('.vid-container iframe').height());
            $('.vid_list').height($('.vid-container iframe').height() - 12);

            $(window).resize(function() {
                $('.vid_list').height($('.vid-container iframe').height() - 10);

            });
        })
    </script>