<?php
namespace Zk2\Bundle\AdminPanelBundle\AdminPanel\Form\Type;

use Zk2\Bundle\AdminPanelBundle\AdminPanel\ConditionOperator;
use Zk2\Bundle\AdminPanelBundle\AdminPanel\Form\Type\BaseFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class for entity filter form field
 */
class EntityFilterType extends BaseFilterType
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
	
	$entity_type = $options['entity_type'] ? $options['entity_type'] : 'entity';
	
	$opt = array(
	    'required' => false,
	    'empty_value' => '',
	    'em' => $options['em'],
	    'class' => $options['entity_class'],
            'property' => $options['property'],
	    'attr' => array(
		'class' => 'sf_filter_select',
		'data-index' => $options['data_index'],
	    )
	);
	
	if( $options['sf_query_builder'] )
	{
	    $query = function(EntityRepository $er)  use ($options) {
                return $er->createQueryBuilder( $options['sf_query_builder']['alias'] )
	            ->where( $options['sf_query_builder']['where'] )
                    ->orderBy($options['sf_query_builder']['order_field'], $options['sf_query_builder']['order_type']);
            };
	    $opt['query_builder'] = $query;
	}
	
        $builder->add('name', $entity_type, $opt);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return BaseFilterType::ENTITY_FILTER;
    }
}