#AdminPanelBundle
Lots of applications are required to display tabular list of entities that have to be pagination,
also it would be nice to have the ability to sort through all fields and flexible filtering.
Those tasks can solve AdminPanelBundle.
Of course, this is not something new - the same SonataAdminBundle provides similar functionality,
but Sonata is a monster (in a good sense of the word), with many options and dependencies,
and my goal was to make a fast and flexible navigation of large tabular arrays.

##What can bundle
* At the entrance can be array, Doctrine\ORM\Query, Doctrine\ORM\QueryBuilder, Doctrine\Common\Collection\ArrayCollection
* Output only certain fields (properties)
* For any field (property) can be defined unlimited number of filters (AND, OR) with a choice of operator (=,>, <, LIKE, etc ...)
* For any field, you can enable / disable sorting
* When you apply a filter filtering settings are stored in the session, and when you visit the page apply
* It is possible to deduce the AutoSum on any numeric column

The demonstration can be found [here][1]


##Installation and basic configuration
As usual - run

    composer require "zk2/admin-panel-bundle:dev-master"
Bundle uses **knplabs/knp-paginator-bundle** and **braincrafted/bootstrap-bundle**,
if they do not exist in your application, it will be installed

###Setting KnpPaginatorBundle

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            // ...
        );
    }

###Setting BraincraftedBootstrapBundle
Adjusting well described [here] [2] if Quick, then

    # app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
            // ...
        );
    }
    # app/config/config.yml
    .......
    # Assetic Configuration
    assetic:
        debug:          "%kernel.debug%"
        use_controller: false
        bundles:        [ ]
        filters: # node using
            less:
                node: /usr/bin/node # to know the path you can do $ whereis node
                node_paths: [/usr/lib/node_modules] # $ whereis node_modules
                apply_to: "\.less$"
            cssrewrite: ~
    braincrafted_bootstrap:
        less_filter: less
        jquery_path: %kernel.root_dir%/../web/js/jquery-1.11.1.js # path to jQuery
Next, run

    php app/console braincrafted:bootstrap:install
    php app/console assetic:dump

###Setting Zk2AdminPanelBundle

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Zk2\Bundle\AdminPanelBundle\Zk2AdminPanelBundle(),
            // ...
        );
    }


    # app/config/config.yml
    ......
    twig:
        ......
        form:
            resources:
                - "Zk2AdminPanelBundle:AdminPanel:bootstrap_form_div_layout.html.twig"

    # bundle default settings
    zk2_admin_panel:
        check_flag_super_admin:  false # -- if true, entity user should have the method "flagSuperAdmin()", which returns a boolean value
        pagination_template:     Zk2AdminPanelBundle:AdminPanel:pagination.html.twig # - block template pagination
        sortable_template:       Zk2AdminPanelBundle:AdminPanel:sortable.html.twig # - template links to sort columns

and upload styles, icons, etc..

    php app/console asset:install web --symlink


###Use
Show an example of a small application
["Auto"][1]

Classical structure - Country -> Brand -> Model

Do not judge strictly for the completed data (fake)

All the description placed in the comments in the code (so it seemed to me more clearly)

The controller must be inherited from Zk2\Bundle\AdminPanelBundle\AdminPanel\AdminPanelController

Parent constructor accepts:
* - The main entity
* - The alias for this entity
* - Optional parameter "name entity_manager" - default "default"

