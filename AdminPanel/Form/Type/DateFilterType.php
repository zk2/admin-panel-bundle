<?php
namespace Zk2\Bundle\AdminPanelBundle\AdminPanel\Form\Type;

use Zk2\Bundle\AdminPanelBundle\AdminPanel\ConditionOperator;
use Zk2\Bundle\AdminPanelBundle\AdminPanel\Form\Type\BaseFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class for date filter form field
 */
class DateFilterType extends BaseFilterType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if( $options['level'] )
        {
	    $builder->add('condition_pattern', 'choice',  array(
		'choices' => array(
                    'AND' => 'AND',
                    'OR'  => 'OR',
		),
		'attr' => array(
                    'class' => 'sf_filter_condition_pattern',
                )
            ));
        }
        
        if( $options['condition_operator'] )
        {
	    $builder->add('condition_operator', 'choice',  array(
		'choices' => $options['condition_operator'] ? ConditionOperator::get($options['condition_operator']) : ConditionOperator::get('date_from'),
		'attr' => array(
                    'class' => 'sf_filter_condition_operator',
                )
            ));
        }
	
        $builder->add('name', 'zk2_date_bootstrap', array(
	    'required' => false,
	    'Zk2DateSetting' => array(
	        'filter' => true,
	    ),
	    'attr' => array(
		'class' => 'sf_filter_value sf_filter_text',
		'style' => 'width:70%;',
		'data-index' => $options['data_index'],
	    )
	));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return BaseFilterType::DATE_FILTER;
    }
}