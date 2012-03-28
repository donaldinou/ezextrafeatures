<?php 
namespace extension\ezextrafeatures\classes\helpers {
	
	/**
	 * @brief Meta class for all helpers
	 * @details All helper classes should extends this class because this is their MetaClass
	 * 
	 * @author Adrien Loyant <adrien.loyant@te-laval.fr>
	 * 
	 * @date 2012-03-01
	 * @version 1.0.0
	 * @since 1.0.0
	 * @copyright GNU Public License v.2
	 * 
	 * @package extension\ezadvancedautoload\classes\helpers
	 */
	abstract class Helper {
		
		/**
		 * @brief There are no constructors for an abstract class
		 * @details There are no constructors for an abstract class
		 * 
		 * @return void
		 */
		final public function __construct() {
			// do nothing
		}
		
		/**
		 * @brief An helper isn't clonable
		 * @details An helper isn't clonable
		 * 
		 * @return void
		 */
		final public function __clone() {
			trigger_error('This class is not cloneable');
		}
		
		/**
		 * @brief An helper isn't serializable
		 * @details An helper isn't serializable
		 *
		 * @return void
		 */
		final public function __sleep() {
			trigger_error('This class is not serializable');
		}
		
		/**
		 * @brief An helper isn't serializable
		 * @details An helper isn't serializable
		 *
		 * @return void
		 */
		final public function __wakeup() {
			trigger_error('This class is not serializable');
		}
	
	}
	
}
?>