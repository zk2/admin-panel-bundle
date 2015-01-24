<?php
namespace Zk\AdminPanelBundle\AdminPanel\Form\Type;

use Zk\AdminPanelBundle\AdminPanel\ConditionOperator;
use Zk\AdminPanelBundle\AdminPanel\Form\Type\BaseFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ChoiceFilterType extends BaseFilterType
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
                    'OR'  => 'OR',
                    'AND' => 'AND',
		),
		'attr' => array(
                    'class' => 'sf_filter_condition_pattern',
                )
            ));
        }
        
        if( $options['condition_operator'] )
        {
	    $builder->add('condition_operator', 'choice',  array(
		'choices' => ConditionOperator::get($options['condition_operator']),
		'attr' => array(
                    'class' => 'sf_filter_condition_operator',
                )
            ));
        }
	
	$opt = array(
	    'required' => false,
	    'empty_value' => '',
	    'choices' => $options['sf_choice'],
	    'attr' => array(
		'class' => 'sf_filter_select',
		'data-index' => $options['data_index'],
	    )
	);
	
        $builder->add( 'name', 'choice', $opt );
    }

    public function getName()
    {
        return 'choice_filter';
    }
}