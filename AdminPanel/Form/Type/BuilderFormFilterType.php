<?php
namespace Zk\AdminPanelBundle\AdminPanel\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zk\AdminPanelBundle\AdminPanel\Description\FilterFieldDescription;

class BuilderFormFilterType extends AbstractType
{
    protected $array_fields;
	
    public function __construct( array $array_fields )
    {
	foreach( $array_fields as $field )
        {
            if( !$field instanceof FilterFieldDescription )
            {
                throw new \Exception('BuilderFormFilterType::__construct: Field must be instanceof FilterFieldDescription');
            }
        }
        $this->array_fields = $array_fields;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach( $this->array_fields as $field )
        {
	    $type = $field->getType();
	    $name = $field->getName();
	    
            for( $i = 0; $i < $field->getCount(); $i++ )
            {
		$zk_options = array(
                    'level' => $i,
                    'condition_operator' => $field->getConditionOperator(),
		    'label' => $field->getLabel(),
		    'label_attr' => array('class' => 'filter_label'),
		    'data_index' => $i,
		    'attr' =>  array('data-index' => $i),
                );
		
		if( 'entity_filter' == $type )
		{
		    $zk_options['entity_type'] = $field->getOption('entity_type');
	            $zk_options['entity_class'] = $field->getOption('entity_class');
	            $zk_options['property'] = $field->getOption('property');
		    $zk_options['em'] = $field->getOption('em');
		}
		
		elseif( 'choice_filter' == $type )
		{
		    $zk_options['sf_choice'] = $field->getOption('sf_choice');
		}
		
		elseif( 'boolean_filter' == $type )
		{
		    $zk_options['revert'] = $field->getOption('revert');
		}
		
		if( $qb = $field->getOption('sf_query_builder') )
		{
		    $zk_options['sf_query_builder'] = $qb;
		}
		
                $builder->add(
                    $name.$i,
                    $type,
                    $zk_options
                );
            }
        }
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }
    
    public function getName()
    {
        return 'admin_filter';
    }
}
