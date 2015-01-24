<?php

namespace Zk\AdminPanelBundle\AdminPanel\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;

/**
 * class DateBootstrampType
 * 
 * Implements the widget type "date" in form template
 *
 */
class DateBootstrampType extends AbstractType
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
	    'filter' => false,
        );
	
        $resolver
	->setDefaults(array(
	    'widget' => 'single_text',
            'ZkDateSetting' =>  $defaults,
        ))
        ->setNormalizers(array(
            'ZkDateSetting' => function (Options $options, $configs) use ($defaults) {
                return array_merge($defaults, $configs);
            },
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView( $view, $form, $options );
        
        $view->vars = array_replace($view->vars, array(
	    'ZkDateSetting' =>  $options['ZkDateSetting'],
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
        return 'zk_date_bootstramp';
    }
}