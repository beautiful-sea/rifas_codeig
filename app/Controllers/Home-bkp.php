<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\RaffleModel;
use App\Models\OrderModel;
use App\Models\SettingModel;
use App\Models\CustomerModel;
use App\Models\UserModel;
use App\Models\WinnerModel;
use Exception;

class Home extends BaseController
{


    public function index()
    {

        $raffleModel = new RaffleModel();
        $categoryModel = new CategoryModel();
        $winnerModel = new WinnerModel();
       

        $data = [
            'title' => 'Mundo Cell',
        ];

        $id_c = filter_var($this->request->getGet('c'), FILTER_SANITIZE_STRIPPED);

        if(isset($id_c) && !empty($id_c)){
            
            if($id_c === 'todas'){
                $data['category'] = (object) ['title' => 'Todas', 'description'=> 'Sua chance de mudar de vida'];
                $data['raffles'] = $raffleModel->select('id, slug, title, images, price, status, draw_date, percent_level, fake_percent_level, description')->asObject()->orderBy('id','DESC')->paginate(10);
            } else {
                $data['category'] = $categoryModel->where(['id' => $id_c])->first();

                if($data['category']){
                    $data['raffles'] = $raffleModel->select('id, slug, title, images, price, status, draw_date, percent_level, fake_percent_level, description')->where(['id_category' => $id_c])->asObject()->orderBy('id','DESC')->paginate(10);
                } else {
                    return redirect()->to('/');
                }
            }
           
            $data['pager'] = $raffleModel->pager;
        } else {    
          
            /* Busca direta, com limite de 4 */
            $data['raffles'] = $raffleModel->select('id, slug, title, images, price, status, draw_date, percent_level, fake_percent_level, description')->where(['status' => 1])->asObject()->orderBy('id','DESC')->findAll(4);
        }

        
        $data['categories'] = $categoryModel->findAll();

        /* Winners
        $data['winners'] = $winnerModel->orderBy('id','DESC')->findAll(4);

        if($data['winners']){
            foreach($data['winners'] as $key_winner => $winner){
                $data['winners'][$key_winner]->raffle =  $raffleModel->select('slug, title, images')->where('id',$winner->id_raffle)->first();
            }
        }

        */
        // PEGO AS 4 ÚLTIMAS RIFAS 

        $raffles = $raffleModel->select('id, winners, title, slug, draw_date', 'description')->where(['status' => 2])->orderBy('id', 'DESC')->findAll(4);
    
    
        
        

        foreach($raffles as $r){

            // pego os ganhadores
            $w = json_decode($r->winners);

            if($w){
                foreach($w as $key_winner => $winner){

                    if($winner->position == '1') {
                        
                        $data['winners'][] = [
                            'name' => $winner->name,
                            'id' => $r->id,
                            'winner' => $winner->name,
                            'slug' => $r->slug,
                            'images' => $r->images,
                            'title' => $r->title,
                            'favoritar' => $r->favoritar,
                            'draw_date' => $r->draw_date,
                            'number' => $winner->number
                        ];
                    }
                }
            }
            
        }
        
        echo view('frontend/partials/header',$data);
        echo view('frontend/home', $data);
        echo view('frontend/partials/footer');
    }

