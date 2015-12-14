<?php

namespace BusinessLogic\Models;

class AclModel extends AbstractModel {

    public function setAclResources($resources) {
        $resourcesRep = $this->em->getRepository("Entities:AclResource");
        $roles = $this->getRoles();
        foreach ($resources as $name => $alias) {
            $rep = $resourcesRep->findBy(array("name" => $name, "alias" => $alias));
            //daca nu este deja definita resursa, o inseram
            if (!isset($rep[0])) {
                $entity = new Entities\AclResource();
                $entity->setName($name);
                $entity->setAlias($alias);
                $this->em->persist($entity);

                //adaugam resursa rolurilor
                foreach ($roles as $role) {
                    $acl = new Entities\Acl();
                    if ($role->getName() == "admin")
                        $acl->setAction('allow');
                    else
                        $acl->setAction('deny');
                    $acl->setResource($entity);
                    $acl->setRole($role);
                    $this->em->persist($acl);
                }
            }
        }

        $bdResources = $this->getAclResources();
        //Scoatam resursele din baza de date care nu mai sunt active
        foreach ($bdResources as $bdResource) {
            if (!in_array($bdResource->getAlias(), $resources)) {
                $this->em->remove($bdResource);
            }
        }
        $this->em->flush();
    }

    /**
     * 
     * @return \BusinessLogic\Models\Entities\AclRole
     */
    public function getRole($id_role) {
        $role = $this->em->find("Entities:AclRole", $id_role);
        return $role;
    }

    /**
     * 
     * @return \BusinessLogic\Models\Entities\AclRole
     */
    public function getRoles() {
        $rep = $this->em->getRepository("Entities:AclRole");
        $r = $rep->findAll();
        return $r;
    }

    /**
     * @return \BusinessLogic\Models\Entities\AclResource
     */
    public function getAclResources() {
        $rep = $this->em->getRepository("Entities:AclResource");
        $r = $rep->findAll();
        return $r;
    }

    public function getAclResourcesForRole($id_role) {
        $rep = $this->em->getRepository("Entities:AclResource");
        $r = $rep->findBy(array(),array("alias"=>"asc"));

        $con = $this->em->getConnection();
        $stm = $con->prepare("select id_resource from acl where id_role=:id_role and action='allow'");
        $stm->bindParam(':id_role', $id_role);
        $stm->execute();
        $res = $stm->fetchAll();

        $selected_resource = array();
        if ($res)
            foreach ($res as $resource) {
                $selected_resource[] = $resource['id_resource'];
            }

        foreach ($r as $key => $resource) {
            if (in_array($resource->getId_resource(), $selected_resource)) {

                $resource->checked = 1;
                $r[$key] = $resource;
            }
        }

        return $r;
    }

    /**
     * 
     * @param type $id_role
     * @return \BusinessLogic\Models\Entities\Acl
     */
    public function getAclForRole($id_role) {
        $rep = $this->em->getRepository("Entities:Acl");
        $r = $rep->findBy(array("id_role" => $id_role));
        return $r;
    }

    public function setRoleRules($id_role, $rules) {
        //delete old acl
        $this->em->createQuery("delete from Entities:Acl acl where acl.id_role=$id_role")
                ->execute();
        $resources = $this->getAclResources();
        $role = $this->getRole($id_role);

        foreach ($resources as $key => $resource) {
            $acl = new Entities\Acl();

            if ($rules) {
                if (in_array($resource->getId_resource(), $rules))
                    $acl->setAction("allow");
                else
                    $acl->setAction("denny");
            }
            else
                $acl->setAction("denny");
            $acl->setRole($role);

            $acl->setResource($resource);
            $this->em->persist($acl);
        }
        $this->em->flush();
        return true;
    }

}
?>