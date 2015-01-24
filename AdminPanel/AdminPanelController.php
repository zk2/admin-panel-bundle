<?php

namespace Zk2\Bundle\AdminPanelBundle\AdminPanel;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Zk2\Bundle\AdminPanelBundle\AdminPanel\Description\ListFieldDescription;
use Zk2\Bundle\AdminPanelBundle\AdminPanel\Description\FilterFieldDescription;
use Zk2\Bundle\AdminPanelBundle\AdminPanel\Form\Type\BuilderFormFilterType;

/**
 * Class AdminPanelController
 * 
 * Generate admin's view
 * Class allows you to quickly generate administrative interface to a tabular view "List",
 * broken down by pages, sorts and filters on all fields
 *
 * @since       1.0
 */
abstract class AdminPanelController extends Controller
{
    /**
     * @var string $class (current class)
     */
    protected $class;
    
    /**
     * @var string $alias (current alias)
     */
    protected $alias;
    
    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    protected $query;
    
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    /**
     * @var $em_name
     */
    protected $em_name;
    
    /**
     * @var \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    protected $paginator;
    
    /**
     * @var array $list_fields
     */
    protected $list_fields=array();
    
    /**
     * @var array $filter_fields
     */
    protected $filter_fields=array();
    
    /**
     * @var \Symfony\Component\Form\Form
     */
    protected $filter_form;
    
    /**
     * @var boolean $autosum
     */
    protected $autosum=false;
    
    /**
     * Constructor
     * 
     * @param string $class
     * @param string $alias
     * @param string $em_name='default'
     */
    public function __construct( $class, $alias, $em_name='default' )
    {
        $this->class = $class;
        $this->alias = $alias;
        $this->em_name = $em_name;
    }
    
    /**
     * Get current class
     * 
     * @return string current class name
     */
    protected function getClass()
    {
        return $this->class;
    }
    
    /**
     * Get EntityManager
     * 
     * @return EntityManager
     */
    protected function EntityManager()
    {
        return $this->em = $this->get('doctrine')->getManager( $this->em_name );
    }
    
    /**
     * Get EM (link Get EntityManager)
     * 
     * @return $this
     */
    protected function getEm()
    {
        if( !$this->em ) $this->em = $this->get('doctrine')->getManager( $this->em_name );
        return $this;
    }
    
    /**
     * Check privileges
     *
     * @param array|string $roles
     * @return boolean
     */
    protected function isZk2Granded( $roles )
    {
        if( !is_array( $roles ) ) $roles = array( $roles );
        if( $this->container->getParameter('zk2_admin_panel.check_flag_super_admin') )
        {
            if( $this->get('security.context')->getToken()->getUser()->flagSuperAdmin() ) return true;
        }
        return $this->get('security.context')->isGranted($roles);
    }
    
    /**
     * buildQuery
     *
     * Building a query without conditions
     * 
     * @return $this
     */
    protected function buildQuery()
    {
        $this->query =
        $this->em->getRepository($this->class)
        ->createQueryBuilder($this->alias);
        return $this;
    }
    
    /**
     * Get Query
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getQuery()
    {
        if( !$this->query ) $this->buildQuery();
        return $this->query;
    }
    
    /**
     * Is Reset
     *
     * Check whether the event to reset all filters
     * If true - remove sessipn variable this filter
     * 
     * @return boolean
     */
    protected function isReset()
    {
        if($this->get('request')->query->get('_reset'))
        {
            $this->get('session')->remove( '_filter_'.$this->get('request')->get('_route') );
            $this->get('session')->remove( '_pager_'.$this->get('request')->get('_route') );
            return true;
        }
        return false;
    }
    
    /**
     * checkFilters
     * 
     * Build filters form
     * If the method POST filters are constructed and written to the session
     * else if in session is a form of filter-it is Otherwise,
     * if there is a default filters-they are  
     * 
     * @param array $default (stand default filter (option))
     * @return 
     */
    protected function checkFilters($default=array())
    {
        if( $this->filter_fields )
        {
            $this->filter_form = $this->createForm( new BuilderFormFilterType( $this->filter_fields ) );
            
            if( count( $default ) )
            {
                $this->filter_form->setData( $default );
            }
            
            if ( $this->get('request')->getMethod() == 'POST' )
            {
                $this->get('session')->remove( '_filter_'.$this->get('request')->get('_route') );
                $this->get('session')->remove( '_pager_'.$this->get('request')->get('_route') );
                
                $this->filter_form->bind( $this->get('request') );
                
                $this->get('zk2_admin_panel.query_builder')->buildQuery( $this->filter_form, $this->query );
                
                $filterService = $this->get('zk2_admin_panel.form_filter_session');
                $filterService->serialize( $this->filter_form->getData(),'_filter_'.$this->get('request')->get('_route') );
            }
            elseif( $this->get('session')->has( '_filter_'.$this->get('request')->get('_route') ) )
            {
                $filterService = $this->get('zk2_admin_panel.form_filter_session');
                $data = $filterService->unserialize( '_filter_'.$this->get('request')->get('_route'), $this->em_name );
                
                $this->filter_form->setData( $data );
                $this->get('zk2_admin_panel.query_builder')->buildQuery( $this->filter_form, $this->query );
            }
            elseif( count($default) )
            {
                $this->get('zk2_admin_panel.query_builder')->buildQuery( $this->filter_form, $this->query );
            }
        }
    }
    
