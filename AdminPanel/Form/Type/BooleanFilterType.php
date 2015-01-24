<?php
namespace Zk\AdminPanelBundle\AdminPanel\Form\Type;

use Zk\AdminPanelBundle\AdminPanel\ConditionOperator;
use Zk\AdminPanelBundle\AdminPanel\Form\Type\BaseFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            'choices' => $options['revert'] ? array('' => '','true' => 'No','false' => 'Yes',)  : array('' => '','true' => 'Yes','false' => 'No',),
	    'attr' => array(
                'class' => 'sf_filter_condition_operator',
            )
        ));
    }

    public function getName()
    {
        return 'boolean_filter';
    }
}