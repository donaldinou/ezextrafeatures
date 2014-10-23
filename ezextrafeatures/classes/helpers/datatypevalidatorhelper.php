<?php
namespace extension\ezextrafeatures\classes\helpers {

    use extension\ezextrafeatures\classes\enums\datatypeEnum;

    abstract class dataTypeValidatorHelper extends Helper {

        public static function validateClassField( $value, datatypeEnum $type, $options=array() ) {
            $result = \eZInputValidator::STATE_ACCEPTED;
            $filterOptions = array();

            switch ($type) {
                case datatypeEnum::CHAR:
                    if (strlen($value) > 1) {
                        $result = \eZInputValidator::STATE_INVALID;
                    }
                    break;

                case datatypeEnum::STRING:
                case datatypeEnum::VARCHAR:
                    if (!is_string($value)) {
                        $result = \eZInputValidator::STATE_INVALID;
                    }
                    break;

                case datatypeEnum::HEXA:
                    $filterOptions['flags'] = FILTER_FLAG_ALLOW_HEX;
                case datatypeEnum::OCTAL:
                    $filterOptions['flags'] = FILTER_FLAG_ALLOW_OCTAL;

                case datatypeEnum::INT:
                case datatypeEnum::INTEGER:
                case datatypeEnum::LONG:
                    if ( isset($option['min_range']) ) {
                        $filterOptions['options']['min_range'] = $options['min_range'];
                    }
                    if ( isset($option['max_range']) ) {
                        $filterOptions['options']['max_range'] = $options['max_range'];
                    }
                    if (filter_var($value, FILTER_VALIDATE_INT, $filterOptions) === false) {
                        $result = \eZInputValidator::STATE_INVALID;
                    }
                    break;

                case datatypeEnum::FLOAT:
                case datatypeEnum::REAL:
                case datatypeEnum::DOUBLE:
                case datatypeEnum::DECIMAL:
                    $filterOptions['flags'] = FILTER_FLAG_ALLOW_THOUSAND;
                    if ( isset($option['decimal']) ) {
                        $filterOptions['options']['decimal'] = $option['decimal'];
                    }
                    if (filter_var($value, FILTER_VALIDATE_FLOAT, $filterOptions) === false) {
                        $result = \eZInputValidator::STATE_INVALID;
                    } else {
                        if ( isset($option['min_range']) ) {
                            if ( (float)$value < (float)$option['min_range'] ) {
                                $result = \eZInputValidator::STATE_INVALID;
                            }
                        }
                        if ( isset($option['max_range']) ) {
                            if ( (float)$value > (float)$option['max_range'] ) {
                                $result = \eZInputValidator::STATE_INVALID;
                            }
                        }
                    }
                    break;

                case datatypeEnum::BOOL:
                case datatypeEnum::BOOLEAN:
                    $filterOptions['flags'] = FILTER_NULL_ON_FAILURE;
                    if ( is_null(filter_var($value, FILTER_VALIDATE_BOOLEAN, $filterOptions)) ) {
                        $result = \eZInputValidator::STATE_INVALID;
                    }
                    break;

                case datatypeEnum::ARR:
                    if (!is_array($value)) {
                        $result = \eZInputValidator::STATE_INVALID;
                    }
                    break;

                case datatypeEnum::OBJECT:
                    if (!is_object($value)) {
                        $result = \eZInputValidator::STATE_INVALID;
                    }
                    break;

                case datatypeEnum::EMAIL:
                    if (filter_var($value, FILTER_VALIDATE_EMAIL, $filterOptions) === false) {
                        $result = \eZInputValidator::STATE_INVALID;
                    }
                    break;

                case datatypeEnum::IP:
                    if ( isset($option['mode']) ) {
                        if ( strtolower($option['mode']) == 'ipv6' ) {
                            $filterOptions['flags'] = FILTER_FLAG_IPV6;
                        } elseif (strtolower($option['mode']) == 'ipv4') {
                            $filterOptions['flags'] = FILTER_FLAG_IPV4;
                        }
                    }
                    if (filter_var($value, FILTER_VALIDATE_IP, $filterOptions) === false) {
                        $result = \eZInputValidator::STATE_INVALID;
                    }
                    break;

                case datatypeEnum::URL:
                    if ( isset($option['path_required']) && $option['path_required'] ) {
                        $filterOptions['flags'] = (isset($filterOptions['flags'])) ? $filterOptions['flags']|FILTER_FLAG_PATH_REQUIRED : FILTER_FLAG_PATH_REQUIRED;
                    }
                    if ( isset($option['query_required']) && $option['query_required'] ) {
                        $filterOptions['flags'] = (isset($filterOptions['flags'])) ? $filterOptions['flags']|FILTER_FLAG_QUERY_REQUIRED : FILTER_FLAG_QUERY_REQUIRED;
                    }
                    if (filter_var($value, FILTER_VALIDATE_URL, $filterOptions) === false) {
                        $result = \eZInputValidator::STATE_INVALID;
                    }
                    break;

                default:
                    $result = \eZInputValidator::STATE_INTERMEDIATE;
                break;
            }

            // if unit test is ok
            if ($result == \eZInputValidator::STATE_ACCEPTED) {
                if (!empty($option['allowed'])) {
                    if ( !is_array($value) ) {
                        $value = array($value);
                    }
                    foreach ($value as $val) {
                        if (!in_array($val, $option['allowed'])) {
                            $result = \eZInputValidator::STATE_INVALID;
                        }
                    }
                }
            }

            return $result;
        }

        public static function convertObjectField( $value, datatypeEnum $type ) {
            switch ($type) {
                case datatypeEnum::INT:
                case datatypeEnum::INTEGER:
                case datatypeEnum::LONG:
                    $value = (int)$value;
                    break;

                case datatypeEnum::FLOAT:
                case datatypeEnum::DECIMAL:
                case datatypeEnum::REAL:
                case datatypeEnum::DOUBLE:
                    $value = (float)$value;
                    break;

                case datatypeEnum::BOOLEAN:
                case datatypeEnum::BOOL:
                    $value = (bool)$value;
                    break;

                case datatypeEnum::ARR:
                    $value = unserialize($value);
                    break;

                default:
                    $value = (string)$value;
                    break;
            }
        }


    }

}
?>