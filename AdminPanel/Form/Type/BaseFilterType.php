<?php
namespace Zk2\Bundle\AdminPanelBundle\AdminPanel\Form\Type;

use Zk2\Bundle\AdminPanelBundle\AdminPanel\ConditionOperator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Base abstract class for filter form field
 */
abstract class BaseFilterType extends AbstractType
{
    const BOOLEAN_FILTER = 'zk2_admin_panel_boolean_filter';
    const CHOICE_FILTER = 'zk2_admin_panel_choice_filter';
    const DATE_FILTER = 'zk2_admin_panel_date_filter';
    const ENTITY_FILTER = 'zk2_admin_panel_entity_filter';
    const TEXT_FILTER = 'zk2_admin_panel_text_filter';
    
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