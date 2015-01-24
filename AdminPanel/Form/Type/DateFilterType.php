<?php
namespace Zk\AdminPanelBundle\AdminPanel\Form\Type;

use Zk\AdminPanelBundle\AdminPanel\ConditionOperator;
use Zk\AdminPanelBundle\AdminPanel\Form\Type\BaseFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
		'choices' => ConditionOperator::get('date_from'),
		'attr' => array(
                    'class' => 'sf_filter_condition_operator',
                )
            ));
        }
	
        $builder->add('name', 'zk_date_bootstramp', array(
	    'required' => false,
	    'ZkDateSetting' => array(
	        'filter' => true,
	    ),
	    'attr' => array(
		'class' => 'sf_filter_value sf_filter_text',
		'style' => 'width:70%;',
		'data-index' => $options['data_index'],
	    )
	));
    }

    public function getName()
    {
        return 'date_filter';
    }
}