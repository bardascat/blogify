<?php

namespace BusinessLogic\Models;

use Doctrine\ORM\EntityManager;
use NeoMvc\Models\Entities as Entities;

class NomenclatorModel extends AbstractModel {

    function __construct() {
        parent::__construct();
    }

    public function getPages() {
        $qb = $this->em->createQueryBuilder();
        $qb->select("p")
                ->from("Entities:SimplePage", "p")
                ->orderBy("p.id_page", "desc");

        $query = $qb->getQuery();
        //  $query->setQueryCacheDriver(new \Doctrine\Common\Cache\ApcCache());
        //  $query->useQueryCache(true);
        $query->execute();
        return $query->getResult();
    }

    public function getUserPachete($aPost) {

        $dql = $this->em->getConnection()->createQueryBuilder();
        $pachete = $dql->select("pachet.id_pachet,pachet.name")
                        ->from("orders", "orders")
                        ->join("orders", "orders_items", "orders_items", "orders.id_order=orders_items.id_order")
                        ->join("orders", "users", "users", "orders.id_user=users.id_user")
                        ->join("orders_items", "pachet", "pachet", "orders_items.id_pachet=pachet.id_pachet")
                        ->where("orders.payment_status=:status")
                        ->andWhere("orders.order_type!='alimentare'")
                        ->andWhere("orders.id_user=:id_user")
                        ->setParameter("id_user", $aPost['id_user'])
                        ->setParameter("status", \App_constants::$PAYMENT_STATUS_CONFIRMED)
                        ->execute()->fetchAll();


        return $pachete;
    }

    public function getPachete($aPost) {

        if (isset($aPost['xaction']) && $aPost['xaction'] == 'update' && $aPost['data']) {
            $modificari = json_decode($aPost['data']);
            if (!is_array($modificari)) {
                $modificari = array($modificari);
            }
            foreach ($modificari as $modificare) {
                $this->updatePachet((array) $modificare);
            }
        }

        $pacheteQB = $this->em->createQueryBuilder()
                ->select("p")
                ->from("Entities:Pachet", "p")
                ->where("p.name!='Alimentare cont'");

        $pacheteQB->groupBy("p.id_pachet");

        $pachete = $pacheteQB->orderBy("p.price", "asc")
                        ->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);


