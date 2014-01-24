<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Accounts\Controller\Login'     => 'Accounts\Controller\LoginController',
            'Accounts\Controller\Register'  => 'Accounts\Controller\RegisterController',
            'Accounts\Controller\Dashboard' => 'Accounts\Controller\DashboardController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'accounts' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/accounts',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Accounts\Controller',
                        'controller'    => 'Login',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'accounts' => __DIR__ . '/../view',
        ),
    ),
);
