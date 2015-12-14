<?php

namespace BusinessLogic\Models;

use Doctrine\ORM\EntityManager;
use BusinessLogic\Models\Entities as Entities;

class EmailModel extends AbstractModel {

    private $CI;

    function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
    }

    public function getUserEmailGrid($type = "sent", $id_user, $aPost) {
        $aColumnMapping = array(
            array("table" => "toEmail", "col" => "email", "ref" => "to_email"),
            array("table" => false, "col" => "CONCAT(toEmail.lastname, ' ',toEmail.firstname)", "ref" => "to_lastname"),
            array("table" => "fromEmail", "col" => "email", "ref" => "from_email"),
            array("table" => false, "col" => "CONCAT(fromEmail.lastname, ' ',fromEmail.firstname)", "ref" => "from_lastname"),
            array("table" => false, "col" => "CONCAT(e.title, ' ',e.content)", "ref" => "title"),
        );


        $dql = $this->em->getConnection()->createQueryBuilder();
        $dql->select("e.*,
toEmail.id_user as to_id_user,
toEmail.lastname as to_lastname,
toEmail.firstname as to_firstname,
toEmail.email as to_email,

fromEmail.id_user as from_id_user,
fromEmail.lastname as from_lastname,
fromEmail.firstname as from_firstname,
fromEmail.email as from_email")
                ->from("email", "e")
                ->join("e", "users", "toEmail", "e.to_email=toEmail.id_user")
                ->join("e", "users", "fromEmail", "e.from_email=fromEmail.id_user");

        switch ($type) {
            case "sent": {
                    $dql->where("e.from_email=" . $id_user);
                    $dql->andWhere("e.deleted=0");
                }break;
            case "deleted": {
                    $dql->where("e.deleted=1");
                }break;
            //inbox
            default: {
                    $dql->where("e.to_email=" . $id_user);
                    $dql->andWhere("e.deleted=0");
                }break;
        }
        $filters = $this->getGridFilterParams($aPost);


        $this->gridFiltersExt($dql, $filters, $aColumnMapping);

        $result = $dql->execute()->fetchAll();

        $totalCount = $this->getFoundRows();
        $data = array(
            'totalCount' => $totalCount,
            'data' => $result
        );

        return $data;
    }

    /**
     * 
     * @param type $id_email
     * @return \BusinessLogic\Models\Entities\Email
     */
    public function getEmail($id_email) {

        try {
            $r = $this->em->find("Entities:Email", $id_email);
            $r->setViewed(1);
            $this->em->persist($r);
            $this->em->flush();
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $r;
    }

    public function sendEmail($from, $aData) {


        $email = new Entities\Email();
        $email->postHydrate($aData);
        $email->setFrom($from);

        $to = $this->em->find("Entities:User", $aData['to_email']);
        $email->setTo($to);

        $this->em->persist($email);
        $this->em->flush();

        return array(
            "status" => "success",
            "to" => $to
        );
    }

    public function deleteEmail($aPost) {
        $email = $this->getEmail($aPost['id_email']);
        $email->setDeleted(1);
        $this->em->persist($email);
        $this->em->flush();
        return true;
    }

    public function solicitareTask($post, $from) {

        $taskModel = new TaskModel();
        $to = $taskModel->getAvailableOperator();
       
        if (!$to)
            return false;

      
        $serviciu = $this->em->find("Entities:Serviciu", $post['id_serviciu']);
 
        
        $content="Utilizatorul ".$from->getFirstname().' '.$from->getLastname()." a solicitat serviciul : <b>".$serviciu->getName()."</b><br/><br/>";
        $content.="<br/><b>Data Finalizare:</b> " . $post['date'];
        $content.= "<br/><b>Observatii client:</b> " . $post['observatii'];

       
        if ($post['file']) {
            $content.="<br/><br/><b>Fisier exemplificativ:</b> <a href='".base_url($post['file'])."'>" . base_url($post['file']."</a>");
        }

           
        $title = "Solicitare Serviciu: " . $serviciu->getName();
        $email = new Entities\Email();
        $email->setContent($content);
        $email->setTitle($title);
        $email->setFrom($from);

        $email->setTo($to);

        $this->em->persist($email);
        $this->em->flush();

        \NeoMail::genericMail($email->getContent(),$email->getTitle(), \App_constants::$OFFICE_EMAIl);
        \NeoMail::genericMail($email->getTitle(), $email->getContent(), $to->getEmail());
        

        return $to;
    }

}

?>
