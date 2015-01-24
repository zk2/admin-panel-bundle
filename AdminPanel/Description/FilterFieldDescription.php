<?php
namespace Zk2\Bundle\AdminPanelBundle\AdminPanel\Description;

use Zk2\Bundle\AdminPanelBundle\AdminPanel\ConditionOperator;

/**
 * AdminPanel FilterField
 * 
 * @see AdminPanelController:buildFilterFields
 */
class FilterFieldDescription
{
    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $type
     */
    protected $type;

    /**
     * @var string $label
     */
    protected $label;

    /**
     * @var integer $count
     */
    protected $count;

    /**
     * @var string $condition_operator
     */
    protected $condition_operator;

    /**
     * @var array $options
     */
    protected $options=array();
  
    /**
     * Constructor
     * 
     * @param array $array
     */
    public function __construct( $array )
    {
        if( !$array ) throw new \Exception('FilterFieldDescription::name IS NULL');
        
        $this->name  = $array[0];
        $this->type  = isset( $array[1] ) ? $array[1] : 'text_filter';
        $this->label = isset( $array[2] ) ? $array[2] : $array[0];
        $this->count = isset( $array[3] ) ? $array[3] : 1;
        
        if( isset( $array[4] ) )
        {
            if( !ConditionOperator::validate( $array[4] ) )
            {
                throw new \Exception(sprintf("ConditionOperator %s does not exist",$array[1]));
            }
            $this->condition_operator = $array[4];
        }
        else $this->condition_operator = 'full_text';

        $this->options = isset( $array[5] ) ? $array[5] : array();
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
     *  setType
     *
     *  @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     *  getType
     *
     *  @return string $type
     */
    public function getType()
    {
        return $this->type;
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
     *  setCount
     *
     *  @param integer $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     *  getCount
     *
     *  @return integer $count
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     *  setConditionOperator
     *
     *  @param string $condition_operator
     */
    public function setConditionOperator($condition_operator)
    {
        $this->condition_operator = $condition_operator;
    }

    /**
     *  getConditionOperator
     *
     *  @return string $condition_operator
     */
    public function getConditionOperator()
    {
        return $this->condition_operator;
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

}