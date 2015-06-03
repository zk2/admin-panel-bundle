<?php
namespace Zk2\Bundle\AdminPanelBundle\Twig\Extension;

use Twig_Extension;
use Twig_Filter_Method;
use Twig_Function_Method;
use Twig_Filter_Function;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * AdminPanel Twig Extension
 * 
 * Class that extends the Twig_Extension
 */
class AdminPanelExtension extends Twig_Extension
{
    protected $session,$convert_time_with_timezone;
    
    /**
     * Constructor
     *
     * @param Session   $session    The session
     */
    function __construct(Session $session, $convert_time_with_timezone)
    {
        $this->session = $session;
        $this->convert_time_with_timezone = $convert_time_with_timezone;
    }
    
    /**
     *  @return array
     *  @see \Twig_Extension
     */
    public function getFunctions()
    {
        return array(
            'isNumeric'       => new \Twig_Function_Method($this, 'renderIsNumeric', array('is_safe' => array('html'))),
            'isArray'         => new \Twig_Function_Method($this, 'renderIsArray', array('is_safe' => array('html'))),
            'convertDateTime' => new \Twig_Function_Method($this, 'renderConvertDateTime', array('is_safe' => array('html'))),
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
     *  Datetime with timezone
     *
     *  @param (DateTime | string) $date
     *  @param string $format
     *  @param string $timezone
     *  @return string
     */
    public function renderConvertDateTime($date, $format, $inputTimezone = null, $outputTimezone = null)
    {
        if(!$this->convert_time_with_timezone) return ($date instanceof \DateTime) ? $date->format($format) : null;
        
        if (null === $inputTimezone)
        {
            $inputTimezone = date_default_timezone_get();
        }
        
        if (null === $outputTimezone)
        {
            $outputTimezone = $this->session->get('timezone', date_default_timezone_get());
        }

        if (!$date instanceof \DateTime)
        {
            if(!strtotime($date) and !(ctype_digit((string) $date)))
            {
                return null;
            }
            if (!$inputTimezone instanceof \DateTimeZone)
            {
                $inputTimezone = new \DateTimeZone($inputTimezone);
            }
            
            if (ctype_digit((string) $date))
            {
                $date = new \DateTime('@'.$date, $inputTimezone);
            }
            else
            {
                $date = new \DateTime($date, $inputTimezone);
            }
        }

        if (!$outputTimezone instanceof \DateTimeZone)
        {
            $outputTimezone = new \DateTimeZone($outputTimezone);
        }

        $date->setTimezone($outputTimezone);

        return $date->format($format);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zk2_admin_panel.twig_extension';
    }
}