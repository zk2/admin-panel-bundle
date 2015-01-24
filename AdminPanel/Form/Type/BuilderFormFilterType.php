<?php
namespace Zk2\Bundle\AdminPanelBundle\AdminPanel\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zk2\Bundle\AdminPanelBundle\AdminPanel\Description\FilterFieldDescription;
use Zk2\Bundle\AdminPanelBundle\AdminPanel\Form\Type\BaseFilterType;

/**
 * Class for Zk2AdminPanel Form Filter
 */
class BuilderFormFilterType extends AbstractType
{
    protected $array_fields;

    /**
     * Constructor
     *
     * @param array FilterFieldDescription
     */
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
		$zk2_options = array(
                    'level' => $i,
                    'condition_operator' => $field->getConditionOperator(),
		    'label' => $field->getLabel(),
		    'label_attr' => array('class' => 'filter_label'),
		    'data_index' => $i,
		    'attr' =>  array('data-index' => $i),
                );
		
		if( BaseFilterType::ENTITY_FILTER == $type )
		{
		    $zk2_options['entity_type'] = $field->getOption('entity_type');
	            $zk2_options['entity_class'] = $field->getOption('entity_class');
	            $zk2_options['property'] = $field->getOption('property');
		    $zk2_options['em'] = $field->getOption('em');
		}
		elseif( BaseFilterType::CHOICE_FILTER == $type )
		{
		    $zk2_options['sf_choice'] = $field->getOption('sf_choice');
		}
		elseif( BaseFilterType::BOOLEAN_FILTER == $type )
		{
		    $zk2_options['revert'] = $field->getOption('revert');
		}
		
		if( $qb = $field->getOption('sf_query_builder') )
		{
		    $zk2_options['sf_query_builder'] = $qb;
		}
		
                $builder->add(
                    $name.$i,
                    $type,
                    $zk2_options
                );
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zk2_admin_panel_form_filter';
    }
}
