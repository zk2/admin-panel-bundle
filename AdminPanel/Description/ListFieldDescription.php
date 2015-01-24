<?php
namespace Zk\AdminPanelBundle\AdminPanel\Description;


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
     *  Constructor
     */
    public function __construct( array $array )
    {
        if( !$array[0] ) throw new \Exception('ListFieldDescription::name IS NULL');
        $this->name    = $array[0];
        $this->label   = isset( $array[1] ) ? $array[1] : null;
        $this->alias   = isset( $array[2] ) ? $array[2] : null;
        $this->sort    = isset( $array[3] ) ? $array[3] : true;
        $this->func    = isset( $array[4] ) ? $array[4] : null;
        $this->filter  = isset( $array[5] ) ? $array[5] : null;
        $this->options = isset( $array[6] ) ? $array[6] : null;
        $this->method  = isset( $array[7] ) ? $array[7] : null;
        $this->autosum = isset( $array[8] ) ? $array[8] : null;
    }

    /**
     *  setName
     *
     *  @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     *  setLabel
     *
     *  @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
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
     *  setAlias
     *
     *  @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
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
     *  setSort
     *
     *  @param boolean $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     *  getSort
     *
     *  @return boolean $sort
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     *  setFunc
     *
     *  @param string $func
     */
    public function setFunc($func)
    {
        $this->func = $func;
    }

    /**
     *  getName
     *
     *  @return string $func
     */
    public function getFunc()
    {
        return $this->func;
    }

    /**
     *  setFilter
     *
     *  @param string $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    /**
     *  getFilter
     *
     *  @return string $filter
     */
    public function getFilter()
    {
        return $this->filter;
    }
    /**
     *  setMethod
     *
     *  @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     *  getMethod
     *
     *  @return string $method
     */
    public function getMethod()
    {
        return $this->method;
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
     *  getAliasAndName
     *
     *  @return string
     */
    public function getAliasAndName()
    {
        return sprintf( "%s.%s", $this->alias, $this->name );
    }

    /**
     *  getPatternInAutosum
     *
     *  @return string
     */
    public function getPatternInAutosum()
    {
        if( !$this->autosum ) return null;
        return is_array($this->autosum) ? current($this->autosum) : sprintf( "%s.%s", $this->alias, $this->name );
    }

    /**
     *  getAutosum
     *
     *  @return string $autosum
     */
    public function getAutosum()
    {
        return $this->autosum;
    }

}