    /* Url da rifa */
    public function raffle($slug){

        $data = [];

        $raffleModel = new RaffleModel();
        $categoryModel = new CategoryModel();

        $data['raffle'] = $raffleModel->where(['slug' => $slug])->first();


        if($data['raffle']){

            $data['title'] = $data['raffle']->title;
            $data['images'] = json_decode($data['raffle']->images);

            if(isset($data['images'][0])){
                $image_og = base_url('public/images') .'/'.$data['images'][0];
                $image_type = str_replace('.','', strstr($data['images'][0], '.'));

                if($image_type == 'jpg'){
                    $image_type = 'jpeg';
                }

            } else {
                $image_og = base_url('public/images') .'/default.png';
                $image_type = 'png';
                
            }

            if(isset($data['raffle']->pixels) && !empty($data['raffle']->pixels)){
                $data['pixels'] = $data['raffle']->pixels;
            }

            $data['metas'] = '
                <meta property="og:title" content="'.$data['raffle']->title.'">
                <meta property="og:site_name" content="HD Produtora">
                <meta property="og:description" content="'.$data['raffle']->description.' :: HD Produtora">
                <meta property="og:url" content="'.base_url().'/'.$data['raffle']->slug.'">
                <meta property="og:type" content="article">
                <meta property="article:author" content="HD Produtora">
                <meta property="article:section" content="SEO">
                <meta property="article:tag" content="Rifas, melhor site de rifas, rifa automática, pagamento por pix">
                <meta property="article:published_time" content="'.date('Y-m-d H:i:s',strtotime($data['raffle']->created_at)).'.0">
                <meta property="og:image" content="'.$image_og.'">
                <meta property="og:image:type" content="image/'.$image_type.'">
                <meta property="og:image:width" content="800"> 
                <meta property="og:image:height" content="600"> 
                <meta property="og:locale" content="pt_BR">
            ';
            
            // SE A RIFA POSSUI GANHADORES
            if($data['raffle']->winners){
                $data['winners'] = json_decode($data['raffle']->winners);
            }

            $data['categories'] = $categoryModel->findAll();
            
            echo view('frontend/partials/header', $data);

            $count = 0;
            $res = 0;
            $pagos = 0;
            foreach( json_decode($data['raffle']->numbers) as $number ){
                if($number->status == '0'){
                    $count++;
                }else if($number->status == '1'){
                    $res++;
                } else if($number->status == '2'){
                    $pagos++;
                }
            }

            $data['quantity_of_avaiable_numbers'] = $count;
            $data['reservados'] = $res;
            $data['pagos'] = $pagos;
            
            if($data['raffle']->type){
                /* Rifa manual */
                echo view('frontend/raffle_manual', $data);
            } else {
                /* Rifa automática */
                echo view('frontend/raffle_auto', $data);
            }

            echo view('frontend/partials/footer');

        } else {
            return redirect()->to('/');
        }
    }