Controller

    namespace AppBundle\Controller;
    
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Zk2\Bundle\AdminPanelBundle\AdminPanel\AdminPanelController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Security\Core\Exception\AccessDeniedException;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    
    class DefaultController extends AdminPanelController
    {
        /**
         * Constructor
         */
        public function __construct()
        {
            parent::__construct('AppBundle\Entity\Model','m');
        }
        
listAction

    /**
     * listAction
     *
     * @Route("/", name="model_list")
     *
     * @return renderView
     */
    public function listAction( Request $request )
    {
        // If the access rights are being used
        // Method isZk2Granded assumes the role or an array of roles
        // If app/config.yml parameter zk2_admin_panel.check_flag_super_admin == true,
        // Method checks for the "full access"
        /*
        if ( false === $this->isZk2Granded(array('ROLE_LIST')) )
        {
            throw new AccessDeniedException();
        }*/
        
        // when you reset all filters
        if( $this->isReset() )
        {
            return $this->redirect( $this->generateUrl( $this->get('request')->get('_route') ) );
        }
        
        // construction of table columns
        $this->buildListFields();
        
        // the structure of the table columns to transfer a pattern
        $items = $this->getListFields();
        
        // initialize request
        $this->getEm()->buildQuery();
        
        // the query may contain reference to objects - "m, b, c",
        // and to the specific properties of objects - "b.id AS brand_id, b.name AS brand_name, m.name, m.color"
        // the difference is that in the former case, the query returns a collection of objects which can be costly
        // and in the second case returns to the normal array
        $this->getQuery()
            ->select(
                'b.id AS brand_id,b.name AS brand_name,c.name AS country_name,b.logo,m.id AS id,m.name,'
                .'m.color,m.airbag,m.sales,m.speed,m.price,m.dateView')
            ->leftJoin('m.brand','b')
            ->leftJoin('b.country','c')
        ;
        
        // default sorting
        if( !$this->get('request')->query->has('sort') )
        {
            $this->getQuery()->orderBy('m.id','DESC');
        }
        
        // build filters
        $this->buildFilterFields();
        // apply filters
        $this->checkFilters();
        // initialize KnpPaginator
        $pagination = $this->getPaginator(30);
        // form a filter pattern for transmission
        $filter_form = $this->getViewFiltersForm();
        // if necessary AutoSum some columns
        $this->initAutosum();
        $autosum = $this->getSumColumns();
        
        return $this->render('AppBundle:Model:list.html.twig', array(
            'results'     => $pagination,
            'items'       => $items,
            'filter_form' => $filter_form,
            // Is it necessary to create a button "new entity"
            'is_new'      => false, //$this->isZk2Granded(array('ROLE_NEW_ITEM')),
            'autosum'     => $autosum,
            // number format by default (PHP :: number_format), you can override the settings for each column
            'zkNumberFormat' => array('0','.',' '), 
        ));
    }

Construction of table columns

addInList method accepts an array:

* - entity property
* - column heading (the method of trans is an analogue of a standard feature of Symfony. Takes a value, domain and array of parameters)
* - entity alias
* - array options

The default values in the array of options:

* - 'sort'    => true, -- sorting column
* - 'func'    => null, -- functions (dateTimeFormat)
* - 'filter'  => null, -- filters (yes_no)
* - 'method'  => null, -- the name of the property or method
* - 'autosum' => null, -- unique alias for AutoSum

Also the options array may include:

* - 'link_id' => 'brand_edit' -- route name
* - 'lid'     => 'brand_id'   -- property or method to transfer ID in route
* - 'style' => 'text-align:center' -- any css style (using a table cell)
* - 'class' => 'my-class' -- any DOM class (using a table cell)
* - 'icon_path' => '/img/' -- will turn into a tag img src = "{icon_path} value"
* - 'icon_width' => 24 -- used with icon_path (width of the image)
* - 'zkNumberFormat' => array(2,'.',' ') -- PHP::number_format
* - 'dateTimeFormat' => 'Y-m-d' -- used for func::dateTimeFormat

More information about the options and their use can be found in the
original code AdminPanelBundle/Resources/views/AdminPanel/adminList.html.twig

You can use any of customer's options, in that case you have to override the template adminList.html.twig
with one of the standard ways of Symfony, and process them there

    /**
     * Construction of table columns
     */
    public function buildListFields()
    {
        $this
        ->addInList(array(
            'name',                            // entity property
            $this->trans('Brand','messages'),  // column heading
            'b',                               // entity alias
            array(
                // if your query returns a simple array, the alias has to be here (b.name AS brand_name)
                // else there must be a method, which is defined in the basic entity
                // (in our case Model::getBrandName())
                'method'  => 'brand_name',
                
                // The brand name will be a link ( @Route("/brand/{id}/edit", name="brand_edit") )
                'link_id' => 'brand_edit',
                
                // if our query returns a simple array, the alias has to be here ( b.id AS brand_id )
                // else there must be the name of the method, which is defined in the basic entity
                //     ( in our case Model::getBrandId() )
                // if link_id is defined and lid is undefined, the ID from the base entity will be substituted into the route
                'lid' => 'brand_id'
            ),
        ))
        ->addInList(array(
            'name',
            $this->trans('Country','messages'),
            'c',
            array(
                'method'  => 'country_name',
            ),
        ))
        ->addInList(array(
            'logo',
            $this->trans('Logo','messages'),
            'b',
            array(
                'sort'    => false,
                'style' => 'text-align:center',
                'icon_path' => '/img/'
            ),
        ))
        ->addInList(array(
            'name',
            $this->trans('Model','messages'),
            'm',
            array(
                'link_id' => 'model_edit',
            ),
        ))
        ->addInList(array(
            'color',
            $this->trans('Color','messages'),
            'm',
            array(
                'style' => 'text-align:center'
            ),
        ))
        ->addInList(array(
            'airbag',
            $this->trans('Airbag','messages'),
            'm',
            array(
                'filter'  => 'yes_no',  // Will display "Yes" or "No"
                'style' => 'text-align:center'
            ),
        ))
        ->addInList(array(
            'sales',
            $this->trans('Sales','messages'),
            'm',
            array(
                'autosum' => 'sales_sum', // The amount of numbers will be calculated
                'style' => 'text-align:center'
            ),
        ))
        ->addInList(array(
            'speed',
            $this->trans('Max speed','messages'),
            'm',
            array(
                'style' => 'text-align:center'
            ),
        ))
        ->addInList(array(
            'price',
            $this->trans('Price','messages'),
            'm',
            array(
                'style' => 'text-align:center',
                'zkNumberFormat' => array(2,'.',' ')
            ),
        ))
        ->addInList(array(
            'dateView',
            $this->trans('Date','messages'),
            'm',
            array(
                'func'    => 'dateTimeFormat',  // For DateTime
                'dateTimeFormat' => 'Y-m-d',
                'style' => 'text-align:center'
            ),
        ))
        ;
    }

Construction of filters

method addInFilter takes an array:

* - 'b_name' -- alias and name of the property with the underbar
* - 'zk2_admin_panel_XXXXX_filter' -- filter type
* - The name of the filter
* - the number of filters for the field
* - set of available operators (LIKE, =,>, <, etc ...). Read more - AdminPanel/ConditionOperator.php
* - an array of parameters

Types of filters:

* - 'zk2_admin_panel_boolean_filter' -- Boolean filter (yes / no)
* - 'zk2_admin_panel_choice_filter'  -- drop-down list, certain there also
* - 'zk2_admin_panel_date_filter'    -- filter by date
* - 'zk2_admin_panel_entity_filter'  -- drop-down list containing the entity (a request is made to the database)
* - 'zk2_admin_panel_text_filter'    -- normal text field

addInFilter

    /**
     * construction of filters
     */
    public function buildFilterFields()
    {
        $this
        ->addInFilter(array( // -- drop-down list containing the entities
            'b_name',
            'zk2_admin_panel_entity_filter',
            $this->trans('Brand','messages'),
            5,
            'smal_int',
            array(
                'entity_type' => 'entity',
                'entity_class' => 'AppBundle\Entity\Brand',
                'property' => 'name',
                'sf_query_builder' => array( // If you want to limit the query - condition
                   'alias' => 'b',
                    'where' => 'b.id IS NOT NULL',
                    'order_field' => 'b.name',
                    'order_type' => 'ASC',
                )
        )))
        ->addInFilter(array(
            'm_name',
            'zk2_admin_panel_text_filter',
            $this->trans('Model','messages'),
            5,
            'light_text'
        ))
        ->addInFilter(array( // drop-down list, certain there also
            'm_color',
            'zk2_admin_panel_choice_filter',
            $this->trans('Color','messages'),
            5,
            'smal_int',
            array('sf_choice' => array(
                'black' => 'black',
                'blue' => 'blue',
                'brown' => 'brown',
                'green' => 'green',
                'red' => 'red',
                'silver' => 'silver',
                'white' => 'white',
                'yellow' => 'yellow',
            )),
        ))
        ->addInFilter(array(
            'm_airbag',
            'zk2_admin_panel_boolean_filter',
            $this->trans('Airbag','messages'),
        ))
        ->addInFilter(array(
            'm_door',
            'zk2_admin_panel_text_filter',
            $this->trans('Number of doors','messages'),
            5,
            'medium_int'
        ))
        ->addInFilter(array(
            'm_speed',
            'zk2_admin_panel_text_filter',
            $this->trans('Max speed','messages'),
            5,
            'medium_int'
        ))
        ->addInFilter(array(
            'm_prise',
            'zk2_admin_panel_text_filter',
            $this->trans('Price','messages'),
            5,
            'medium_int'
        ))
        ->addInFilter(array( // filter by date
            'm_dateView',
            'zk2_admin_panel_date_filter',
            $this->trans('Date','messages'),
            2
        ))
        ;
    }

Edit methods

    /**
     * edit Brand Action
     *
     * @Route("/brand/{id}/edit", name="brand_edit")
     * 
     * @param Request $request
     * @param integer $id
     *
     * @return renderView
     */
    public function editBrandAction( Request $request, $id )
    {
        ............
    }
    
    /**
     * edit Action
     *
     * @Route("/model/{id}/edit", name="model_edit")
     * 
     * @param Request $request
     * @param integer $id
     *
     * @return renderView
     */
    public function editAction( Request $request, $id )
    {
        .....
    }


Well, very simple template

    # AppBundle:Model:list.html.twig
    
    {% extends "Zk2AdminPanelBundle::base.html.twig" %}
    
    {% block zk2_title %}Models list{% endblock %}
    
    {% block zk2_h %}<h1>General list</h1>{% endblock %}
    
    {% block zk2_body %}
    
    {% if filter_form %}
    {% include 'Zk2AdminPanelBundle:AdminPanel:adminFilter.html.twig' with {
        'filter_form': filter_form,
        'colspan': 2, {# number of columns in the table filter #}
        'this_path': path('model_list')
    } %}
    {% endif %}
        
    {% include 'Zk2AdminPanelBundle:AdminPanel:adminList.html.twig' with {
        'items': items,
        'results': results,
        'Zk2NumberFormat': zkNumberFormat
    } %}

    {% if is_new %}
        The "New" button
    {% endif %}
    
    {% endblock %}


[1]:  http://admin-panel.zeka.guru/
[2]:  http://bootstrap.braincrafted.com/getting-started.html#configuration
