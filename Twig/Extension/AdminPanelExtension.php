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
            'formatDatetime' => new \Twig_Function_Method($this, 'renderFormatDatetime', array('is_safe' => array('html'))),
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
     *  formatDatetime
     *
     *  @param DateTime $date
     *  @param string $timezone
     *  @return DateTime
     */
    public function renderFormatDatetime($date, $format, $timezone = null)
    {
        if (null === $timezone)
        {
            $timezone = $this->container->get('session')->get('timezone', date_default_timezone_get());
        }
 
        if (!$date instanceof \DateTime)
        {
            if (ctype_digit((string) $date))
            {
               $date = new \DateTime('@'.$date);
            }
            else
            {
                return $date;
            }
        }
 
        if (!$timezone instanceof \DateTimeZone)
        {
            $timezone = new \DateTimeZone($timezone);
        }
 
        $date->setTimezone($timezone);
 
        return $date->format($format);
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