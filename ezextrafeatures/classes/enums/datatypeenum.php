<?php
namespace extension\ezextrafeatures\classes\enums {
    
    /**
     * @brief Enumeration class for datatype class definition
	 * @details Enumeration class for datatype definition. 
	 * This will permit to specify the type of fields for a DataType 
	 * This class will need to extends <a href="http://php.net/manual/fr/book.spl-types.php">SplEnum</a> soon
	 * 
	 * @author Adrien Loyant <adrien.loyant@te-laval.fr>
	 * 
	 * @date 2012-03-01
	 * @version 1.0.0
	 * @since 1.0.0
	 * @copyright GNU Public License v.2
	 * 
	 * @package extension\ezadvancedautoload\classes\enums
     */
    class datatypeEnum {
        
        /**
         * @brief Default value
		 * @details This is the default value of this enumeration
		 * 
         * @var string
         */
        const __default = static::STRING;
        
        /**
         * @brief Typing an attribute as a string
		 * @details Typing an attribute as a string
		 * 
         * @var string
         */
        const STRING = 'string';
        
        /**
         * @brief Typing an attribute as a varchar
         * @details Typing an attribute as a varchar
         * 
         * @note STRING alias
         *
         * @var string
         */
        const VARCHAR = static::STRING;
        
        /**
         * @brief Typing an attribute as a char
         * @details Typing an attribute as a char
         *
         * @var string
         */
        const CHAR = 'char';
        
        /**
         * @brief Typing an attribute as an hexadecimal value
         * @details Typing an attribute as an hexadecimal value
         *
         * @var string
         */
        const HEXA = 'hex';
        
        /**
         * @brief Typing an attribute as an octal value
         * @details Typing an attribute as an octal value
         *
         * @var string
         */
        const OCTAL = 'octal';
        
        /**
         * @brief Typing an attribute as an int
         * @details Typing an attribute as an int
         *
         * @var string
         */
        const INT = 'int';
        
        /**
         * @brief Typing an attribute as an integer
         * @details Typing an attribute as an integer
         *
         * @note INT alias
         *
         * @var string
         */
        const INTEGER = static::INT;
        
        /**
         * @brief Typing an attribute as a long
         * @details Typing an attribute as a long
         * 
         * @note LONG alias
         *
         * @var string
         */
        const LONG = static::INT;
        
        /**
         * @brief Typing an attribute as a float
         * @details Typing an attribute as a float
         *
         * @var string
         */
        const FLOAT = 'float';
        
        /**
         * @brief Typing an attribute as a double
         * @details Typing an attribute as a double
         * 
         * @note FLOAT alias
         *
         * @var string
         */
        const DOUBLE = static::FLOAT;
        
        /**
         * @brief Typing an attribute as a real
         * @details Typing an attribute as a real
         * 
         * @note FLOAT alias
         *
         * @var string
         */
        const REAL = static::FLOAT;
        
        /**
         * @brief Typing an attribute as a decimal
         * @details Typing an attribute as a decimal
         * 
         * @note FLOAT alias
         *
         * @var string
         */
        const DECIMAL = static::FLOAT;
        
        /**
         * @brief Typing an attribute as a bool
         * @details Typing an attribute as a bool
         *
         * @var string
         */
        const BOOL = 'bool';
        
        /**
         * @brief Typing an attribute as a boolean
         * @details Typing an attribute as a boolean
         * 
         * @note BOOL alias
         *
         * @var string
         */
        const BOOLEAN = static::BOOL;
        
        /**
         * @brief Typing an attribute as an array
         * @details Typing an attribute as an array
         *
         * @var string
         */
        const ARR = 'array';
        
        /**
         * @brief Typing an attribute as an object
         * @details Typing an attribute as an object
         *
         * @var string
         */
        const OBJECT = 'object';
        
        /**
         * @brief Typing an attribute as an email value
         * @details Typing an attribute as a string as an email value
         *
         * @var string
         */
        const EMAIL = 'email';
        
        /**
         * @brief Typing an attribute as an url value
         * @details Typing an attribute as an url value
         *
         * @var string
         */
        const URL = 'url';
        
        /**
         * @brief Typing an attribute as an ip value
         * @details Typing an attribute as an ip value
         *
         * @var string
         */
        const IP = 'ip';
        
    }
    
}

?>