    /**
     * initAutosum
     * 
     * Initialization AutoSum
     *
     * @see getSumColumns
     * 
     */
    protected function initAutosum()
    {
        $this->autosum = true;
    }
    
    /**
     * getSumColumns
     * 
     * The total sum of the values ​​for the selected columns
     * 
     * @return 
     */
    protected function getSumColumns()
    {
        if( $this->autosum )
        {
            $select = '';
            
            foreach( $this->list_fields as $field )
            {
                if( $field->getAutosum() )
                {
                    $select .=
                    sprintf("SUM(%s) AS %s,", $field->getPatternInAutosum(),$field->getAutosum());
                }
            }
            $select = trim($select,',');
            
            if( $select )
            {
                $q = strstr( $this->query->getQuery()->getDql(), " FROM " );
                $q = "SELECT ".$select.$q;
                
                $sumQuery = $this->em->createQuery($q)
                ->setParameters($this->query->getQuery()->getParameters())
                ->setMaxResults(1);
                
                try{
                    return $sumQuery->getSingleResult();
                }
                catch (\Doctrine\Orm\NoResultException $e){
                    return null;
                }
            }
        }
        return null;
    }
    
    /**
     * getViewFiltersForm
     * 
     * @return \Symfony\Component\Form\Form::createView or false
     */
    protected function getViewFiltersForm()
    {
        if( $this->filter_fields )
        {
            return $this->filter_form->createView();
        }
        return false;
    }
    
    /**
     * Get Paginator
     * 
     * @param integer $limit ( limit per page )
     * @param array $options
     * @return \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    protected function getPaginator( $limit=30, $options=array() )
    {
        $page = 1;
        
        if( $this->get('request')->query->has('page') )
        {
            $page = $this->get('request')->query->get('page');
            $this->get('session')->set( '_pager_'.$this->get('request')->get('_route'), $page );
        }
        elseif( $this->get('session')->has( '_pager_'.$this->get('request')->get('_route') ) )
        {
            $page = $this->get('session')->get( '_pager_'.$this->get('request')->get('_route') );
        }
        
        $this->paginator = $this->get('knp_paginator');
        
        $pagination = $this->paginator->paginate(
            $this->query,
            $this->get('request')->query->get('page', $page), 
            $limit,
            $options
        );
        $pagination->setTemplate( $this->container->getParameter( 'zk2_admin_panel.pagination_template' ) );
        $pagination->setSortableTemplate( $this->container->getParameter( 'zk2_admin_panel.sortable_template' ) );
        return compact( 'pagination' );
    }
    
    /**
     * addInList
     *
     * Method to add an array of items in the list
     *
     * @param array $field ( @see Zk2\Bundle\AdminPanelBundle\AdminPanel\Description\ListFieldDescription )
     * @return $this
     */
    protected function addInList( array $field )
    {
        $this->list_fields[] = new ListFieldDescription( $field );
        return $this;
    }
    
    /**
     * addInFilter
     *
     * Method to add an array of items in the filter
     *
     * @param array $field ( @see Zk2\Bundle\AdminPanelBundle\AdminPanel\Description\FilterFieldDescription )
     * @return $this
     */
    protected function addInFilter( array $field )
    {
        $this->filter_fields[$field[0]] = new FilterFieldDescription( $field );
        return $this;
    }
    
    /**
     * Get ListFields
     * 
     * @return array list_fields
     */
    public function getListFields()
    {
        return $this->list_fields;
    }
    
    /**
     * Get FilterFields
     * 
     * @return array filter_fields
     */
    public function getFilterFields()
    {
        return $this->filter_fields;
    }
    
    /**
     * Tranclation
     * 
     * @param string $word
     * @param string $domain
     * @param array $array
     *
     * @return string
     */
    protected function trans( $word, $domain='messages', $array=array() )
    {
        return $this->get('translator')->trans($word, $array, $domain);
    }
    
    /**
     * @abstract buildListFields
     *
     * @code
     *     ->addInList(array(
     *         'name',
     *         $this->trans('form.name','FOSUserBundle'),
     *         'u',
     *         array(
     *             'sort'    => true,
     *             'func'    => null,
     *             'filter'  => null,
     *             'method'  => null,
     *             'autosum' => null,
     *             'style'   => 'text-align:center',
     *             'link_id' => 'zk2_admin_user_edit',
     *         )
     *    ))
     * @endcode
     */
    abstract function buildListFields();
    
    /**
     * buildFilterFields
     *
     * @code
     *     ->addInFilter(array(
     *         'u_name',
     *         'text_filter',
     *         $this->trans('form.name','FOSUserBundle'),
     *         5,
     *         'light_text'
     *    ))
     * @endcode
     */
    protected function buildFilterFields(){return;}
}