<?php
namespace Zk2\Bundle\AdminPanelBundle\Twig\Extension;

use Twig_Extension;
use Twig_Filter_Method;
use Twig_Function_Method;
use Twig_Filter_Function;

/**
 * AdminPanel Twig Extension
 * 
 * Class that extends the Twig_Extension
 */
class AdminPanelExtension extends Twig_Extension
{
    protected $container;
  
    public function __construct($container)
    {
        $this->container = $container;
    }
    
    /**
     *  @return array
     *  @see \Twig_Extension
     */
    public function getFunctions()
    {
        return array(
            'isNumeric'      => new \Twig_Function_Method($this, 'renderIsNumeric', array('is_safe' => array('html'))),
            'isArray'        => new \Twig_Function_Method($this, 'renderIsArray', array('is_safe' => array('html'))),
        );
    }

    /**
     *  Renders is Numeric
     *
     *  @param $item
     *  @return boolean
     */
    public function renderIsNumeric($item)
    {
        return is_numeric($item);
    }

    /**
     *  Renders is array
     *
     *  @param $item
     *  @return boolean
     */
    public function renderIsArray($item)
    {
        return is_array($item);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zk2_admin_panel.twig_extension';
    }
}