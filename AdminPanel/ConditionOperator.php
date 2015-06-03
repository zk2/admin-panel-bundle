<?php

namespace Zk2\Bundle\AdminPanelBundle\AdminPanel;

/**
 * The class contains static methods, 
 * Returning SQL operator from mask
 */
class ConditionOperator
{
    const FULL_TEXT  = 'full_text';
    const LIGHT_TEXT = 'light_text';
    const SMAL_TEXT  = 'smal_text';
    const FULL_INT   = 'full_int';
    const SMAL_INT   = 'smal_int';
    const MIN_INT    = 'min_int';
    const LIKE       = 'like';
    const EQ         = 'eq';
    const YES_NO     = 'yes_no';
    const DATE_FROM  = 'date_from';
    const DATE_TO    = 'date_to';
    const MIN_MAX    = 'min_max';
    const MEDIUM_INT = 'medium_int';
    
    /**
     * validate condition_operator
     *
     * @return boolean
     */
    static public function validate( $condition_operator )
    {
        $refl = new \ReflectionClass('Zk2\Bundle\AdminPanelBundle\AdminPanel\ConditionOperator');
        foreach( $refl->getConstants() as $val )
        {
            if( $condition_operator == $val )
            return true;
        }
        return false;
    }
    
    /**
     * Retruns an array of available conditions patterns.
     *
     * @return array
     */
    static public function get( $condition_operator )
    {
	switch( $condition_operator )
	{
	    case self::FULL_TEXT: return array(
                '%%%s%%'      => '%LIKE%',
                '%s'          => '=',
                'x%s'         => '<>',
                '%s%%'        => 'LIKE%',
                '%%%s'        => '%LIKE',
		        'x%%%s%%'     => 'NOT %LIKE%',
                'x%s%%'       => 'NOT LIKE%',
                'x%%%s'       => 'NOT %LIKE',
                'IS NULL'     => 'IS EMPTY',
                'IS NOT NULL' => 'IS NOT EMPTY',
	    );
	    case self::LIGHT_TEXT: return array(
                '%%%s%%'      => '%LIKE%',
                '%s'          => '=',
                'x%s'         => '<>',
                '%s%%'        => 'LIKE%',
                '%%%s'        => '%LIKE',
		        'x%%%s%%'     => 'NOT %LIKE%',
                'x%s%%'       => 'NOT LIKE%',
                'x%%%s'       => 'NOT %LIKE',
	    );
	    case self::SMAL_TEXT: return array(
                '%%%s%%'      => '%LIKE%',
                '%s'          => '=',
                'x%s'         => '<>',
                'IS NULL'     => 'IS EMPTY',
                'IS NOT NULL' => 'IS NOT EMPTY',
	    );
	    case self::MIN_INT: return array(
                '%s'          => '=',
                'x%s'         => '<>',
	    );
	    case self::FULL_INT: return array(
                '%s'          => '=',
                'x%s'         => '<>',
		        'xx%s'        => '>',
		        'xxx%s'       => '<',
		        'xxxx%s'      => '>=',
		        'xxxxx%s'     => '<=',
                'IS NULL'     => 'IS EMPTY',
                'IS NOT NULL' => 'IS NOT EMPTY',
	    );
	    case self::MEDIUM_INT: return array(
                '%s'          => '=',
                'x%s'         => '<>',
		        'xxxx%s'      => '>=',
		        'xx%s'        => '>',
		        'xxxxx%s'     => '<=',
		        'xxx%s'       => '<',
	    );
	    case self::SMAL_INT: return array(
                '%s'          => '=',
                'x%s'         => '<>',
                'IS NULL'     => 'IS EMPTY',
                'IS NOT NULL' => 'IS NOT EMPTY',
	    );
	    case self::LIKE: return array(
                '%%%s%%'      => '%LIKE%',
	    );
	    case self::EQ: return array(
                '%s'          => '=',
	    );
	    case self::YES_NO: return array(
                'TRUE_FALSE'  => '=',
	    );
	    case self::DATE_FROM: return array(
		        'xxxx%s'      => '>=',
		        'xx%s'        => '>',
		        'xxxxx%s'     => '<=',
		        'xxx%s'       => '<',
                '%s'          => '=',
                'x%s'         => '<>',
	    );
	    case self::DATE_TO: return array(
		        'xxxxx%s'     => '<=',
		        'xxx%s'       => '<',
		        'xxxx%s'      => '>=',
		        'xx%s'        => '>',
                '%s'          => '=',
                'x%s'         => '<>',
	    );
	    case self::MIN_MAX: return array(
		        'xx%s'        => '>',
		        'xxxxx%s'     => '<=',
	    );
	}
        return array();
    }
    
    /**
     * Retruns an array of available conditions patterns.
     *
     * @return string
     */
    static public function get2( $condition_operator )
    {
	switch( $condition_operator )
	{
	    case "%s"     : return "='|||'";
	    case "x%s"    : return "<>'|||'";
	    case "xx%s"   : return ">'|||'";
	    case "xxx%s"  : return "<'|||'";
	    case "xxxx%s" : return ">='|||'";
	    case "xxxxx%s": return "<='|||'";
	    case "%%%s%%" : return "LIKE '%|||%'";
	    case "%s%%"   : return "LIKE '|||%'";
	    case "%%%s"   : return "LIKE '%|||'";
	    case "x%%%s%%": return "NOT LIKE '%|||%'";
	    case "x%s%%"  : return "NOT LIKE '|||%'";
	    case "x%%%s"  : return "NOT LIKE '%|||'";
	}
	return null;
    }

}
