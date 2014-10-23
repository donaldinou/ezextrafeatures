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
    class eZINIFeaturesTemplateOperators {

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
            return array( 'ezini_hassection', 'ezini_hasgroup', 'ezini_section', 'ezini_group' );
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
                            'ezini_hassection' => array(
                                            'section' => array( 'type' => 'string', 'required' => true, 'default' => '' ),
                                            'ini_file' => array( 'type' => 'string', 'required' => false, 'default' => 'site.ini' ),
                                            'ini_path' => array( 'type' => 'string', 'required' => false, 'default' => null ),
                                            /*'dynamic' => array( 'type' => 'boolean', 'required' => false, 'default' => false ) //useless */
                            ),
                            'ezini_hasgroup' => array(
                                            'group' => array( 'type' => 'string', 'required' => true, 'default' => '' ),
                                            'ini_file' => array( 'type' => 'string', 'required' => false, 'default' => 'site.ini' ),
                                            'ini_path' => array( 'type' => 'string', 'required' => false, 'default' => null ),
                                            /*'dynamic' => array( 'type' => 'boolean', 'required' => false, 'default' => false ) //useless */
                            ),
                            'ezini_section' => array(
                                            'section' => array( 'type' => 'string', 'required' => true, 'default' => '' ),
                                            'ini_file' => array( 'type' => 'string', 'required' => false, 'default' => 'site.ini' ),
                                            'ini_path' => array( 'type' => 'string', 'required' => false, 'default' => null ),
                                            /*'dynamic' => array( 'type' => 'boolean', 'required' => false, 'default' => false ) //useless */
                            ),
                            'ezini_group' => array(
                                            'group' => array( 'type' => 'string', 'required' => true, 'default' => '' ),
                                            'ini_file' => array( 'type' => 'string', 'required' => false, 'default' => 'site.ini' ),
                                            'ini_path' => array( 'type' => 'string', 'required' => false, 'default' => null ),
                                            /*'dynamic' => array( 'type' => 'boolean', 'required' => false, 'default' => false ) //useless */
                            ),
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
        public function modify( \eZTemplate $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, array $namedParameters, $placement ) {

            // Prepare variables
            if ( count($operatorParameters) > 0 ) {

                $section = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                $ini_file = (isset($namedParameters['ini_file'])) ? $namedParameters['ini_file'] : 'site.ini' ;
                $ini_path = (isset($namedParameters['ini_path'])) ? $namedParameters['ini_path'] : null ;
                // NOTE : there is a third parameter for namedparameters : dynamic

                if (!is_null($ini_path)) {
                    $ini = \eZINI::instance( $ini_file, $ini_path, null, null, null, true );
                } else {
                    $ini = \eZINI::instance( $ini_file );
                }

            } else {
                $tpl->error( $operatorName, 'Missing variable section/group parameter' );
            }

            switch ( $operatorName ) {
                case 'ezini_hassection':
                    $operatorValue = $this->hasSection($ini, $section);
                    break;

                case 'ezini_hasgroup':
                    $operatorValue = $this->hasSection($ini, $section);
                    break;

                case 'ezini_section':
                    $operatorValue = $this->section($ini, $section);
                    break;

                case 'ezini_group':
                    $operatorValue = $this->group($ini, $section);
                    break;

                default:
                    // Nothing
                    break;
            }
        }

        /**
         * @brief Return true if the specific section exist in the ini file
         * @details Return true if the specific section exist in the ini file
         *
         * @param \eZINI $ini
         * @param string $blockName
         *
         * @return boolean
         */
        public function hasSection( \eZINI $ini, $sectionName ) {
            return $ini->hasSection($sectionName);
        }

        /**
         * @brief Return true if the specific group exist in the ini file
         * @details Return true if the specific group exist in the ini file
         *
         * @param \eZINI $ini
         * @param string $blockName
         *
         * @return boolean
         */
        public function hasGroup( \eZINI $ini, $blockName ) {
            return $ini->hasGroup($blockName);
        }

        /**
         * @brief Return variables for the specific section exist in the ini file
         * @details Return variables for specific section exist in the ini file
         *
         * @note this is not recursive (multidimensionnal array)
         *
         * @param \eZINI $ini
         * @param string $sectionName
         *
         * @return mixed
         */
        public function section( \eZINI $ini, $sectionName ) {
            return $ini->group($sectionName);
        }

        /**
         * @brief Return variables for the specific section exist in the ini file
         * @details Return variables for specific section exist in the ini file
         *
         * @note this is not recursive (multidimensionnal array)
         *
         * @param \eZINI $ini
         * @param string $blockName
         *
         * @return mixed
         */
        public function group( \eZINI $ini, $blockName ) {
            return $ini->group($blockName);
        }

    }
}
