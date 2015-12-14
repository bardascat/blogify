<?php

namespace BusinessLogic\Models;

class UserModel extends AbstractModel {

    private $CI;

    function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
    }
    public function setMessagesViewed($user) {
        try {
            $this->em->createQuery("update Entities:Email m  set m.viewed=1 where m.toE=:id_user")
                    ->setParameter("id_user", $user->getId_user())
                    ->execute();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getUnseenComments($user) {
        $qb = $this->em->createQueryBuilder();
        $qb->select("e.id_email")
                ->from("Entities:Email", 'e')
                ->join("e.toE", "client")
                ->where('client.id_user=:id_user')
                ->andWhere("e.viewed=0")
                ->setParameter("id_user", $user->getId_user());

        $comments = $qb->getQuery()->getResult();
        if (!$comments)
            return 0;
        else
            return count($comments);
    }

    /**
     * 
     * @param type $email
     * @return \NeoMvc\Models\Entity\User
     */
    public function checkEmail($email) {
        $userRep = $this->em->getRepository("Entities:User");
        $user = $userRep->findBy(array("email" => $email));
        if (isset($user[0]))
            return $user[0];
        else
            return false;
    }

    public function createUser($params) {
        $checkEmail = $this->checkEmail($params['email']);
        if ($checkEmail) {
            throw new \Exception("Adresa email deja folosita", 1);
        }
        $user = new Entities\User();
        $user->postHydrate($params);
        $roleRep = $this->em->getRepository("Entities:AclRole")->findBy(array("name" => $params['role']));
        if (!isset($roleRep[0])) {
            throw new \Exception("Invalid Role", 1);
        }
        $user->setAclRole($roleRep[0]);
        if (!$params['password']) {
            $params['password'] = $this->randString(10);
        }
        $user->setPassword(sha1($params['password']));


        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
            return false;
        }
        $this->sendNotification($user);
        //  $this->subscribeUser($user);
        return $user;
    }

    public function resetPassword($iUser) {
        $user = $this->getUserByPk($iUser);


        if ($user) {
            $sPass = generatePassword(14, 8);
            $user->setPassword($this->CI->auth->encodePsswd($sPass));
            $this->em->persist($user);
            $this->em->flush();
            return $sPass;

            ob_start();
            require_once("application/views/mailMessages/resetpassword.php");
            $body = ob_get_clean();
            $subject = "Parola contului a fost resetată";
            \NeoMail::genericMail($body, $subject, $email);
            $this->em->persist($user);
            $this->em->flush();
            return true;
        } else
            return false;
    }

    private function randString($length, $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789') {
        $str = '';
        $count = strlen($charset);
        while ($length--) {
            $str .= $charset[mt_rand(0, $count - 1)];
        }
        return $str;
    }

    public function sendNotification(Entities\User $user) {
        $email = $user->getEmail();
        ob_start();
        switch ($user->getAclRole()->getName()) {
            case \DLConstants::$PARTNER_ROLE: {
                    require_once("application/views/mailMessages/contnou_partener.php");
                }break;
            default: {
                    require_once("application/views/mailMessages/contnou.php");
                }break;
        }
        $body = ob_get_clean();
        $subject = "Confirmare creare cont " . \DLConstants::$WEBSITE_COMMERCIAL_NAME;
        \NeoMail::genericMail($body, $subject, $email);
    }

    public function updateUser($post) {


        $user = $this->getUserByPk($post['id_user']);


        try {

            $this->em->createQuery("delete Entities:UserNotification u where u.id_user=:id_user")
                    ->setParameter("id_user", $user->getId_user())
                    ->execute();

            foreach ($post['notification'] as $id_notification) {
                $notification = new Entities\UserNotification();
                $notification->setType($id_notification);
                $user->addUserNotification($notification);
            }

            $user->postHydrate($post);
            $this->em->persist($user);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), '1');
        }
        return 1;
    }

    public function updateUserMobile($formdata, $user) {

        $user->setEmail($formdata['email']);
        $user->setLastname($formdata['lastname']);
        $user->setFirstname($formdata['firstname']);
        $user->setCnp($formdata['cnp']);
        $user->setPhone($formdata['phone']);

        try {

            $this->em->createQuery("delete Entities:UserNotification u where u.id_user=:id_user")
                    ->setParameter("id_user", $user->getId_user())
                    ->execute();

            if (isset($formdata['email_check'])) {
                $notification = new Entities\UserNotification();
                $notification->setType(\App_constants::$NOTIFICATION_EMAIL);
                $user->addUserNotification($notification);
            }

            if (isset($formdata['phone_check'])) {
                $notification = new Entities\UserNotification();
                $notification->setType(\App_constants::$NOTIFICATION_PHONE);
                $user->addUserNotification($notification);
            }

            if (isset($formdata['sms_check'])) {
                $notification = new Entities\UserNotification();
                $notification->setType(\App_constants::$NOTIFICATION_SMS);
                $user->addUserNotification($notification);
            }

            if (isset($formdata['cont_helpie_check'])) {
                $notification = new Entities\UserNotification();
                $notification->setType(\App_constants::$CONT_HELPIE);
                $user->addUserNotification($notification);
            }

            $this->em->persist($user);
            $this->em->flush();
        } catch (\Exception $e) {
            return "Adresa e-mail deja folosita";
        }
        return 1;
    }

    public function changePassword($post) {
        $user = $this->getUserByPk($post['id_user']);
        $user->setPassword(sha1($post['new_password']));
        $this->em->persist($user);
        $this->em->flush();
        return true;
    }

    public function updatePassword($post) {
        $user = $this->getUserByPk($post['id_user'], true);
        $user->setPassword(sha1($post['new_password']));
        $this->em->persist($user);
        $this->em->flush();
        return true;
    }

    public function setUserProfileImage($id_user, $profile_image) {
        $user = $this->getUserByPk($id_user, true);
        $user->setProfile_image($profile_image);
        $this->em->persist($user);
        $this->em->flush($user);
        return 1;
    }

    /**
     * Cauta userul dupa email si parola.
     * @param type $email
     * @param type $password
     * @return Entities:User
     */
    public function find_user($email, $password = false) {

        $qb = $this->em->createQueryBuilder();
        $qb->select("u")
                ->from("Entities:User", 'u')
                ->where('u.email=:email or u.username=:email');
        if ($password)
            $qb->andWhere('u.password=:password');


        $qb->setParameter(':email', $email)
                ->setParameter(':password', $password);

        $user = $qb->getQuery()->getResult();

        if (isset($user[0]))
            return $user[0];
        else
            return false;
    }

    /**
     * Cauta user dupa id.
     * @param  id int
     * @return \BusinessLogic\Models\Entities\User
     */
    public function getUserByPk($id, $ORM = true) {

        if (!$ORM)
            $user = $this->em->getConnection()->executeQuery("select users.*,company.company_name from users
                left join company using(id_user)
                where users.id_user=$id")->fetchAll();
        else {
            $user = $this->em->getRepository("Entities:User")->findBy(array("id_user" => $id));
        }
        if (!isset($user[0]))
            return false;
        else
            return $user[0];
    }

    public function deleteUser($id_user) {
        try {
            $dql = $this->em->createQuery("delete from Entities:User u where u.id_user='$id_user'");
            $dql->execute();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function getUsers($page, $limit = 30) {
        try {
            $query = $this->em->createQuery("select users from Entities:User  users join users.AclRole r where r.name!=:role_name order by users.id_user desc")
                    ->setParameter("role_name", \DLConstants::$PARTNER_ROLE)
                    ->setFirstResult(( $page * $limit) - $limit)
                    ->setMaxResults($limit);
            $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
            return $paginator;
        } catch (\Doctrine\ORM\Query\QueryException $e) {
            echo $e->getMessage();
        }
    }

    public function searchUser($keyword) {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select("u")
                ->from("Entities:User", "u")
                ->where("u.nume like :keyword")
                ->orWhere("u.email like :keyword")
                ->orderBy("u.id_user", "desc")
                ->setParameter(":keyword", "%" . $keyword . '%')
                ->getQuery();

        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
        return $paginator;
    }

    public function fbLogin() {

        $config = array(
            'appId' => '359856294153596',
            'secret' => 'aa5766af5d81afa49a0c823548680bde',
            'fileUpload' => true, // Indicates if the CURL based @ syntax for file uploads is enabled.
        );
        $fbLib = APPPATH . '/libraries/Facebook.php';
        require_once($fbLib);

        $facebookLib = new \Facebook($config);
        $user = $facebookLib->getUser();

        // We may or may not have this data based on whether the user is logged in.
        //
        // If we have a $user id here, it means we know the user is logged into
        // Facebook, but we don't know if the access token is valid. An access
        // token is invalid if the user logged out of Facebook.
        $profile = null;
        if ($user) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $profile = $facebookLib->api('/me?fields=id,name,link,email,gender,first_name,last_name,birthday,location');
            } catch (\FacebookApiException $e) {
                $user = null;
            }
        }

        $fb_data = array(
            'me' => $profile,
            'uid' => $user,
            'loginUrl' => $facebookLib->getLoginUrl(
                    array(
                        'scope' => 'email,user_birthday,user_location', // app permissions
                        'redirect_uri' => base_url('account/fblogin?return_fb=true') // URL where you want to redirect your users after a successful login
                    )
            ),
            'logoutUrl' => $facebookLib->getLogoutUrl(),
        );


        return $fb_data;
    }

    public function newsletterSubscribe($email) {
        $newsletterSubscriber = new Entities\NewsletterSubscriber();
        $newsletterSubscriber->setEmail($email);
        try {
            $this->em->persist($newsletterSubscriber);
            $this->em->flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Functie care aduce datele despre useri
     */
    function getGridData($aPost) {
        $aColumnMapping = array();

        $dql = $this->em->getConnection()->createQueryBuilder();
        $dql->select("u.*, rol.*,rol.rol_nume")
                ->from("users", "u")
                ->join("u", "user_rol", "user_rol", "u.id_user=user_rol.id_user")
                ->join("user_rol", "rol", "rol", "user_rol.rol_id=rol.rol_id")
                ->groupBy("u.id_user");

        $this->gridFiltersExt($dql, $this->getGridFilterParams($aPost), $aColumnMapping);

        $result = $dql->execute()->fetchAll();

        $totalCount = $this->getFoundRows();
        $data = array(
            'totalCount' => $totalCount,
            'data' => $result
        );

        return $data;
    }

    function getTransactionsForGrid($aPost) {
        $aColumnMapping = array(
            array("table" => false, "col" => "CONCAT(operator.lastname, ' ',operator.firstname)", "ref" => "operator_lastname"),
            array("table" => "partener", "col" => "name", "ref" => "partener_nume")
        );

        if (!isset($aPost['sort'])) {
            $aPost['sort'] = "id_transaction";
        }

        $dql = $this->em->getConnection()->createQueryBuilder();
        $dql->select("t.*,operator.firstname as operator_firstname,operator.lastname as operator_lastname,partener.name as partener_nume,CONCAT(client.lastname, ' ',client.firstname) as client,client.id_user as id_client")
                ->from("user_transaction", "t")
                ->join("t", "users", "client", "t.id_client=client.id_user")
                ->join("t", "users", "operator", "t.id_operator=operator.id_user")
                ->leftJoin("t", "partener", "partener", "t.id_partener=partener.id_partener")
                ->where("t.id_client=" . $aPost['id_user']);

        $this->gridFiltersExt($dql, $this->getGridFilterParams($aPost), $aColumnMapping);



        $result = $dql->execute()->fetchAll();

        $totalCount = $this->getFoundRows();
        $data = array(
            'totalCount' => $totalCount,
            'data' => $result
        );

        return $data;
    }

    /**
     * Returneaza rolurile unui user
     *
     * @param  $iUserId
     * @return array
     */
    function getUserRol($iUserId) {
        $this->db->select("rol.rol_id,rol_nume");
        $this->db->join("user_rol", "user_rol.user_id=user.user_id");
        $this->db->join("rol", "user_rol.rol_id=rol.rol_id");
        $this->db->where("user.user_id", $iUserId);
        $aResult = $this->db->get("user")->result_array();

        return $aResult;
    }

    /**
     * Adaugare / editare user
     *
     * @param  $aData
     * @return \BusinessLogic\Models\Entities\User
     */
    function editUser($aData) {


        if (!isset($aData['user_id']))
            $iRecord = false;
        else
            $iRecord = ($aData["user_id"] ? $aData["user_id"] : 0);
        if ($iRecord == 0) {
            //adaugare
            $user = new Entities\User();
            $bDuplicate = $this->checkEmail($aData['email']);
            if ($bDuplicate) {
                return "Adresa de email exista deja in sistem.";
            }
            //daca la creare parola nu a fost introdusa atunci parola va fi username-ul
            $user->setPassword(( $aData["newPassword"] != "" ) ? $this->CI->auth->encodePsswd($aData["newPassword"]) : $this->CI->auth->encodePsswd($aData["email"]));
        } else {
            //editare
            $user = $this->getUserByPk($aData['user_id']);
            //daca schimba emailul sa fie valid
            if ($aData['email'] != $user->getEmail()) {
                $bDuplicate = $this->checkEmail($aData['email']);
                if ($bDuplicate) {
                    return "Adresa de email exista deja in sistem.";
                }
            }

            //daca la editare se cere modificarea parolei
            if ($aData["newPassword"])
                $user->setPassword($this->CI->auth->encodePsswd($aData["newPassword"]));
        }

        /*
          $aRol = explode(",", $aData["user_rol"]);

          //modificare roluri
          if (isset($aData["user_rol"]) && ( $aData["user_rol"] ) != "") {
          try {
          $user->removeAllRoles();
          } catch (\Exception $e) {
          echo $e->getMessage();
          }
          if (count($aRol) > 0) {
          foreach ($aRol as $iRol) {
          $rol = $this->em->find("Entities:Rol", $iRol);
          $user->addRol($rol);
          }
          }
          }
         * */

        $user->removeAllRoles();
        $rol = $this->em->find("Entities:Rol", $aData['rol_id']);
        $user->addRol($rol);

        /*
          //sunt necesare pachetele de asociat
          if ($rol->getRol_nume() == "client") {
          $user->removeAllPachete();
          $pachet = $this->em->find("Entities:Pachet", $aData['id_pachet']);
          $user->addPachet($pachet);
          }
         * 
         */


        $user->postHydrate($aData);

        /*
          //daca userul este activ atunci se reseteaza numarul de loginuri
          if ($aData["user_activ"] == 1) {
          $aTemp["user_nr_login"] = 0;
          } */

        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    /**
     * Functie care seteaza theme-ul unui user
     */
    function setStyle($sStyle) {
        $aUser = $this->auth->getUserDetails();
        $this->db->where('user_id', $aUser["user_id"]);
        $this->db->set("template_style", $sStyle);
        $this->db->update('user');
    }

    function saveSecurityAnswer($aData) {
        $aUser = $this->auth->getUserDetails();
        $this->db->where('user_id', $aUser["user_id"]);
        $this->db->set('user_security_answer_1', $this->auth->encodePsswd($aData['security_answer_1']));
        $this->db->set('user_security_answer_2', $this->auth->encodePsswd($aData['security_answer_2']));
        $this->db->update("user");

        return TRUE;
    }

    public function getTransactions($user, $aPost) {


        try {
            $qb = $this->em->createQueryBuilder();
            $qb->select("t")
                    ->from("Entities:Transaction", "t")
                    ->join("t.client", "user")
                    ->where("user.id_user=" . $user->getId_user());

            if (isset($aPost['transaction_type']) && $aPost['transaction_type']) {
                $qb->andWhere("t.type=:type")
                        ->setParameter("type", $aPost['transaction_type']);
            }
            if (isset($aPost['from']) && $aPost['from']) {
                $qb->andWhere("t.stamp>=:from")
                        ->setParameter("from", date("Y-m-d", strtotime($aPost['from'])));
            }
            if (isset($aPost['to']) && $aPost['to']) {
                $qb->andWhere("t.stamp<=:to")
                        ->setParameter("to", date("Y-m-d", strtotime($aPost['to'])));
            }
            $qb->orderBy("t.stamp", "desc");

            $r = $qb->getQuery()->execute();

            return $r;
        } catch (\Exception $e) {
            print_r("Eroare: " . $e->getMessage());
            exit();
        }
    }

    public function reinoirePachet(\BusinessLogic\Models\Entities\User $user) {

        $pachet = $user->getActivePachet();
        $pachetOrderItem = $user->getPachetOrderItem();
        if ($pachet->getIsExpired() || !$pachet->getIsEnabled()) {
            $newDate = date("d-m-Y", strtotime(date("Y-m-d") . " +1 month"));
        } else {

            $newDate = date("d-m-Y", strtotime($pachet->getExpireDate() . " +1 month"));
        }
        //daca pachetul este expirat il activam acum
        $order=$pachetOrderItem->getOrder();
        $order->setPayment_status(\App_constants::$PAYMENT_STATUS_CONFIRMED);
        $order->setOrderStatus(\App_constants::$ORDER_STATUS_CONFIRMED);
        
        $pachetOrderItem->setExpires(new \DateTime($newDate));
        $user->setSold($user->getSold() - $pachet->getPrice());
        //creare tranzactie ce scoate bani din cont
        $transaction = new Entities\Transaction();
        $transaction->setValue($pachet->getPrice());
        $transaction->setType(\App_constants::$TRANZACTIE_CHELTUIELI);
        $transaction->setDetails("Reinoire pachet " . $pachet->getName());
        $transaction->setCurrent_sold($user->getSold());
        $transaction->setOperator($user);
        $transaction->setClient($user);

        $user->addTransaction($transaction);
        $this->em->persist($user);
        $this->em->persist($pachetOrderItem);
        $this->em->persist($order);
        $this->em->persist($transaction);
        $this->em->flush();
    }

}
?>

