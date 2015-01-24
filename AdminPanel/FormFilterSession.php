<?php
namespace Zk\AdminPanelBundle\AdminPanel;

class FormFilterSession
{
    protected $container;
  
    public function __construct( $container )
    {
        $this->container = $container;
    }
  
    /**
     * serialize 
     * 
     */
    public function serialize( $data, $filter_name )
    {
        if( !$data ) return null;
        foreach ($data as $fname => $field )
        {
            if( isset($field['name']) and is_object($field['name']) )
            {
                if ($field['name'] instanceof \DateTime )
                {
                    $data[$fname]['name'] = $field['name']->format('Y-m-d');
                }
                else
                {
                    $entity = $field['name'];
                    $data[$fname]['name'] = sprintf( "CLASS %s %u",get_class($entity), $entity->getId() );
                }
            }
        }
        $this->container->get('session')->set( $filter_name, $data );
        return false;
    }

    /**
     * unserialize 
     * 
     */
    public function unserialize( $filter_name, $em_name='default' )
    {
        $entity_manager = $this->container->get('doctrine')->getManager( $em_name );
        $data = $this->container->get('session')->get( $filter_name );
        foreach( $data as $fname => $field )
        {
            $date = date_parse($field['name']);
            if( preg_match("/^(CLASS)\s(.*)\s(\d+)$/", $field['name'], $array) )
            {
                $entity = $entity_manager->find( $array[2],$array[3] );
                if( is_object($entity) )
                {
                    $data[$fname]['name'] = $entity;
                }
                else unset($data[$fname]);
            }
            elseif( checkdate( $date["month"], $date["day"], $date["year"] ) )
            {
                $data[$fname]['name'] = new \DateTime( $field['name'] );
            }
        }
        return $data;
    }

}