    /* Compra da rifa manual */
    public function buyManualRaffle() {
        
        if($this->request->getMethod() == "post"){

            $validation = \Config\Services::Validation();

            $rules = [
                "name" => [
                    "label" => "name",
                    "rules" => "required|trim|min_length[5]|max_length[100]"
                ],
                "phone" => [
                    "label" => "phone",
                    "rules" => "required|trim|min_length[15]|max_length[15]"
                ],
                "phone_confirm" => [
                    "label" => "phone_confirm",
                    "rules" => "required|matches[phone]"
                ],
                "raffle_id" => [
                    "label" => "rifa",
                    "rules" => "required"
                ],
                "numbers" => [
                    "label" => "números",
                    "rules" => "required"
                ]
            ];

            if($this->validate($rules)){

                $raffleModel = new RaffleModel();
                $settingModel = new SettingModel();
                $userModel = new UserModel();

                $raffle_id = $this->request->getPost('raffle_id');
                $name = $this->request->getPost('name');
                $phone = $this->request->getPost('phone');
                $email = $this->request->getPost('email');
                $numbers = $this->request->getPost('numbers');

                /* Verifica se o usuário já é cliente */

                $customerModel = new CustomerModel();
                
                $customer = $customerModel->where(['phone' => $phone])->first();
                 // pega a rifa 
                $raffle = $raffleModel->where('id', $raffle_id)->first();

                if(!$customer){
                
                    // Cadastra e cria um id
                    $newCustomer = [
                        "name" => ucwords($name),
                        "email" => mb_strtolower($email),
                        "phone" => $phone,
                    ];

                    $customerModel->save( $newCustomer );
                    $customerId = $customerModel->getInsertID();

                    $customer = $customerModel->where('id',$customerId)->first();

                }

                /* Joga o user na session */
                session()->set('customer', $customer);

               

                // pega a data e hora atual
                $dateNow = new \DateTime('now');

                // SE A RIFA EXISTE 
                if($raffle){

                    // PEGO AS CONFIGURAÇÕES DO USUÁRIO
                    $userSettings = $userModel->select('id, expires_time, mp_access_token, paggue_client_key, paggue_client_secret')->where('id',$raffle->id_user)->first();
                    
                    // SE JÁ FOI PAGA 
                    if($raffle->payment_status){

                        // SE ESTÁ DISPONÍVEL
                        if($raffle->status == 1){
                    
                            if($raffle->draw_date != '0000-00-00 00:00:00'){
        
                                $raffleDrawDate = new \DateTime($raffle->draw_date);
            
                                if($raffleDrawDate->getTimestamp() < $dateNow->getTimestamp()){
                                    // Rifa expirada
                                    session()->setFlashdata('status', ['message' => 'Rifa expirada.','status' => 'error']);
                                    return redirect()->to('/'.$raffle->slug);
                                }
        
        
                            }
        
        
                            // Agora começa a verificar se os números estão disponíveis
                            $myNumbers = json_decode($numbers);
                            $raffleNumbers = json_decode($raffle->numbers);
        
                            $matrizIndexes = array();
                            $newNumbers = array();        
        
                            /* AQUI, APROVEITA E JÁ SALVA OS INDEXES DA MATRIZ, PRA NÃO PESQUISAR DE NOVO */
                            foreach($myNumbers as $keyMyNumbers => $valueMyNumbers){
                                //print_r(array_values($raffleNumbers));exit;
                                $keyArr = array_search($valueMyNumbers, array_column($raffleNumbers, 'number'));
                                
                                if($raffleNumbers[$keyArr]->status != 0){
                                    continue;  
                                }
                               
        
                                // Salva o index do número na matriz
                                $matrizIndexes[] = $keyArr;
        
                                // aproveita e cria uma matriz de números, pra já criar o pedido
                                $newNumbers[] = $raffleNumbers[$keyArr]->number;
                            }
        
                            /* TEM NÚMERO DISPONÍVEL, PEGA, ADICIONA A UM PEDIDO E ALTERA NA MATRIZ */
        
                            if($matrizIndexes && $newNumbers){
        
                                /* CRIA O PEDIDO E GUARDA O ID, QUE VAI SER USADO NA NOVA MATRIZ */
        
                                $orderModel = new OrderModel();
        
                                // Se o número estiver na promoção
                                $price = count($newNumbers) * $raffle->price;
                                $original_price = 0;
                                
                                if($raffle->discount_status){
                                    /* O NÚMERO ENTRA NA PROMOÇÃO */
                                    if(count($newNumbers) >= $raffle->discount_quantity){
                                        $original_price = $price;
                                        $price = count($newNumbers) * $raffle->discount_price;
                                    }
                                }
        
                                $packs = json_decode($raffle->packs);
                               
                                /* Verifica se ele adquiriu algum pack */
        
                                if($packs){
                                    foreach($packs as $pack){
                                        if (count($newNumbers) == $pack->qnt_numbers){
                                            $price = currencyToDecimal($pack->price);
                                        }
                                    }
                                }
        
                                if($userSettings->expires_time){
                                    $expires_time = $userSettings->expires_time;
                                } else {
                                    $expires_time = 10;
                                }
                                $newOrder = [
                               
                                    "hash" => md5($customer->id. time(). rand(0,9999)),
                                    "id_customer" => $customer->id,
                                    "id_raffle" => $raffle->id,
                                    "id_user" => $userSettings->id,
                                    "status" => 0,
                                    'quantity' => count($newNumbers),
                                    "numbers" => json_encode($newNumbers),
                                    "price" => $price,
                                    "original_price" => $original_price,
                                    "expires_in" => strtotime('+'.$expires_time.'minutes')
                                ];
        
                                $orderModel->save($newOrder);
                                $orderId = $orderModel->getInsertID();
        
                                foreach($matrizIndexes as $i){
                                    
                                    $raffleNumbers[$i]->status = 1; // aguardando pagamento
                                    $raffleNumbers[$i]->order_id = $orderId; // id do pedido
                                    $raffleNumbers[$i]->user = ucfirst($name); // nome do usuário
                                    $raffleNumbers[$i]->reserved_at = $dateNow->format('Y-m-d H:i:s');  
                                }
        
                                // Codifica em json para o banco de dados
                                $raffleNumbers = json_encode($raffleNumbers);
        
                                // Enfim salva a matriz atualizada e atualiza a rifa
                                $raffleModel->save([
                                    'id' => $raffle->id,
                                    'numbers' => $raffleNumbers
                                ]);
        
                                session()->set('order', $newOrder);
                                session()->set('customer', $customer);
        
                                /* Atualiza a porcentagem da rifa */
                                $this->setRafflePercent($raffle->id);
                                
                                // direciona para meus pedidos, onde terá o botão de checkout 
                                return redirect()->to('/meus-pedidos');
                                
        
                            } else {
                                // Retorna com erro de números indisponíveis
                                session()->setFlashdata('status', ['message' => 'Número(s) expirado(s).','status' => 'error']);
                                return redirect()->to('/'.$raffle->slug);
                            }
                        } else {
                            // RIFA INDISPONÍVEL
                            session()->setFlashdata('status', ['message' => 'Rifa indisponível','status' => 'error']);
                            return redirect()->to('/'.$raffle->slug);
                        }

                    } else {
                        // MODO DEMONSTRAÇÃO
                        session()->setFlashdata('status', ['message' => 'Modo demonstração','status' => 'error']);
                        return redirect()->to('/'.$raffle->slug);
                    }


                } else {
                    // RIFA NÃO ENCONTRADA
                    session()->setFlashdata('status', ['message' => 'Rifa não encontrada','status' => 'error']);
                    return redirect()->to('/');
                }
                
                
            } else {
                session()->setFlashdata('status', ['message' => 'Ocorreu algum erro, verifique seus dados e tente novamente','status' => 'error']);
                return redirect()->to('/');
            }


        } 

    }

