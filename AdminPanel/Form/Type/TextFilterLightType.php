<?php
namespace Zk2\Bundle\AdminPanelBundle\AdminPanel\Form\Type;

use Zk2\Bundle\AdminPanelBundle\AdminPanel\ConditionOperator;
use Zk2\Bundle\AdminPanelBundle\AdminPanel\Form\Type\BaseFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class for text filter form field
 */
class TextFilterLightType extends BaseFilterType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder->add('condition_operator', 'hidden',  array('data' => '%%%s%%',));
	
        $builder->add('name', null, array(
	        'required' => false,
	        'attr' => array()
	    ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return BaseFilterType::TEXT_FILTER_LIGHT;
    }
}