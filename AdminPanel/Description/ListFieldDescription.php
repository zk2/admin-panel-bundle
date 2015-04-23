<?php
namespace Zk2\Bundle\AdminPanelBundle\AdminPanel\Description;

/**
 * AdminPanel ListField
 * 
 * @see AdminPanelController:buildListFields
 */
class ListFieldDescription
{
    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $label
     */
    protected $label;

    /**
     * @var string $alias
     */
    protected $alias;

    /**
     * @var boolean $sort
     */
    protected $sort;

    /**
     * @var string $func
     */
    protected $func;

    /**
     * @var string $filter
     */
    protected $filter;

    /**
     * @var array $options
     */
    protected $options=array();

    /**
     * @var string $method
     */
    protected $method;
    
    /**
     * @var string $autosum
     */
    protected $autosum;
    
    /**
     * @var array $default_options
     */
    protected $default_options = array(
        'sort'    => true,
        'func'    => null,
        'filter'  => null,
        'method'  => null,
        'autosum' => null,
    );
    
    /**
     * Constructor
     * 
     * @param array $array
     */
    public function __construct( array $array )
    {
        if( !$array[0] ) throw new \Exception('ListFieldDescription::name IS NULL');
        $this->name    = $array[0];
        $this->label   = isset( $array[1] ) ? $array[1] : null;
        $this->alias   = isset( $array[2] ) ? $array[2] : null;
        $this->options = ( isset( $array[3] ) and is_array( $array[3] ) )
            ? array_merge( $this->default_options, $array[3] )
            : $this->default_options;
    }

    /**
     *  getName
     *
     *  @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *  getLabel
     *
     *  @return string $label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     *  getAlias
     *
     *  @return string $alias
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     *  getSort
     *
     *  @return boolean $sort
     */
    public function getSort()
    {
        return $this->options['sort'];
    }

    /**
     *  getFunc
     *
     *  @return string $func
     */
    public function getFunc()
    {
        return $this->options['func'];
    }

    /**
     *  getFilter
     *
     *  @return string $filter
     */
    public function getFilter()
    {
        return $this->options['filter'];
    }

    /**
     *  getMethod
     *
     *  @return string $method
     */
    public function getMethod()
    {
        return $this->options['method'];
    }

    /**
     *  setOption
     *
     *  @param string $key
     *  @param string $val
     */
    public function setOption($key,$val)
    {
        $this->options[$key] = $val;
    }

    /**
     *  getOption
     *
     *  @return string
     */
    public function getOption($key)
    {
        return isset( $this->options[$key] ) ? $this->options[$key] : null;
    }

    /**
     *  getAliasDotName
     *
     *  @return string
     */
    public function getAliasDotName()
    {
        if( 'noalias' == $this->alias ) return $this->alias;
        return sprintf( "%s.%s", $this->alias, $this->name );
    }

    /**
     *  getPatternInAutosum
     *
     *  DEPRECATE !!! Use getPatternAggregateSqlFunction()
     *
     *  @return string
     */
    public function getPatternInAutosum()
    {
        if( !$this->options['autosum'] ) return null;
        
        return is_array( $this->options['autosum'] )
            ? current( $this->options['autosum'] )
            : sprintf( "%s.%s", $this->alias, $this->name )
        ;
    }

    /**
     *  getAutosum
     *
     *  DEPRECATE !!! Use getPatternAggregateSqlFunction()
     *
     *  @return 
     */
    public function getAutosum()
    {
        return $this->getPatternAggregateSqlFunction();
    }

    /**
     *  getPatternAggregateSqlFunction
     *
     *  @return array( name,  pattern ) | null
     */
    public function getPatternAggregateSqlFunction()
    {
        if($this->options['autosum']) return $this->options['autosum'];
        if($opt = $this->getOption('aggregate_sql_function'))
        return array('name' => $this->name.'__aggr','func' => $opt);
        return null;
    }

}