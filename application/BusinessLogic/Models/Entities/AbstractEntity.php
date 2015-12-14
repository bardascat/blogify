<?php

namespace BusinessLogic\Models\Entities;

abstract class AbstractEntity {

    public function postHydrate($post_params, $customMap = false) {
        $reflect = new \ReflectionObject($this);

        foreach ($post_params as $key => $value) {

            if (property_exists($this, $key)) {
                $prop = $reflect->getProperty($key);

                if (!$prop->isPrivate()) {

                    
                    if (validateDate($value, 'd-m-Y') || validateDate($value, 'd-m-Y H:i:s') || validateDate($value, 'Y-m-d H:i:s') || validateDate($value, 'd-m-Y H:i') ||  validateDate($value, 'd.m.Y H:i')) {
                        
                        $value = new \DateTime($value);
                    }

                    $this->$key = $value;
                }
            }
            if ($customMap)
                foreach ($customMap as $key => $prop) {
                    $this->$prop = $post_params[$key];
                }
        }
    }

    public function generateStdObject() {
        $class = new \stdClass;

        foreach ($this as $key => $value) {
            $class->$key = $value;
        }
        return $class;
    }

    public function getIterationArray() {

        $iteration = array();
        foreach ($this as $key => $value) {
            if (!is_object($value) || ($value instanceof \DateTime))
                switch (\BusinessLogic\Util\Language::getLanguage()) {
                    case "ro": {
                            $ro_key = $key . "_ro";
                            if (isset($this->$ro_key)) {
                                $iteration[$key] = $this->$ro_key;
                            } else
                                $iteration[$key] = $value;
                        }break;
                    default: {
                            $iteration[$key] = $value;
                        }
                }
        }

        return $iteration;
    }

}

function validateDate($date, $format = 'Y-m-d H:i:s') {
    $d = \DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

?>
