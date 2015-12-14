        <form  method="post" action="<?php echo base_url('account/register_submit') ?>">
            <input type="hidden" name="role" value="<?php echo DLConstants::$USER_ROLE ?>"/>
            <table>
                <tr>
                    <td colspan="2">
                        <?php if (isset($notification)) echo $this->view->show_message($notification) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Nume:</label>
                    </td>
                    <td>
                        <input type="text" name="lastname" value="<?php echo set_value('lastname') ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Telefon:</label>
                    </td>
                    <td>
                        <input type="text" name="phone" value="<?php echo set_value('phone') ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Email:</label>
                    </td>
                    <td>
                        <input type="text" name="email" value="<?php echo set_value('email') ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Adresa:</label>
                    </td>
                    <td>
                        <input type="text" name="address" value="<?php echo set_value('address') ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Oras:</label>
                    </td>
                    <td>
                        <select style="width: 207px;" name="city">
                            <?php foreach ($cities as $city) { ?>
                                <option value="<?php echo $city->getDistrict() ?>"><?php echo $city->getDistrict() ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Varsta:</label>
                    </td>
                    <td>
                        <select style="width: 207px;" name="age">
                            <option value="18-25">18-25</option>
                            <option value="25-30">25-30</option>
                            <option value="30-40">30-40</option>
                            <option value=">40">>40</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Sex:</label>
                    </td>
                    <td>
                        <select style="width: 207px;" name="gender">
                            <option value="M">Masculin</option>
                            <option value="F">Feminin</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 25px;">
                        <label>Parola:</label>
                    </td>
                    <td style="padding-top: 25px;">
                        <input type="password" name="password"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <input type="checkbox" name="agreement" value="<?php echo set_value('agreement') ?>"/>
                                </td>
                                <td style="padding-left: 10px;">
                                    Sunt de acord cu <a style="color: #2d8bff" href="<?php echo base_url('termeni-conditii') ?>">Termenii si conditiile</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input id="greenButton" type="submit" value="Creeaza cont"/>
                    </td>
                </tr>
            </table>
        </form>