<?php
namespace Zk2\Bundle\AdminPanelBundle\AdminPanel\Form\Type;

use Zk2\Bundle\AdminPanelBundle\AdminPanel\ConditionOperator;
use Zk2\Bundle\AdminPanelBundle\AdminPanel\Form\Type\BaseFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class for boolean filter form field
 */
class BooleanFilterType extends BaseFilterType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	$builder->add('condition_operator', 'choice',  array(
	    'choices' => array( 'TRUE_FALSE'  => '=' ),
	    'attr' => array(
                'class' => 'sf_filter_condition_operator',
            )
        ));
	
        $builder->add('name','choice',array(
	    'required' => false,
            'choices' => $options['revert']
	        ? array('' => '','true' => 'No','false' => 'Yes',)
		: array('' => '','true' => 'Yes','false' => 'No',),
	    'attr' => array(
                'class' => 'sf_filter_condition_operator',
            )
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return BaseFilterType::BOOLEAN_FILTER;
    }
}