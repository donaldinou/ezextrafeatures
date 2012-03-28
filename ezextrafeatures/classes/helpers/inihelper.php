<?php 
namespace extension\ezextrafeatures\classes\helpers {
    
    /**
     * @brief Helper which provide help for ini file
	 * @details Helper which provide help for ini file
	 * 
	 * @author Adrien Loyant <adrien.loyant@te-laval.fr>
	 * 
	 * @date 2012-03-01
	 * @version 1.0.0
	 * @since 1.0.0
	 * @copyright GNU Public License v.2
	 * 
	 * @package extension\ezextrafeatures\classes\helpers
     */
    abstract class iniHelper extends Helper {
        
        /**
         * @brief pattern for multidimentionnal array
         * @details pattern for multidimentionnal array
         * 
         * @var string
         */
		const SECTION_PATTERN = '/^\[[0-9a-zA-Z\-\_]*\]$/';
		
        /**
         * @brief Tranform an ini config file to a json config string
         * @details Tranform an ini config file to a json config string
         * 
         * @param string $fileName
         * @param string|array $sectionsRoot
         * @param boolean $convert convert string variable from ini file to real type if it set to true
         * 
         * @return multitype:string |string
         */
        public static function INItoJSON( $fileName='site.ini', $sectionsRoot=array(), $convert=false ) {
            $result = array();
            $ini = \eZINI::instance( $fileName );
            
            // Set root section
            if (!is_array($sectionsRoot)) {
                $sectionsRoot = array( $sectionsRoot );
            }
            if (count($sectionsRoot)<1) {
                $sectionsRoot = $ini->BlockValues;
            }
			
			$result = array();
			foreach($sectionsRoot as $section) {
				if ($ini->hasSection($section)) {
					$result[] = static::buildConfigArray( $ini->group($section), $ini, $convert );
				}
			}
			
			// FIX : do not return an array while there is just one result
			if ( count($result)<2) {
			    reset($result);
			    $result = current($result);
			}
			
            return json_encode($result);
        }
        
        /**
         * @todo
         */
        public static function JSONtoINI(  ) {
            
        }
		
        /**
         * @brief Recursive method to build multidimentionnal array
         * @details Recursive method to build multidimentionnal array
         * 
         * @param array $root
         * @param \eZINI $config
         * @param boolean $convert convert string variable to real type if it set to true
         * 
         * @return array
         */
		private static function buildConfigArray( array $root, \eZINI $config, $convert=false ) {
			$result = $root;
			if (is_array($root)) {
				foreach ($root as $key => $value) {
					if (is_array($value)) {
						$result[$key] = self::buildConfigArray( $value, $config, $convert );
					} else {
						if (preg_match(static::SECTION_PATTERN, $value)) {
							$section = substr($value, 1, -1);
							if ( $config->hasSection($section) ) {
								$result[$key] = self::buildConfigArray( $config->group($section), $config, $convert );
							} else {
								$result[$key] = $value; // no need to convert because this is a string
							}
						} else {
						    $result[$key] = ($convert) ? static::convertToJSON($value) : $value;
						}
					}
				}
			}
			return $result;
		}
		
		/**
		 * @brief Convert a typed object to a type use in a json
		 * @details Convert a typed object to a type use in a json.
		 * Currently, only integer, float and boolean are managed
		 * 
		 * @param mixed $value
		 * @return mixed
		 * 
		 * @note You can passed anything object you want, 
		 * but keep in memory that $value is firstly converted into a string.
		 */
		public static function convertToJSON( $value ) {
		    $result = $value;
		    
		    // first convert it into string
		    $value = static::convertToINI($value);
		    
		    // check type 
		    if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
		        $result = (int)$value;
		    }
		    elseif (filter_var($value, FILTER_VALIDATE_FLOAT) !== false) {
		        $result = (float)$value;
		    }
		    elseif (!is_null(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
		        $result = (bool)$value;
		    }
		    
		    return $result;
		}
		
		/**
		 * 
		 * @param unknown_type $value
		 */
		public static function convertToINI( $value ) {
		    return (string)$value;
		}
        
    }
    
}
?>