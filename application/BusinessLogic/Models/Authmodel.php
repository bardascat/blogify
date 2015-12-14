<?php

namespace BusinessLogic\Models;

class Authmodel extends AbstractModel {

    function __construct() {
        parent::__construct();
    }

    /**
     * @return Array array cu codurile rolurilor asociate user-ului specificat
     * @param type $iUserId
     */
    function getRolesByUserId($iUserId) {
        try {
            $r = $this->em->createQuery("select rol,u from Entities:User u join u.roluri rol where u.id_user=:id_user")
                    ->setParameter(":id_user", $iUserId)
                    ->getArrayResult();
        } catch (\Exception $e) {
            echo $e->getMessage();
            
        }
        

        if (!$r)
            return false;
        else{
            $result=array();
            foreach($r[0]['roluri'] as $rol){
                $result[]=$rol['rol_nume'];
            }
            return $result;
        }
            
    }

    //Intoarce array cu permisiunile user-ului
    function getPermsByUserId($iUserId) {

        $aResult = array();
        //Extrage roluri user
        $aRoles = $this->getRolesByUserId($iUserId);
        //Extrage permisiuni din rolurile asociate
        foreach ($aRoles as $aRol) {

            $aResult = array_merge($aResult, $this->getPermsByRoleName($aRol));
        }

        return $aResult;
    }

    //Intoarce array cu permisiunile rolului
    function getPermsByRoleName($iRoleId) {

        try {
            $r = $this->em->createQueryBuilder()
                            ->select("p.perm_cod,rolp.rp_valoare")
                            ->from("Entities:RolPermisiune", "rolp")
                            ->join("rolp.permisiune", "p")
                            ->join("rolp.rol", "rol")
                            ->where("p.perm_activ=1")
                            ->andWhere("rolp.rp_valoare=1")
                            ->andWhere("rol.rol_nume=:param")
                            ->setParameter(":param", $iRoleId)
                            ->getQuery()->getArrayResult();
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }

        if (!$r)
            return array();

        $aResult = array();
        foreach ($r as $row) {
            $aResult[$row['perm_cod']] = $row['rp_valoare'];
        }

        return $aResult;
    }

    //Intoarce numele permisiunii cu cheia precizata (daca exista) sau string gol
    function getPermName($iPermId) {
        
        
        $rep=$this->em->getRepository("Entities:Permisiune");
        $perm=$rep->findOneBy(array("perm_cod"=>$iPermId));
        if($perm)
            return $perm->getPerm_nume();
        return '';
    }

    //Adauga inregistrare noua in tabela log_acces
    function insertLog($iUserId, $sCodOperatie, $sObsOperatie, $sIp, $sBrowser, $sUri, $aContext) {
        return;
        $sSql = 'INSERT INTO log_acces
				(id_user, log_operatie, log_operatie_obs, log_acces_ip,
				log_acces_browser, log_acces_data, log_acces_uri, log_acces_post)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?)
				';
        $bResult = $this->db->query($sSql, array(
            $iUserId,
            $sCodOperatie,
            $sObsOperatie,
            $sIp,
            $sBrowser,
            date('Y-m-d H:i:s'),
            $sUri,
            $aContext
        ));

        return $bResult;
    }

    //Modifica data ultimei activitati a user-ului dat
    function updateUserLastActivity($iUserId) {
        return;
        $sSql = 'UPDATE user SET user_activitate = ? WHERE id_user= ? LIMIT 1';
        $bResult = $this->db->query($sSql, array(
            date('Y-m-d H:i:s'),
            $iUserId
        ));

        return $bResult;
    }

    //Verifica daca perechea username/parola exista in tabela user
    //Intoarce id_user la success, FALSE la eroare
    function getUserId($email, $sPsswd, $bActiv = FALSE) {

        try {
            $dql = $this->em->createQueryBuilder();
            $r = $dql->select("u")
                    ->from("Entities:User", "u")
                    ->where("u.email=:email")
                    ->andWhere("u.password=:password")
                    ->setParameter(":email", $email)
                    ->setParameter(":password", $sPsswd)
                    ->getQuery()
                    ->execute();

            if (!$r)
                return false;
            else
                return $r[0]->getId_user();

            $sActiv = "";
            if ($bActiv) {
                //$sActiv = " AND user_activ = 1 ";
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }


        return FALSE;
    }

    //Intoarce sesiunile utilizatorului $iUserId
    function getSessionsByUserId($iUserId) {
        $rep = $this->em->getRepository("Entities:CISession");
        $r = $rep->findBy(array(
            "id_user" => $iUserId
        ));

        if ($r)
            return $r;
        else
            return false;
    }

    //Sterge sesiunile utilizatorului $iUserId
    //Intoarce numarul inregistrarilor sterse
    function deleteSessions($iUserId) {
        $rows = $this->em->createQuery("delete Entities:CISession s where s.id_user=:u")
                ->setParameter(":u", $iUserId)
                ->execute();

        return $rows;
    }

    //Seteaza campul id_user in inregistrarea $sSessionId din tabela de sesiuni
    //Intoarce numarul inregistrarilor afectate
    function updateSessionUserId($sSessionId, $iUserId) {
        try{
        $r=$this->em->createQuery("update Entities:CISession ses set ses.id_user=:id_user where ses.session_id=:id")
                ->setParameter(":id_user", $iUserId)
                ->setParameter(":id", $sSessionId)
                ->execute();
        }catch(\Exception $e){
            echo $e->getMessage();
        }
        return $r;
    }

    //Intoarce inregistrarea cu user id precizat din tabela user
    /**
     * 
     * @param type $iUserId
     * @return \BusinessLogic\Models\Entities\User
     */
    function getUser($iUserId) {
        $user = $this->em->find("Entities:User", $iUserId);
        return $user;
    }

    //Update-aza parola user-ului dat si intoarce rezultatul actiunii
    function updateUserPassword($iUserId, $sNewPassword) {
        $sSql = 'UPDATE `user` SET user_parola = ? WHERE id_user = ? LIMIT 1';
        $bResult = $this->db->query($sSql, array(
            $sNewPassword,
            $iUserId
        ));

        return $bResult;
    }

    /**
     * Functie care seteaza de cate ori a esuat userul in logare
     */
    function setIncercareLogin($sUserName, $bTipIncercare) {

        return false;
        $iIncercare = 0;
        $aUser = $this->db->get_where("user", array("user_nume" => $sUserName))->row_array();
        if (isset($aUser["user_nr_login"])) {
            $iIncercare = (int) $aUser["user_nr_login"];
        } else
            return;

        if ($bTipIncercare === TRUE) {
            $iIncercare = 0;
        } else {
            $iIncercare ++;
        }

        $this->db->where("user_nume", $sUserName);
        $this->db->set("user_nr_login", $iIncercare);
        $this->db->update("user");

        if ($iIncercare > $this->config->item("number_of_safe_logins")) {
            $this->db->where("user_nume", $sUserName);
            $this->db->set("user_activ", 0);
            $this->db->update("user");
        }


        return;
    }

}
