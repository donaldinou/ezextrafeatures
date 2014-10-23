<?php
namespace extension\ezextrafeatures\autoloads {

    /**
     * @brief Class operator to manage functions in template
     * @details Class operator to manage functions in template
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
    class eZJSFeaturesTemplateOperators {

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
            return array( 'get_preferred_library' );
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
                            'get_preferred_library' => array(
                                                                'fallback_value' => array( 'type' => 'string', 'required' => false, 'default' => 'jquery' )
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
                case 'get_preferred_library':
                    $fallbackValue = null;
                    if (!empty($namedParameters['fallback_value'])) {
                        $fallbackValue = $namedParameters['fallback_value'];
                    }
                    $operatorValue = $this->getPreferredLibrary($fallbackValue);
                    break;

                default:
                    // Nothing
                    break;
            }
        }

        /**
         * @brief Get the preferred library defined in ezjscore
         * @details Get the preferred library defined in ezjscore.
         * If the library define is not in the authorized library then it will use the $fallbackValue.
         * It could be usefull when you have multiples js file which use different library in extension you don't have write rights.
         * For example, preferred library is yui3, but you got a script that can be use with both jquery and mootools.
         * You set authorizedlibrary to jquery and mootools and then precise the fallback value. Old scripts continue to work well,
         * and you don't got any problems with newer.
         *
         * @param string $fallbackValue
         * @return string
         */
        public function getPreferredLibrary( $fallbackValue='jquery' ) {
            // init vars
            $inieZJSCore = \eZINI::instance( 'ezjscore.ini' );

            // set authorized library
            $authorizedLibrary = array( 'jquery' );
            if ( $inieZJSCore->hasVariable( 'eZJSCore', 'AuthorizedLibrary') ) {
                $authorizedLibrary = $inieZJSCore->variable( 'eZJSCore', 'AuthorizedLibrary');
            }

            // set preferred library
            $preferredLibrary = 'jquery';
            if ( $inieZJSCore->hasVariable( 'eZJSCore', 'PreferredLibrary') ) {
                $preferredLibrary = $inieZJSCore->variable( 'eZJSCore', 'PreferredLibrary');
            }

            // set result
            $result = $preferredLibrary;
            if (!in_array($preferredLibrary, $authorizedLibrary)) {
                $result = $fallbackValue;
            }

            // return value
            return $result;
        }

    }
}
