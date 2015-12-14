<script  type="text/javascript" src="<?= URL ?>scripts/jquery.1.10.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= URL ?>css/admin.css"/>
<script type="text/javascript" src="<?= URL ?>scripts/jquery_ui/ui-1-10.js"></script>
<link rel="stylesheet" type="text/css" href="<?= URL ?>scripts/jquery_ui/ui-1-10.css"/>

<script type="text/javascript">
$(document).ready(function(){
  $('.submit_login').button();
})
</script>

<table id="login_table"  border="0">

    <tr>
        <td style="text-align: center; padding-bottom: 20px;">
            <img class="logo" src="<?= URL ?>images/admin/logo.png"/>
        </td>
    <tr>

    <tr>
        <td>
            <form  method="post" class="login_form" action="<?php echo URL . 'admin/login/login' ?>">
                <table border="0" cellpadding="0" cellspacing="0">

                    <tr>
                        <td>

                            <label>Username</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="username"/>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label>Password</label>
                        </td>
                    <tr>
                        <td>
                            <input type="password" name="password"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" class="submit_login" name="login" value="Login"/>
                        </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>


</table>

</div>




