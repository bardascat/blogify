<?php
/* @var $user BusinessLogic\Models\Entities\User */
/* @var $view View  */
?>

<div class='inner_small'>
    <div class="account messages">

        <?php require_once 'application/views/user/user_menu.php' ?>


        <div class='right_side'>

            <a id="newMessageBtn" href="<?php echo base_url('account/mesaj_nou') ?>"><?php echo \BusinessLogic\Util\Language::output("mesaj_nou") ?></a>
            <?php if (isset($notification)) echo $this->view->show_message($notification) ?>

            <div class="messages_list">

                <div class="messages_type">
                    <form method="get">
                        
                        <div class="custom-select">
                            <select onchange="$('.messages_type form').submit()" name="message_type">
                                <option <?php if ($message_type == "inbox") echo "selected"; ?> value="inbox"><?php echo \BusinessLogic\Util\Language::output("mesaje_primite") ?></option>
                                <option <?php if ($message_type == "outbox") echo "selected"; ?> value="outbox"><?php echo \BusinessLogic\Util\Language::output("mesaje_trimise") ?></option>
                            </select>
                        </div>
                            
                        
                    </form>
                </div>

                <div class="list" style="width:100%">
                    <?php
                    $start = 1;
                   
                    if (count($userMessages)) {
                        
                        foreach ($userMessages as $message) {
                            ?>
                            <div class="message<?php if ($start % 2 == 0) echo " grey" ?>">
                                <div class="name" style="width: 50%; float: left; font-weight: bold">
                                    <?php
                                    switch ($message_type) {
                                        case "inbox": {
                                                echo "From: " . $message->getFrom()->getFirstname() . " " . $message->getFrom()->getLastname();
                                            }break;
                                        case "outbox": {
                                                echo "To: " . $message->getTo()->getFirstname();
                                            }break;
                                    };
                                    ?>
                                </div>
                                <div style="float: right;" class="message_date"><?php echo $message->getCDate()->format("d-m H:i") ?></div>

                                <div class="msg_content" style="clear: both; width:100%; float: left;">
                                    <div class="left_column" style="width:100%;">
                                        <div class="message_data"> 
                                            <?php echo $message->getContent() ?>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="right_column">
                                        <div class="message_date"><?php echo $message->getCDate()->format("d-m H:i") ?></div>
                                   
                                            <div class="read_more" onclick="">Read more</div>
                                   
                                    </div>
                                    -->
                                </div>
                            </div>
                            <?php
                            $start++;
                        }
                    }else echo "<div style='margin-top:0.5em; font-size:1.4em'>Nu aveti niciun mesaj</div>";
                    ?>


                </div>
                <div id="clear"></div>
            </div>

            <div id="clear"></div>
        </div>


        <div id="clear"></div>
    </div>
</div>