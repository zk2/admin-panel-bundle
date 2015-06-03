<?php
namespace Zk2\Bundle\AdminPanelBundle\AdminPanel\Form\Type;

use Zk2\Bundle\AdminPanelBundle\AdminPanel\ConditionOperator;
use Zk2\Bundle\AdminPanelBundle\AdminPanel\Form\Type\BaseFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class for choice filter form field
 */
class ChoiceFilterLightType extends BaseFilterType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder->add('condition_operator', 'hidden',  array('data' => '%s',));
	
	    $opt = array(
	        'required' => false,
	        'empty_value' => '',
	        'choices' => $options['sf_choice'],
	        'attr' => array()
	    );
	
        $builder->add( 'name', 'choice', $opt );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return BaseFilterType::CHOICE_FILTER_LIGHT;
    }
}