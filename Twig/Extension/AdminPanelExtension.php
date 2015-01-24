<?php
namespace Zk\AdminPanelBundle\Twig\Extension;

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
    /**
     * \@var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;
    
    /**
     * Constructor
     * 
     * @param Container $container
     */
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
            'wordPreview'    => new \Twig_Function_Method($this, 'renderWordPreview', array('is_safe' => array('html'))),
            'textPreview'    => new \Twig_Function_Method($this, 'renderTextPreview', array('is_safe' => array('html'))),
            'isArray'        => new \Twig_Function_Method($this, 'renderIsArray', array('is_safe' => array('html'))),
            'isObject'       => new \Twig_Function_Method($this, 'renderIsObject', array('is_safe' => array('html'))),
            'isNumeric'      => new \Twig_Function_Method($this, 'renderIsNumeric', array('is_safe' => array('html'))),
            'inArray'        => new \Twig_Function_Method($this, 'renderInArray', array('is_safe' => array('html'))),
            'zkMatches'      => new \Twig_Function_Method($this, 'renderMatches', array('is_safe' => array('html'))),
            'formatDatetime' => new \Twig_Function_Method($this, 'renderFormatDatetime', array('is_safe' => array('html'))),
            'zkPregMatch'    => new \Twig_Function_Method($this, 'renderZkPregMatch', array('is_safe' => array('html'))),
            'zkTranschoice'  => new \Twig_Function_Method($this, 'renderZkTranschoice', array('is_safe' => array('html'))),
        );
    }

    /**
     *  Renders Word Preview
     *
     *  @param string $text
     *  @param integer $count_synbols
     *  @return string or false
     */
    public function renderWordPreview($text, $count_synbols=10)
    {
        if( strlen($text) > $count_synbols )
        {
            $text = substr ( $text, 0, $count_synbols ).'...';
        }
        return $text;
    }
    
    /**
     *  Renders Text Preview
     *
     *  @param string $text
     *  @param integer $count_words
     *  @return string or false
     */
    public function renderTextPreview($text, $count_words=20)
    {
        $text = strip_tags( $text,'<a><img>' );
        $words = explode(' ', $text);
        $text = '';
        $cou = 0;
        $is_big = false;
        
        foreach ( $words as $word )
        {
            if( $word )
            {
                if( $cou <= $count_words )
                {
                    $text .= ' '.$word;
                    $cou++;
                }
                else
                {
                    $text .= '...';
                    $is_big = true;
                    break;
                }
            }
        }
        return $is_big ? $text : false;
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
     *  Renders in array
     *
     *  @param 
     *  @return boolean
     */
    public function renderInArray($value,$array)
    {
        return in_array($value,$array);
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
     *  Renders is object
     *
     *  @param $item
     *  @return boolean
     */
    public function renderIsObject($item)
    {
        return is_object($item);
    }
    
    /**
     *  Twig preg_match
     *
     *  @param $item
     *  @param $pattern
     *  @return boolean
     */
    public function renderMatches($pattern,$item)
    {
        return strpos($item,$pattern) !== false;
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
        /*if (null === $timezone)
        {
            $timezone = $this->container->get('session')->get('timezone', 'UTC');;
        }*/
 
        if (!$date instanceof \DateTime)
        {
            if (ctype_digit((string) $date))
            {
               $date = new \DateTime('@'.$date);
            }
            else
            {
                return null;
                $date = new \DateTime($date);
            }
        }
 
        /*if (!$timezone instanceof \DateTimeZone)
        {
            $timezone = new \DateTimeZone($timezone);
        }
 
        $date->setTimezone($timezone);*/
 
        return $date->format($format);
    }
    
    
    /**
     *  ZkPregMatch
     *
     *  @param string $pattern
     *  @param string $subject
     *  @return boolean
     */
    public function renderZkPregMatch( $pattern, $subject )
    {
        return preg_match( $pattern, $subject );
    }
    
    
    /**
     *  ZkTranschoice
     *
     *  @param integer $int
     *  @param array $variantes
     *  @return string
     */
    public function renderZkTranschoice( $int, array $variantes )
    {
        $key = (($int % 10 == 1) and ($int % 100 != 11)) ? 0
            : ((($int % 10 >= 2) and ($int % 10 <= 4) and
            (($int % 100 < 10) or ($int % 100 >= 20))) ? 1 : 2);
        return $variantes[$key];
    }

###########################################################################################3
    
    /**
     *  @return array
     *  @see \Twig_Extension
     */
    public function getFilters()
    {
        return array(
            'newStr'         => new \Twig_Filter_Method($this, 'newStrFilter'),
            'strExplode'     => new \Twig_Filter_Method($this, 'strExplodeFilter'),
            'var_dump'       => new \Twig_Filter_Function('var_dump'),
            'print_r'        => new \Twig_Filter_Method($this, 'printRFilter'),
            'zmd5'           => new \Twig_Filter_Method($this, 'zmd5Filter'),
            'in_string'      => new \Twig_Filter_Method($this, 'inStringFilter'),
            'int_to_time'    => new \Twig_Filter_Method($this, 'intToTimeFilter'),
            'int_to_date'    => new \Twig_Filter_Method($this, 'intToDateFilter'),
        );
    }
    
    /**
     *  newStrFilter
     *
     *  @param string $str
     *
     *  @return str_replace(', ','<br>',$str)
     */
    public function newStrFilter($str)
    {         
        return str_replace(', ','<br>',$str);
    }
    
    /**
     *  strExplodeFilter
     *
     *  @param array $array
     *
     *  @return string
     */
    public function strExplodeFilter($array)
    {
        $str = "";
        foreach($array as $value)
        {
            $i = 0;
            foreach($value as $key => $val)
            {
                $str .= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$i).$val.'<br>';
                $i++;
            }
        }
        return $str;
    }

    /**
     *  printRFilter
     *
     *  @param string $arr
     *
     *  @return print_r($arr)
     */
    public function printRFilter($arr)
    {         
        return print_r($arr);
    }

    /**
     *  printRFilter
     *
     *  @param string $arr
     *
     *  @return print_r($arr)
     */
    public function zmd5Filter($value)
    {
        $h = $this->container->getParameter('secret');
        return md5($h.$value.$h);
    }
    
    /**
     *  inStringFilter
     *
     *  @param string $text
     *
     *  @return $text в одну строку
     */
    public function inStringFilter($text)
    {
        $text = preg_replace('|\s+|', ' ', $text);
        return $text;
    }

    /**
     *  intToTimeFilter
     *
     *  @param integer $int
     *
     *  @return string
     */
    public function intToTimeFilter($int)
    {         
        $date = new \DateTime(date('Y-m-d 00:00:00'));
        $date->modify($int.' second');
        return $date->format('H:i');
    }

    /**
     *  intToDateFilter
     *
     *  @param integer $int
     *
     *  @return string
     */
    public function intToDateFilter($int)
    {         
        return date('Y-m-d H:i',$int);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zk_admin_panel.twig_extension';
    }
}