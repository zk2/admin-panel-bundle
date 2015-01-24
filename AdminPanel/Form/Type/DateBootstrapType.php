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
 * Implements the widget type "date" in form template
 *
 */
class DateBootstrapType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaults = array(
            'autoclose'          => 'true',
            'beforeShowDay'      => '$.noop',
            'calendarWeeks'      => 'false',
            'clearBtn'           => 'true',
            'daysOfWeekDisabled' => '[]',
            'endDate'            => 'Infinity',
            'forceParse'         => 'true',
            'format'             => '"yyyy-mm-dd"',
            'keyboardNavigation' => 'true',
            'language'           => '"en"',
            'minViewMode'        => '0',
            'multidate'          => 'false',
            'multidateSeparator' => '","',
            'orientation'        => '"auto"',
            'rtl'                => 'false',
            'startDate'          => '-Infinity',
            'startView'          => '0',
            'todayBtn'           => 'false',
            'todayHighlight'     => 'true',
            'weekStart'          => '1',
	    'filter'             => false,
        );
	
        $resolver
	    ->setDefaults(array(
	        'widget' => 'single_text',
                'Zk2DateSetting' =>  $defaults,
            ))
            ->setNormalizers(array(
                'Zk2DateSetting' => function (Options $options, $configs) use ($defaults) {
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
	    'Zk2DateSetting' =>  $options['Zk2DateSetting'],
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'date';
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zk2_date_bootstrap';
    }
}