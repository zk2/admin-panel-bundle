<?php
namespace Zk2\Bundle\AdminPanelBundle\AdminPanel;

use Symfony\Component\Form\Form;
use Doctrine\ORM\QueryBuilder as BaseQueryBuilder;

/**
* Build a query from a given form object,
* we basically add conditions to the Doctrine query builder.
*/
class QueryBuilder
{
    protected $parameters=array();
    
    /**
     * Build a filter query.
     *
     * @param \Symfony\Component\Form\Form $form
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function buildQuery( Form $form, BaseQueryBuilder $queryBuilder )
    {
        $group_child = $this->groupChild( $form );
        
        foreach ( $group_child as $field => $child )
        {
            $this->applyFilter( $queryBuilder, $child, $field );
        }
        return $queryBuilder->setParameters($this->parameters);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function groupChild( Form $form )
    {
        $group_child = array();
        
        foreach ( $form->all() as $child )
        {
            $group_child[substr($child->getName(),0,-1)][] = $child;
        }
        return $group_child;
    }

    /**
     * {@inheritdoc}
     */
    protected function applyFilter( BaseQueryBuilder $queryBuilder, $form, $field )
    {
	$array = explode('_',$field);
	$alias = $array[0];
	unset($array[0]);
	$field = $field = implode('_',$array);
        
        $condition = '';
        
        foreach( $form as $i => $child )
        {
            $or_and = ( $child->has('condition_pattern') ) ? $child->get('condition_pattern')->getData() : ' ';
        
            $paramName = sprintf( '%s_%s_param_%s', $alias, $field, $i );
            
            $get_name = $child->get('name')->getData() instanceof \DateTime
                ? $child->get('name')->getData()->format('Y-m-d')
                : $child->get('name')->getData();
                    
            if( ( $child->has('name') and trim( (string)$get_name ) != '' )
		or ( $child->has('condition_operator') and in_array($child->get('condition_operator')->getData(),array('IS NULL','IS NOT NULL'))) )
            {
		if(in_array($child->get('condition_operator')->getData(),array('IS NULL','IS NOT NULL')))
		{
                    $condition .= sprintf('%s ( %s.%s %s ',
                        $or_and,
			$alias,
                        $field,
                        $child->get('condition_operator')->getData()
		    );
		    $child->get('condition_operator')->getData() == 'IS NULL' ?
		    $condition .= sprintf("OR %s.%s = '' ) ",$alias,$field) :
		    $condition .= sprintf("AND %s.%s <> '' ) ",$alias,$field);
		}
		elseif($child->get('condition_operator')->getData() == 'TRUE_FALSE')
		{
                    $condition .= sprintf('%s %s.%s = :%s ',
                        $or_and,
			$alias,
                        $field,
                        $paramName
                    );
		    $this->parameters[$paramName] = $get_name == 'true' ? 1 : 0;
		}
                else
                {
                    $value = sprintf( str_replace('x','',$child->get('condition_operator')->getData()), (string)$get_name );
                    
	            switch ( $child->get('condition_operator')->getData() )
	            {
		        case '%s'     : $operator = '='; break;
		        case 'x%s'    : $operator = '<>'; break;
		        case 'xx%s'   : $operator = '>'; break;
		        case 'xxx%s'  : $operator = '<'; break;
		        case 'xxxx%s' : $operator = '>='; break;
		        case 'xxxxx%s': $operator = '<='; break;
		        case '%%%s%%' : $operator = 'LIKE'; break;
		        case '%s%%'   : $operator = 'LIKE'; break;
		        case '%%%s'   : $operator = 'LIKE'; break;
		        case 'x%%%s%%': $operator = 'NOT LIKE'; break;
		        case 'x%s%%'  : $operator = 'NOT LIKE'; break;
		        case 'x%%%s'  : $operator = 'NOT LIKE'; break;
	            }
            
	    
                    $condition .= sprintf('%s %s.%s %s :%s ',
                        $or_and,
		        $alias,
                        $field,
                        $operator,
                        $paramName
                    );
	            $this->parameters[$paramName] = $value;
                }
            }
        }
        
	$condition = trim($condition);
	$condition = trim($condition,'AND');
	$condition = trim($condition,'OR');
        
        if($condition)
	{
            $queryBuilder->andWhere($condition);
	}
    }

}