        return $pachete;
    }

    /**
     * 
     * @return Entities\Pachet
     */
    public function getPacheteEntity() {
        try{
        $qb = $this->em->createQueryBuilder();
        $qb->select('p')
                ->from('Entities:Pachet', 'p')
                 ->where('p.name!=:no')
                ->setParameter("no","Alimentare cont")
                ->orderBy("p.price","asc")
               ;

        
        return $qb->getQuery()->getResult();
        }catch(\Exception $e){
            echo $e->getMessage();
        }
    }

    public function getGridServicii($aPost) {


        if (isset($aPost['xaction']) && $aPost['xaction'] == 'update' && $aPost['data']) {
            $modificari = json_decode($aPost['data']);
            if (!is_array($modificari)) {
                $modificari = array($modificari);
            }
            foreach ($modificari as $modificare) {
                $this->updateServiciu((array) $modificare);
            }
        }

        $aColumnMapping = array();
        $dql = $this->em->getConnection()->createQueryBuilder();
        $dql->select("s.*")
                ->from("serviciu", "s")
                ->leftjoin("s", "pachet_serviciu", "pachet_serviciu", "s.id_serviciu=pachet_serviciu.id_serviciu");

        if (isset($aPost['id_pachet'])) {
            $dql->where("pachet_serviciu.id_pachet=:id_pachet");
            $dql->setParameter("id_pachet", $aPost['id_pachet']);
        }
        $dql->groupBy("s.id_serviciu");

        if (!isset($aPost["sort"]))
            $aPost["sort"] = "id_serviciu";

        $this->gridFiltersExt($dql, $this->getGridFilterParams($aPost), $aColumnMapping);


        $result = $dql->execute()->fetchAll();
        $totalCount = $this->getFoundRows();

        $data = array(
            'totalCount' => $totalCount,
            'data' => $result
        );
        return $data;
    }

    public function getServicii() {
        try {
            $servicii = $this->em->createQueryBuilder()
                            ->select("s")
                            ->from("Entities:Serviciu", "s")
                            ->orderBy("s.id_serviciu", "desc")
                            ->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            return $servicii;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getServiciiPachet($aPost) {

        if ($aPost['xaction'] == 'update' && $aPost['data']) {
            $modificari = json_decode($aPost['data']);
            if (!is_array($modificari)) {
                $modificari = array($modificari);
            }
            foreach ($modificari as $modificare) {
                $this->updateServiciu((array) $modificare);
            }
        }

        try {
            $servicii = $this->em->createQueryBuilder()
                            ->select("s")
                            ->from("Entities:Serviciu", "s")
                            ->join("s.pachete", "p")
                            ->where("p.id_pachet=:id_pachet")
                            ->setParameter(":id_pachet", $aPost['id_pachet'])
                            ->orderBy("s.id_serviciu", "desc")
                            ->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return array(
            "data" => $servicii,
            "totalCount" => count($servicii)
        );
    }

    public function addPachet($aPost) {
        $pachet = new \BusinessLogic\Models\Entities\Pachet();
        $pachet->postHydrate($aPost);
        $this->em->persist($pachet);
        $this->em->flush();
        return true;
    }

    public function addServiciu($aPost) {
        try {
            $pachet = $this->getPachet($aPost['id_pachet']);
            $serviciu = $this->getServiciu($aPost['id_serviciu']);
            $pachet->addServiciu($serviciu);
            $this->em->persist($serviciu);
            $this->em->persist($pachet);
            $this->em->flush();
            return true;
        } catch (\Exception $e) {

            return false;
        }
    }

    public function createServiciu($aPost) {
        $serviciu = new \BusinessLogic\Models\Entities\Serviciu();
        $serviciu->postHydrate($aPost);
        $this->em->persist($serviciu);
        $this->em->flush();
        return true;
    }

    public function updatePachet($modificari) {

        $pachet = $this->getPachet($modificari['id_pachet']);
        $pachet->postHydrate($modificari);

        $this->em->persist($pachet);

        $this->em->flush();
        return true;
    }

    public function updateServiciu($modificari) {

        $pachet = $this->getServiciu($modificari['id_serviciu']);

        $pachet->postHydrate($modificari);

        $this->em->persist($pachet);

        $this->em->flush();
        return true;
    }

    /**
     * 
     * @param type $id_pachet
     * @return \BusinessLogic\Models\Entities\Pachet
     */
    public function getPachet($id_pachet) {
        return $this->em->find("Entities:Pachet", $id_pachet);
    }

    /**
     * 
     * @param type $id_pachet
     * @return \BusinessLogic\Models\Entities\Serviciu
     */
    public function getServiciu($id_serviciu) {
        return $this->em->find("Entities:Serviciu", $id_serviciu);
    }

    public function getRoluri($aData) {
        try {
            $roluri = $this->em->createQueryBuilder()
                    ->select("s")
                    ->from("Entities:Rol", "s");
            if (isset($aData['operator'])) {
                $roluri->where("s.rol_nume!='client'");
            } else if (isset($aData['client'])) {
                $roluri->where("s.rol_nume='client'");
            }
            $result = $roluri->orderBy("s.rol_id", "desc")
                            ->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getUseriCombo($aPost) {


        $dql = $this->em->getConnection()->createQueryBuilder();
        $dql->select("u.*, CONCAT(lastname, ' ',firstname) as user_name")
                ->from("users", "u")
                ->join("u", "user_rol", "ur", "u.id_user=ur.id_user")
                ->join("ur", "rol", "rol", "ur.rol_id=rol.rol_id");


        if (isset($aPost['to'])) {
            $dql->andWhere("u.id_user=" . $aPost['to']);
        }


        if (isset($aPost["query"]) && $aPost["query"] != "") {
            $dql->andWhere("u.lastname like '%" . $aPost['query'] . "%' or u.firstname like '%" . $aPost['query'] . "%'");
        }
        if (isset($aPost['user_status'])) {
            $dql->andWhere("u.user_activ=" . $aPost['user_status']);
        }

        if (isset($aPost['user_rol'])) {
            $dql->andWhere("rol.rol_nume=:rol_nume");
            $dql->setParameter("rol_nume", $aPost['user_rol']);
        }

        $dql->orderBy("u.lastname", "desc");


        $data = $dql->execute()->fetchAll();
        $totalCount = $this->getFoundRows();



        return array(
            "totalCount" => $totalCount,
            "data" => $data
        );
    }

    public function getParteneri($aPost) {


        $dql = $this->em->getConnection()->createQueryBuilder();
        $dql->select("p.*")
                ->from("partener", "p");


        if (isset($aPost["query"]) && $aPost["query"] != "") {
            $dql->andWhere("p.name like '%" . $aPost['query']);
        }
        $dql->orderBy("p.name", "asc");

        $data = $dql->execute()->fetchAll();
        $totalCount = $this->getFoundRows();



        return array(
            "totalCount" => $totalCount,
            "data" => $data
        );
    }

    public function deletePachet($aPost) {
        $pachet = $this->getPachet($aPost['id_pachet']);
        $this->em->remove($pachet);
        $this->em->flush();
        return true;
    }

    public function deleteServiciu($aPost) {
        $pachet = $this->getServiciu($aPost['id_serviciu']);
        $this->em->remove($pachet);
        $this->em->flush();
        return true;
    }

}

?>
