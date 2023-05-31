<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);


/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

/* CRON UPDATE */
$routes->get('/cron/update_orders','Cron::update_orders');

/* API MERCADO PAGO*/
$routes->post('/api/v1/update_order', 'Api::update_order');
$routes->post('/api/v1/confirm_payment_customer', 'Api::confirmPaymentCustomer');

/* API TEMPORARIA */
//$routes->get('/api/v1/update','Api::update');

/* API PAGGUE*/
$routes->post('/api/v1/webhook_paggue', 'Api::webhook_paggue');
$routes->get('/api/v1/webhook_paggue', 'Api::webhook_paggue');


/* API PAGGUE PAGAMENTO DA RIFA */
$routes->post('/api/v1/webhook_raffle_paggue', 'Api::webhook_raffle_paggue');
$routes->get('/api/v1/webhook_raffle_paggue', 'Api::webhook_raffle_paggue');


/* LOGIN */
$routes->match(['get','post'],'/login', 'Auth::login');

/* CADASTRO */
$routes->match(['get','post'],'/cadastro', 'Auth::register');

$routes->get('/clear-pendentes', 'Dashboard::clearPendentes');


/* LOGOUT */
$routes->get('/auth/logout', 'Auth::logout');

/* ADMIN ROUTES */
$routes->get('/dashboard', 'Dashboard::index', ['filter'=> 'auth']);

/* NOVO GANHADOR */
$routes->post('/dashboard/pedidos', 'Dashboard::orders', ['filter' => 'auth']);

/* PEDIDOS */
$routes->match(['get','post'],'/dashboard/pedidos', 'Dashboard::orders', ['filter'=> 'auth']);

/* PEDIDOS */
$routes->get('/dashboard/ranqueamento', 'Dashboard::ranking', ['filter'=> 'auth']);

/* GET NUMBERS ORDER */
$routes->get('/dashboard/pedido/getNumber/(:num)', 'Dashboard::getNumber/$1', ['filter'=> 'auth']);


/* EXCLUI O PEDIDO */
$routes->get('/dashboard/pedidos/excluir/(:num)','Dashboard::delete_order/$1', ['filter'=> 'auth']);

/* APROVA O PEDIDO */
$routes->get('/dashboard/pedidos/aprovar/(:num)','Dashboard::approve_order/$1', ['filter'=> 'auth']);
$routes->post('/aprovarPedido', 'Dashboard::aprovar', ['filter' => 'auth']);

/* Exclui o pedido */
$routes->get('/dashboard/pedidos/excluir/(:num)','Dashboard::delete_order/$1', ['filter'=> 'auth']);





/* TODAS AS RIFAS */
$routes->get('/dashboard/rifas', 'Dashboard::raffles', ['filter'=> 'auth']);

/* ADICIONAR RIFA */
$routes->match(['get','post'],'/dashboard/rifas/adicionar', 'Dashboard::add_raffle', ['filter'=> 'auth']);

/* EDITAR RIFA */
$routes->match(['get','post'],'/dashboard/rifas/editar/(:num)', 'Dashboard::edit_raffle/$1', ['filter'=> 'auth']);

/* EXCLUIR A RIFA */
$routes->get('/dashboard/rifas/excluir/(:num)', 'Dashboard::delete_raffle/$1', ['filter'=> 'auth']);

/* CLIENTES */
$routes->get('/dashboard/clientes', 'Dashboard::customers', ['filter'=> 'auth']);
$routes->get('/dashboard/clientes/rifas/(:num)', 'Dashboard::customer_rifas/$1', ['filter'=> 'auth']);
$routes->get('/dashboard/clientes/rifas/aprovar-pagamento/(:num)', 'Dashboard::aprove_raffle/$1', ['filter'=> 'auth']);

/* PLANOS */
$routes->get('/dashboard/planos', 'Dashboard::planos', ['filter'=> 'auth']);
$routes->get('/dashboard/planos/novo-plano', 'Dashboard::novo_plano', ['filter'=> 'auth']);
$routes->post('/dashboard/planos/novo-plano', 'Dashboard::salvar_plano', ['filter'=> 'auth']);
$routes->get('/dashboard/planos/excluir/(:num)', 'Dashboard::delete_plano/$1', ['filter'=> 'auth']);

/* Ativa e desativa a cobrança do cliente */
$routes->get('/dashboard/clientes/toggle-cobranca/(:num)', 'Dashboard::toggle_cobranca/$1', ['filter'=> 'auth']);

/* REMOVE O CLIENTE */
$routes->get('/dashboard/clientes/excluir/(:num)', 'Dashboard::delete_customer/$1', ['filter'=> 'auth']);

/* CATEGORIAS */
$routes->match(['get','post'],'/dashboard/categorias', 'Dashboard::categories', ['filter'=> 'auth']);

$routes->get('/dashboard/categorias/excluir/(:num)', 'Dashboard::delete_category/$1', ['filter'=> 'auth']);

/* ROUTE GERAR PDF */
$routes->get('/dashboard/gerarpdf/(:any)', 'Dashboard::gerarpdf/$1', ['filter'=> 'auth']);

/* ATIVA E DESATIVA A RIFA */
$routes->get('/dashboard/toggle-raffle/(:any)', 'Dashboard::toggle_raffle/$1', ['filter'=> 'auth']);

/* ATIVA E DESATIVA PARCIAL DA RIFA */
$routes->get('/dashboard/toggle-parcial/(:any)', 'Dashboard::toggle_parcial/$1', ['filter'=> 'auth']);

/* ATIVA E DESATIVA FAVORITAR RIFA */
$routes->get('/dashboard/toggle-favoritar/(:any)', 'Dashboard::toggle_favoritar/$1', ['filter'=> 'auth']);

/* GANHADORES */
//$routes->match(['get','post'],'/dashboard/ganhadores', 'Dashboard::winners', ['filter'=> 'auth']);

/* Excluir ganhador */
$routes->get('/dashboard/ganhadores/excluir/(:num)', 'Dashboard::delete_winner/$1', ['filter'=> 'auth']);

/* CONFIGURAÇÕES */
$routes->match(['get','post'],'/dashboard/configuracoes', 'Dashboard::settings', ['filter'=> 'auth']);


/*======================== FRONT END =============================*/

/* CHECKOUT */
$routes->get('/checkout', 'Home::checkout');


$routes->get('/meus-pedidos', 'Home::myOrders');


/* ENCONTRA OS NÚMEROS */
$routes->post('/meus-pedidos', 'Home::findMyOrders');


/* FIND PAYMENT RESULT */
$routes->post('/buscar-pagamento', 'Home::findPaymentStatus');


/* FIND USER RAFFLE PAYMENT RESULT */
$routes->post('/api/buscar-pagamento', 'Dashboard::findPaymentStatus');

/* RAFFLES */
$routes->get('/(:any)', 'Home::raffle/$1');


/* BUY MANUAL RAFFLE */
$routes->post('/buy-manual-raffle', 'Home::buyManualRaffle');

/* BUY AUTO RAFFLE */
$routes->post('/buy-auto-raffle', 'Home::buyAutoRaffle');

/* UPLOAD DA LOGOMARCA */
$routes->post('/upload-logomarca', 'Dashboard::uploadLogomarca', ['as' => 'upload-logomarca']);








/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}