    /* Compra de rifa automática */
    public function buyAutoRaffle(){


        if($this->request->getMethod() == "post"){

            $validation = \Config\Services::Validation();

            $rules = [
                "name" => [
                    "label" => "name",
                    "rules" => "required|trim|min_length[5]|max_length[100]"
                ],
                "phone" => [
                    "label" => "phone",
                    "rules" => "required|trim|min_length[15]|max_length[15]"
                ],
                "phone_confirm" => [
                    "label" => "phone_confirm",
                    "rules" => "required|matches[phone]"
                ],
                "raffle_id" => [
                    "label" => "rifa",
                    "rules" => "required"
                ],
                "numbers" => [
                    "label" => "números",
                    "rules" => "required"
                ]
            ];


            // VERIFICAÇÃO DOS DADOS
            if($this->validate($rules)){

                $raffleModel = new RaffleModel();
                $userModel = new UserModel();

                $raffle_id = $this->request->getPost('raffle_id');
                $name = $this->request->getPost('name');
                $phone = $this->request->getPost('phone');
                $email = $this->request->getPost('email');
                $numbers = $this->request->getPost('numbers');
                
                

                /* Verifica se o usuário já é cliente */

                $customerModel = new CustomerModel();
                $orderModel = new OrderModel();
                $settingModel = new SettingModel();
                
                $customer = $customerModel->where(['phone' => $phone])->first();

                if(!$customer){
                
                    // Cadastra e cria um id
                    $newCustomer = [
                        "name" => ucfirst($name),
                        "email" => mb_strtolower($email),
                        "phone" => $phone,
                    ];

                    $customerModel->save( $newCustomer );
                    $customerId = $customerModel->getInsertID();

                    $customer = $customerModel->where('id',$customerId)->first();

                }

                /* Joga o user na session */
                session()->set('customer', $customer);

                // pega a rifa 
                $raffle = $raffleModel->where('id', $raffle_id)->first();
                
                // Validando se foi escolhido menos de 4500 numeros (retirar após corrigir problema quando escolhe muitos numeros)
                // if($this->request->getPost('numbers') > 1500){
                //     session()->setFlashdata('status', ['message' => 'Só é permitido comprar no máximo 1500 números','status' => 'error']);
                //                 return redirect()->to('/'.$raffle->slug);
                    
                // }


                $userSettings = $userModel->select('id, expires_time, mp_access_token, paggue_client_key, paggue_client_secret')->where('id',$raffle->id_user)->first();
                
                // pega a data e hora atual
                $dateNow = new \DateTime('now');


                // SE A RIFA EXISTE
                if($raffle){
                    // SE A RIFA NÃO ESTÁ PENDENTE DE PAGAMENTO

                    if($raffle->payment_status){
                    
                        // SE A RIFA ESTÁ ATIVA
                        if($raffle->status == 1){
    
                            if($raffle->draw_date != '0000-00-00 00:00:00'){
        
                                $raffleDrawDate = new \DateTime($raffle->draw_date);
            
                                if($raffleDrawDate->getTimestamp() < $dateNow->getTimestamp()){
                                    // Rifa expirada
                                    session()->setFlashdata('status', ['message' => 'Rifa expirada.','status' => 'error']);
                                    return redirect()->to('/'.$raffle->slug);
                                }
        
                            }
                           
                            // Agora começa a verificar se os números estão disponíveis
                            $quantityOfNumbers = $numbers;
                            $raffleNumbers = json_decode($raffle->numbers);
        
                            $myNumbers = array();
        
                            // Sorteia os números da rifa, pra pegar aleatoriamente
                            shuffle($raffleNumbers);

                            //Auxiliar para salvar os numeros reservados
                            $reservados = [];
                            
                            foreach($raffleNumbers as $k => $number){
        
                                #echo $valueRaffleNumber->number;exit;
        
                                //$hasOrder = $orderModel->where('id_raffle', $raffle->id)->like('numbers',$valueRaffleNumber->number,'match')->first();
                                if($number->status == 0 && count($myNumbers) < $quantityOfNumbers){
                                    // O número está disponível 
                                    //$raffleAvailableNumbers++
        
                                    // VOU PROCESSAR TUDO AQUI MESMO 
                                    // GARANTE QUE O NÚMERO NÃO ESTEJA SENDO USADO
                                    $hasOrder = $orderModel->where('id_raffle', $raffle->id)->like('numbers',$number->number,'match')->first();
                                    
                                    if(!$hasOrder){
        
                                        $myNumbers[] = $number->number;
                                        /* Altera também o status da rifa */
                                        $raffleNumbers[$k]->status = 1; // aguardando pagamento
                                        //$raffleNumbers[$k]->order_id = $orderId; // id do pedido
                                        $raffleNumbers[$k]->user = ucwords($name); // nome do usuário
                                        $raffleNumbers[$k]->reserved_at = $dateNow->format('Y-m-d H:i:s');
                                        array_push($reservados, $k);
                                        
                                    }
                                
                                }
        
                            }
                   
                            // Se existir números disponíveis
                            if($myNumbers){
        
                                // CRIA O PEDIDO E GUARDA O ID, QUE VAI SER USADO NA NOVA MATRIZ 
                                // Se o número estiver na promoção
                                $price = count($myNumbers) * $raffle->price;
                                $original_price = 0;
                                
                                if($raffle->discount_status){
                                    // O NÚMERO ENTRA NA PROMOÇÃO 
                                    if(count($myNumbers) >= $raffle->discount_quantity){
                                        $original_price = $price;
                                        $price = count($myNumbers) * $raffle->discount_price;
                                    }
                                }
        
                                $packs = json_decode($raffle->packs);
                                
                                // Verifica se ele adquiriu algum pack 
                                if($packs){
                                    foreach($packs as $pack){
                                        if ($quantityOfNumbers == $pack->qnt_numbers){
                                            $price = currencyToDecimal($pack->price);
                                        }
                                    }
                                }
        
                                
                                if($userSettings->expires_time){
                                    $expires_time = $userSettings->expires_time;
                                } else {
                                    $expires_time = 10;
                                }
        
                                $newOrder = [
                                    "hash" => md5($customer->id. time(). rand(0,9999)),
                                    "id_customer" => $customer->id,
                                    "id_raffle" => $raffle->id,
                                    "id_user" => $userSettings->id,
                                    "status" => 0, // 1 pra pagamento aprovado
                                    'quantity' => count($myNumbers),
                                    "numbers" => json_encode($myNumbers),
                                    "expires_in" => strtotime('+'.$expires_time.'minutes'),
                                    "price" => $price,
                                    "original_price" => $original_price
                                ];
                                
                                $orderModel->save($newOrder);
                                $orderId = $orderModel->getInsertID();


                                // Atualizando numeros reservados o id do pedido
                                foreach ($reservados as $value) {
                                    $raffleNumbers[$value]->order_id = $orderId; // id do pedido
                                }
        
                                // Codifica em json para o banco de dados teste
                                $raffleNumbers = json_encode($raffleNumbers);
        
                                // Enfim salva a matriz atualizada e atualiza a rifa
                                $raffleModel->save([
                                    'id' => $raffle->id,
                                    'numbers' => $raffleNumbers
                                ]);
                                
        
                                session()->set('order', $newOrder);
                                session()->set('customer', $customer);
                                
        
                                /* Atualiza a porcentagem da rifa */
                                $this->setRafflePercent($raffle->id);
                                
                                // direciona para meus pedidos, onde terá o botão de checkout 
                                return redirect()->to('/meus-pedidos');
                                    
               
                            } else {

                                // NÚMEROS ESGOTADOS
                                session()->setFlashdata('status', ['message' => 'Os números já foram esgotados','status' => 'error']);
                                return redirect()->to('/');
                            }
                        } else {
                            // RIFA PAUSADA
                            session()->setFlashdata('status', ['message' => 'Rifa indisponível','status' => 'error']);
                            return redirect()->to('/'.$raffle->slug);
                        }
    
    
                    } else {
                        // MODO DEMONSTRAÇÃO
                        session()->setFlashdata('status', ['message' => 'Modo demonstração','status' => 'error']);
                        return redirect()->to('/'.$raffle->slug);
                    }


                } else {
                    // RIFA NÃO ENCONTRADA
                    session()->setFlashdata('status', ['message' => 'Rifa não encontrada','status' => 'error']);
                    return redirect()->to('/');
                }

            } else {
                session()->setFlashdata('status', ['message' => 'Ocorreu um erro, verifique os dados e tente novamente','status' => 'error']);
                return redirect()->to('/');
            }


        } 



    }


