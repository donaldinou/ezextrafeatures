<?php 
namespace extension\ezextrafeatures\autoloads {
    
    /**
     * @brief Class operator to manage functions in template
     * @details Class operator to manage functions in template.
     * This provide operators to use internal php function in template
     * 
     * @author Adrien Loyant <adrien.loyant@te-laval.fr>
     * 
     * @date 2012-01-01
     * @version 1.0.0
     * @since 1.0.0
	 * @copyright GNU Public License v.2
     * 
     * extension\ezextrafeatures\autoloads
     */
    class eZPHPFeaturesTemplateOperators {
    
        /**
         * @brief The operators
         * @details The internal operators template
         * 
         * @var array
         */
        protected $Operators;
    
        /**
         * @brief Return the operators names
         * @details Return the operators names
         * 
         * @return array
         */
        public static function operators() {
            return array( 'call_php_func' );
        }
    
        /**
         * @brief Constructor
         * @details The constructor for the operator
         * 
         * @return void
         */
        public function __construct() {
            $this->Operators = static::operators();
        }
    
        /**
         * @brief Return an array with the template operator name.
         * @details Return an array with the template operator name.
         * 
         * @return array
         */
        public function operatorList() {
            return $this->Operators;
        }
    
        /**
         * @brief Return true to tell the template engine that the parameter list exists per operator type
         * @details Return true to tell the template engine that the parameter list exists per operator type,
         * this is needed for operator classes that have multiple operators.
         * 
         * @return boolean
         */
        public function namedParameterPerOperator() {
            return true;
        }
    
        /**
         * @brief Returns an array of named parameters, this allows for easier retrieval of operator parameters.
         * @details Returns an array of named parameters, this allows for easier retrieval of operator parameters.
         * @see \eZTemplateOperator::namedParameterList
         * 
         * @return multitype:multitype:multitype:string boolean number  multitype:string boolean
         */
        public function namedParameterList() {
            return array(
                            'call_php_func' => array(
                                            'callback' => array( 'type' => 'string', 'required' => true, 'default' => '' ),
                                            'param_arr' => array( 'type' => 'array', 'required' => false, 'default' => array() ),
                                            'output' => array( 'type' => 'boolean', 'required' => false, 'default' => false )
                            )
            );
        }
    
        /**
         * @brief Executes the PHP function for the operator cleanup and modifies \a $operatorValue
         * @details Executes the PHP function for the operator cleanup and modifies \a $operatorValue
         * 
         * @param mixed $tpl
         * @param mixed $operatorName
         * @param mixed $operatorParameters
         * @param mixed $rootNamespace
         * @param mixed $currentNamespace
         * @param mixed $operatorValue This args is passed by reference
         * @param array $namedParameters
         * @param mixed $placement
         * 
         * @return void
         */
        public function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, array $namedParameters, $placement ) {
        switch ( $operatorName ) {
                case 'call_php_func':
                    if ( !empty($operatorValue) ) {
                        $callback = $operatorValue;
                        $param_arr = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
                        $output = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace, $placement );
                    } else {
                        $callback = $namedParameters['callback'];
                        $param_arr = $namedParameters['param_arr'];
                        $output = $namedParameters['output'];
                    }
                    ob_start();
                    $operatorValue = $this->callPHPFunc($callback, $param_arr);
                    if ( $output ) {
                        $operatorValue = ob_get_contents();
                    }
                    ob_end_clean();
                    break;
                    
                default:
                    // Nothing
                    break;
            }
        }
        
        /**
         * @brief Call a callback with an array of parameters
         * @details Call a callback with an array of parameters
         * This should be called like this:
         * callPHPFunc( 'str_replace', array( "%body%", "black", "<body text='%body%'>" ) );
         * or
         * callPHPFunc( array('DateTime', 'createFromFormat '), array( 'j-M-Y', '15-Feb-2009' ) );
         * 
         * @param mixed $callback The callback to be call
         * @param array $param_arr The parameters to be passed to the callback, as an indexed array.
         * @return mixed Returns the return value of the callback, or FALSE on error.
         */
        public function callPHPFunc( $callback, array $param_arr ) {
            // init var
            $iniExtraFeatures = \eZINI::instance( 'extrafeatures.ini' );
            $isCallable = false;
            $result = false;
            
            // set authorized library
            $authorizedFunctions = array( );
            if ( $iniExtraFeatures->hasVariable( 'eZPHPFunc', 'AuthorizedFunctions') ) {
                $authorizedFunctions = $iniExtraFeatures->variable( 'eZPHPFunc', 'AuthorizedFunctions');
            }
            
            if (!is_array($param_arr)) {
                \eZDebug::writeWarning( 'Parameters for callable function : '. $callback .' is not an array' );
                $param_arr = array( $param_arr );
            }
            
            // run test
            if (!empty($callback) && !is_object($callback)) {
                if (!is_array($callback)) {
                    if (function_exists($callback)) {
                        if (in_array($callback, $authorizedFunctions)) {
                            $isCallable = true;
                        } else {
                            \eZDebug::writeWarning( 'The callable function : '. $callback .' is not permit for this instance' );
                        }
                    } else {
                        \eZDebug::writeError( 'The callable function : '. $callback .' is not a valid php function' );
                    }
                } else {
                    $class_name = (isset($callback[0])) ? $callback[0] : null;
                    $method_name = (isset($callback[1])) ? $callback[1] : null;
                    if (!is_null($class_name) && !is_null($method_name) ) {
                        if ( class_exists($class_name, false) && method_exists($class_name, $method_name) ) {
                            if (in_array($class_name.'::'.$method_name, $authorizedFunctions)) {
                                try {
                                    $reflection = new \ReflectionMethod($class_name, $method_name);
                                    if ($reflection->isStatic()) {
                                        $isCallable = true;
                                    } else {
                                        \eZDebug::writeError( 'The callback method : '. print_r($callback, true) .' is not static' );
                                    }
                                } catch (ReflectionException $re) {
                                    \eZDebug::writeError( $re->getMessage() );
                                } catch (Exception $e) {
                                    \eZDebug::writeError( $e->getMessage() );
                                }
                            } else {
                                \eZDebug::writeWarning( 'The callable class method : '. $class_name.'::'.$method_name .' is not permit for this instance' );
                            }
                        } else {
                            \eZDebug::writeError( 'Parameters that define callback : '. print_r($callback, true) .' is not a valid' );
                        }
                    } else {
                        \eZDebug::writeError( 'Parameters that define callback : '. print_r($callback, true) .' is not a valid' );
                    }
                }
            } else {
                \eZDebug::writeError( 'The callable function : '. print_r($callback, true) .' is not a valid callback' );
            }
            
            // run call
            if ($isCallable) {
                $result = call_user_func_array($callback, $param_arr);
            }
            
            return $result;
        }
    
    }
}
?>