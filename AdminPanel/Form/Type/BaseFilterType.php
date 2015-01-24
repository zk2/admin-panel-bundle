<?php
namespace Zk\AdminPanelBundle\AdminPanel\Form\Type;

use Zk\AdminPanelBundle\AdminPanel\ConditionOperator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class BaseFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    function buildForm(FormBuilderInterface $builder, array $options){}
    
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'condition_operator' => null,
            'level' => null,
            'label' => null,
            'data_index' => null,
	    'entity_type' => null,
	    'entity_class' => null,
	    'property' => null,
	    'em' => 'default',
	    'sf_choice' => null,
	    'sf_query_builder' => null,
	    'revert' => false,
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName(){}
}