    /* Pesquisa pelos pedidos */
    public function findMyOrders() {

       
        $customerModel = new CustomerModel();

        $validation = \Config\Services::Validation();

        $phone = $this->request->getJSON('phone');

        $customer = $customerModel->where(['phone' => $phone])->first();
        
        if($customer){
            session()->set('user', $customer);
            echo json_encode(['result' => true, 'user' => json_encode($customer, JSON_UNESCAPED_UNICODE)], JSON_UNESCAPED_UNICODE);
        } else {

            echo json_encode(['result'=>false], JSON_UNESCAPED_UNICODE);
        }

        exit;
    }

    /* Pesquisa pelo pagamento */ 

    public function findPaymentStatus(){

        $orderModel = new OrderModel();
    
        $data = [
            'error' => '',
            'status' => ''
        ];

        $order_id = filter_var($this->request->getJsonVar('order_id'), FILTER_SANITIZE_STRIPPED);
       
        if($order_id){
            $order = $orderModel->select('status')->where(['id' => $order_id])->first();

            if($order){

                if($order->status == 0){
                    $data['status'] = 'pending';
                } else {
                    $data['status'] = 'approved';
                }

            } else {
                $data['error'] = 'Pedido não encontrado';
            }
        }

        echo json_encode($data);exit;


    }

