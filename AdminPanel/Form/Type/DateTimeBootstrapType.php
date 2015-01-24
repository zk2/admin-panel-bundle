<?php

namespace Zk2\Bundle\AdminPanelBundle\AdminPanel\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;

/**
 * class DateBootstrapType
 * 
 * Implements the widget type "datetime" in form template
 */
class DateTimeBootstrapType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaults = array(
	    'pickDate' => 'true',              //en/disables the date picker
            'pickTime' => 'false',             //en/disables the time picker
            'useMinutes' => 'false',           //en/disables the minutes picker
            'useSeconds' => 'false',           //en/disables the seconds picker
            'useCurrent' => 'true',            //when true, picker will set the value to the current date/time     
            'minuteStepping' => '1',           //set the minute stepping
            'minDate' => '1/1/1900',           //set a minimum date
            'maxDate' => '""',                 //set a maximum date
            'showToday' => 'false',             //shows the today indicator
            'language' => 'en',                //sets language locale
            'defaultDate' => '""',             //sets a default date, accepts js dates, strings and moment objects
            'disabledDates' => '[]',           //an array of dates that cannot be selected
            'enabledDates' => '[]',            //an array of dates that can be selected
            'useStrict' => 'false',             //use "strict" when validating dates  
            'sideBySide' => 'false',           //show the date and time picker side by side
            'daysOfWeekDisabled' => '[]',      //for example use daysOfWeekDisabled: [0,6] to disable weekends
	    'dateFormat' => 'YYYY-MM-DD',
	    'filter' => false,
        );
        $resolver
	    ->setDefaults(array(
	        'widget' => 'single_text',
                'Zk2DateTimeSetting' =>  $defaults,
            ))
            ->setNormalizers(array(
                'Zk2DateTimeSetting' => function (Options $options, $configs) use ($defaults) {
                    return array_merge($defaults, $configs);
                },
            ))
	;
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView( $view, $form, $options );
        
        $view->vars = array_replace($view->vars, array(
	    'Zk2DateTimeSetting' =>  $options['Zk2DateTimeSetting'],
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'datetime';
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zk2_date_time_bootstrap';
    }
}