    /* Meus pedidos */

    public function myOrders()
    {
        
        $data = [
            'title' => 'Meus pedidos'
        ];

        /* Pega os dados do usuário e do pedido na session */
        $data['customer'] = session()->get('customer');
        //$data['request'] =  session()->get('request');
        
        if(!$data['customer']){
            /* VOLTA PRA RIFA */
            return redirect()->back();
        }
        $orderModel = new OrderModel();
        $raffleModel = new RaffleModel();
        $userModel = new UserModel();
        $customerModel = new CustomerModel();
        
        /* Pega os pedidos do cliente */
        $data['orders'] = $orderModel->where('id_customer',$data['customer']->id)->orderBy('id', 'DESC')->findAll();

        /* Pega alguns dados complementares da rifa */
        foreach($data['orders'] as $orderKey => $orderValue){

            $raffle = $raffleModel->select('id,id_user,title, slug, images,wp_group, gateway')->where(['id' => $orderValue->id_raffle])->orderBy('id', 'DESC')->asObject()->first();
            $data['orders'][$orderKey]->raffle = $raffle;


            $userSettings = $userModel->select('id, mp_access_token, paggue_client_key, paggue_client_secret')->where('id',$raffle->id_user)->first();
            
            if ($orderValue->status == 0){

                /* Verifica se o pagamento ainda é válido */
                if( time() < intval($orderValue->expires_in) ){

                    /* O pedido ainda não foi processado pelo gateway de pagamento */
                    if(!$orderValue->payment_qrcode){

                        if($raffle->gateway == 'mp'){
                            // WDM
                            \MercadoPago\SDK::setAccessToken($userSettings->mp_access_token); // Either Production or SandBox AccessToken

                            $payment = new \MercadoPago\Payment();
                            
                            $payment->transaction_amount = $orderValue->price;
                            $payment->description = $raffle->title;
                            $payment->external_reference = $orderValue->hash;
                            $payment->installments = 1;
                            $payment->payment_method_id = "pix";
                            $payment->date_of_expiration = date('Y-m-d\TH:i:s.vP', strtotime('+60minutes'));
                            $payment->notification_url = base_url('api/v1/update_order').'?user_mp='. $userSettings->id;//base_url('api/v1/update_order'); 
                            $payment->payer = array(
                              "email" => "teste.nienow@email.com",
                              "first_name" => $data['customer']->name,
                              "identification" => array(
                                    "type" => "hash",
                                    "number" => $orderValue->hash
                                )
                            );
                        
                            $payment->save();
                            // var_dump($payment->point_of_interaction->transaction_data->qr_code_base64);
                            // exit();
                            
                            
                            // \MercadoPago\SDK::setAccessToken($userSettings->mp_access_token);
                            // $payment = new \MercadoPago\Payment();

                            // $payment = new \MercadoPago\Payment();
                            // $payment->transaction_amount = $orderValue->price;
                            // $payment->description = $raffle->title;
                            // $payment->external_reference = $orderValue->hash;
                            // $payment->payment_method_id = "pix";
                            // $payment->date_of_expiration = date('Y-m-d\TH:i:s.vP', strtotime('+60minutes'));
                            // //$payment->notification_url = 'https://38c8-201-17-107-207.sa.ngrok.io/rifandos/api/v1/update_order?user_mp='. $userSettings->id;//base_url('api/v1/update_order'); 
                            // $payment->notification_url = base_url('api/v1/update_order').'?user_mp='. $userSettings->id;//base_url('api/v1/update_order'); 
                            
                            
                            
                            // $payment->payer = array(
                           
                            //     "email" => "test@test.com",
                            //     "first_name" => $data['customer']->name,
                            //     "identification" => array(
                            //         "type" => "hash",
                            //         "number" => $orderValue->hash
                            //     )
                            // );
                            
                            /*
                            $payment->payer = array(
                                "type" => "customer",
                                "id" => $userSettings->id,
                                "email" => "teste@gmail.com",
                                "identification" => array(
                                    "type" => "hash",
                                    "number" => $orderValue->hash
                                )
                            );
                            */
                            
                            $orderModel->update($orderValue->id, [
                                'payment_url' => $payment->point_of_interaction->transaction_data->ticket_url,
                                'payment_image' =>$payment->point_of_interaction->transaction_data->qr_code_base64,
                                'payment_qrcode' => $payment->point_of_interaction->transaction_data->qr_code,
                            ]);
                            
                        } else {
                            /* O PAGAMENTO AQUI É NA PAGGUE */
                            /* COMO SÓ POSSO GERAR 1 PAGAMENTO SÓ, VOU TER QUE SALVAR O QRCODE E A IMAGEM */
        
                             // Recuperar o accessToken e id da empresa
                            $payload = array(
                                "client_key"    => $userSettings->paggue_client_key,
                                "client_secret" => $userSettings->paggue_client_secret
                            );
                            
                            $paggue_curl = curl_init();
                        
                            curl_setopt_array($paggue_curl, array(
                                CURLOPT_URL => 'https://ms.paggue.io/payments/api/auth/login', 
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_SSL_VERIFYPEER => 0,
                                CURLOPT_SSL_VERIFYHOST => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => http_build_query($payload),
                            ));
        
                            $auth_response = json_decode(curl_exec($paggue_curl));
        
                            curl_close($paggue_curl);
        
                            $paggue_token = $auth_response->access_token;
                            $paggue_company_id = $auth_response->user->companies[0]->id;
                            
                            // Faz a requisição do pagamento
                            $payload = array(
                                "payer_name"    => $data['customer']->name,
                                "amount"        => $orderValue->price * 100,
                                "external_id"   => $orderValue->hash,
                                "description"   =>  $raffle->title,
                            );
                            
                            $paggue_headers = [
                                "Authorization: Bearer {$paggue_token}",
                                "X-Company-ID: {$paggue_company_id}"
                            ];
                            
                            $curl = curl_init();
        
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => 'https://ms.paggue.io/payments/api/billing_order',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => http_build_query($payload),
                                CURLOPT_HTTPHEADER => $paggue_headers
                            ));
                            
                            $payment_response = json_decode(curl_exec($curl));
                            
                            curl_close($curl);

                            //print_r($payment_response);exit;
            
                            // Debug
                            // $ticket_url = "#";
                            
                            $orderModel->update($orderValue->id, [
                                'payment_url' => '',
                                'payment_image' => '',
                                'payment_qrcode' => ($payment_response->payment)??''
                            ]);

                        }
                    }
   
                }



                $newPayment = [];

                //   $newPayment['url'] = $orderValue->payment_url;

                /* Tá vazio, precisa buscar as informações atualizadas */
                
            
                if(!$orderValue->payment_qrcode){
                    $orderValue = $orderModel->select('payment_qrcode,payment_image, payment_url')->where(['id' => $orderValue->id])->first();
                }

                if($raffle->gateway == 'mp'){
                    /* Mercado pago */
                    $newPayment['image'] = $orderValue->payment_image;
                    $newPayment['url'] = $orderValue->payment_url;

                } else{
                    /* Paggue */
                    require_once(APPPATH.'ThirdParty/phpqrcode/qrlib.php');

                    ob_start();
                    \QRCode::png($orderValue->payment_qrcode, null);
                    $imageString = base64_encode( ob_get_contents() );
                    ob_end_clean();
                    $newPayment['image'] = $imageString;
                }

                $newPayment['qrcode'] = $orderValue->payment_qrcode;

                $data['orders'][$orderKey]->payment = $newPayment;

                // aqui ta valido
            }

            
        }


        //$data['scripts'] = $settingModel->where(['id' => 1])->first();

        /* SE FOI REDIRECIONAMENTO DE PEDIDO APROVADO */
        $data['status'] = filter_var($this->request->getGet('status'), FILTER_SANITIZE_STRIPPED);
        
        if( $data['status'] && $data['status'] == 'approved'){

            $order_id = filter_var($this->request->getGet('order_id'), FILTER_SANITIZE_STRIPPED);

            if($order_id){

                $order = $orderModel->select('id_raffle,numbers')->where(['id' => $order_id])->first();
                $raffle = $raffleModel->select('wp_group')->where(['id' => $order->id_raffle])->first();

                $data['wp_group'] = $raffle->wp_group;
                $data['numbers'] = $order->numbers;
            }

        }
      
        echo view('frontend/partials/header', $data);
        echo view('frontend/my_orders', $data);
        echo view('frontend/partials/footer');
    }

   
    /* Configura a porcentagem da rifa */
    private function setRafflePercent($id){
        
        $raffleModel = new RaffleModel();


        /* Pega a rifa */


        $raffle = $raffleModel->where(['id' => $id])->first();

        /* Pega todos os números da rifa */

        $numbers = json_decode($raffle->numbers);

        $total = count($numbers);
        $total_reserved = 0;

        foreach($numbers as $n){
            if($n->status != 0){
                $total_reserved++;
            }
        }

        $inicio = 0;
        $fim = $total;
        $atual = $total_reserved;

        $total = $fim - $inicio;

		$tempoRestante = $fim - $atual;
		
		
		$percentualParaTerminar =  $tempoRestante / $total;
		$percentualCorrido = 1 - $percentualParaTerminar;
	
	   
		$percentualCorrido = intval($percentualCorrido * 100); 
	
		$percentualCorrido = ($percentualCorrido>100)?100:$percentualCorrido;
		$percentualCorrido = ($percentualCorrido<0)?0:$percentualCorrido;


    
        $raffleModel->update($id, ['percent_level' => $percentualCorrido]);

        return $percentualCorrido;
    }
}
