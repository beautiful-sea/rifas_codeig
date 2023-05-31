<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\RaffleModel;
use App\Models\OrderModel;
use App\Models\UserModel;
use Exception;

class Home extends BaseController
{


    public function index()
    {

        $raffleModel = new RaffleModel();
        $categoryModel = new CategoryModel();

        $data = [
            'title' => "Sistema de rifa",
        ];

        $id_c = filter_var($this->request->getGet('c'), FILTER_SANITIZE_STRIPPED);

        if(isset($id_c) && !empty($id_c)){
            
            if($id_c === 'todas'){
                $data['category'] = (object) ['title' => 'Todas', 'description'=> 'Sua chance de mudar de vida'];
                $data['raffles'] = $raffleModel->select('id, slug, title, images, price, status, draw_date, percent_level')->asObject()->orderBy('id','DESC')->paginate(10);
            } else {
                $data['category'] = $categoryModel->where(['id' => $id_c])->first();

                if($data['category']){
                    $data['raffles'] = $raffleModel->select('id, slug, title, images, price, status, draw_date, percent_level')->where(['id_category' => $id_c])->asObject()->orderBy('id','DESC')->paginate(10);
                } else {
                    return redirect()->to('/');
                }
            }
           
            $data['pager'] = $raffleModel->pager;
        } else {    
          
            /* Busca direta, com limite de 4 */
            $data['raffles'] = $raffleModel->select('id, slug, title, images, price, status, draw_date, percent_level')->where(['status' => 1])->asObject()->orderBy('id','DESC')->findAll(4);
        }

        
        $data['categories'] = $categoryModel->findAll();

        echo view('frontend/partials/header',$data);
        echo view('frontend/home', $data);
        echo view('frontend/partials/footer');
    }

    /* Url da rifa */
    public function raffle($slug){

       
        $data = [

        ];

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

            $data['metas'] = '
                <meta property="og:title" content="'.$data['raffle']->title.'">
                <meta property="og:site_name" content="Rifandos">
                <meta property="og:description" content="'.$data['raffle']->description.' :: Rifandos">
                <meta property="og:url" content="'.base_url().'/'.$data['raffle']->slug.'">
                <meta property="og:type" content="article">
                <meta property="article:author" content="Rifandos">
                <meta property="article:section" content="SEO">
                <meta property="article:tag" content="Rifas, melhor site de rifas, rifa automática, pagamento por pix">
                <meta property="article:published_time" content="'.date('Y-m-d H:i:s',strtotime($data['raffle']->created_at)).'.0">
                <meta property="og:image" content="'.$image_og.'">
                <meta property="og:image:type" content="image/'.$image_type.'">
                <meta property="og:image:width" content="800"> 
                <meta property="og:image:height" content="600"> 
                <meta property="og:locale" content="pt_BR">
            ';

            $data['categories'] = $categoryModel->findAll();
            
            echo view('frontend/partials/header', $data);
            
            if($data['raffle']->type){
                /* Rifa manual */
                echo view('frontend/raffle_manual', $data);
            } else {
                /* Rifa automática */

                /* Faz um cálculo rápido pra ver a quantidade de números disponíveis na rifa */
                $count = 0;
                foreach( json_decode($data['raffle']->numbers) as $number ){
                    if($number->status == 0){
                        $count++;
                    }
                }
                $data['quantity_of_avaiable_numbers'] = $count;

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

                $raffle_id = $this->request->getPost('raffle_id');
                $name = $this->request->getPost('name');
                $phone = $this->request->getPost('phone');
                $email = $this->request->getPost('email');
                $numbers = $this->request->getPost('numbers');

                /* Verifica se o usuário já é cliente */

                $userModel = new UserModel();
                
                $user = $userModel->where(['phone' => $phone])->first();

                if(!$user){
                
                    // Cadastra e cria um id
                    $newUser = [
                        "name" => ucwords($name),
                        "email" => mb_strtolower($email),
                        "phone" => $phone,
                    ];

                    $userModel->save( $newUser );
                    $userId = $userModel->getInsertID();

                    $user = $userModel->where('id',$userId)->first();

                }

                /* Joga o user na session */
                session()->set('user', $user);

                // pega a rifa 
                $raffle = $raffleModel->where('id', $raffle_id)->first();

                // pega a data e hora atual
                $dateNow = new \DateTime('now');

                /* SE A RIFA EXISTE E ESTÁ DISPONÍVEL */
                if($raffle && $raffle->status == 1){
                    
                    if(\DateTime::createFromFormat($raffle->draw_date, 'Y-m-d H:i:s')){
                        $raffleDrawDate = new \DateTime($raffle->draw_date);

                        if($raffleDrawDate < $dateNow){
                            // Rifa expirada
                            session()->setFlashdata('status', ['message' => 'Rifa expirada','status' => 'error']);
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
                        $newOrder = [
                            "id_user" => $user->id,
                            "id_raffle" => $raffle->id,
                            "status" => 0,
                            "numbers" => json_encode($newNumbers),
                            "price" => $price,
                            "original_price" => $original_price,
                            "expires_in" => strtotime('+10minutes')
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
                        session()->set('user', $user);

                        /* Atualiza a porcentagem da rifa */
                        $this->setRafflePercent($raffle->id);
                        
                        // direciona para meus pedidos, onde terá o botão de checkout 
                        return redirect()->to('/meus-pedidos');
                        

                    } else {
                        // Retorna com erro de números indisponíveis
                        session()->setFlashdata('status', ['message' => 'Número(s) expirado(s).','status' => 'error']);
                        return redirect()->to('/'.$raffle->slug);
                    }
                }
                
            } else {
                echo $validation->listErrors();
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

            if($this->validate($rules)){

                $raffleModel = new RaffleModel();

                $raffle_id = $this->request->getPost('raffle_id');
                $name = $this->request->getPost('name');
                $phone = $this->request->getPost('phone');
                $email = $this->request->getPost('email');
                $numbers = $this->request->getPost('numbers');

                /* Verifica se o usuário já é cliente */

                $userModel = new UserModel();
                
                $user = $userModel->where(['phone' => $phone])->first();

                if(!$user){
                
                    // Cadastra e cria um id
                    $newUser = [
                        "name" => ucfirst($name),
                        "email" => mb_strtolower($email),
                        "phone" => $phone,
                    ];

                    $userModel->save( $newUser );
                    $userId = $userModel->getInsertID();

                    $user = $userModel->where('id',$userId)->first();

                }

                /* Joga o user na session */
                session()->set('user', $user);

                // pega a rifa 
                $raffle = $raffleModel->where('id', $raffle_id)->first();

                // pega a data e hora atual
                $dateNow = new \DateTime('now');

                /* SE A RIFA EXISTE E ESTÁ DISPONÍVEL */
                if($raffle && $raffle->status == 1){
                    
                    if(\DateTime::createFromFormat($raffle->draw_date, 'Y-m-d H:i:s')){
                        $raffleDrawDate = new \DateTime($raffle->draw_date);

                        if($raffleDrawDate < $dateNow){
                            // Rifa expirada
                            session()->setFlashdata('status', ['message' => 'Rifa expirada.','status' => 'error']);
                            return redirect()->to('/'.$raffle->slug);
                        }
                    }

                    // Agora começa a verificar se os números estão disponíveis
                    $quantityOfNumbers = $numbers;
                    $raffleNumbers = json_decode($raffle->numbers);

                    $matrizIndexes = array();
                    $newNumbers = array();

                    /* Verifica se a rifa possui a quantidade de números disponíveis */

                    /* Pega a quantidade de números disponíveis na rifa */

                    $raffleAvailableNumbers = 0;

                    $avaiables = [];
                    foreach($raffleNumbers as $keyRaffleNumber => $valueRaffleNumber){

                        if($valueRaffleNumber->status == 0){
                            // O número está disponível 
                            $raffleAvailableNumbers++;
                            $avaiables[] = ['id'=>$keyRaffleNumber, 'number' =>$valueRaffleNumber->number];

                        }
                    }
           
                    /* Se existir números disponíveis */
                    if($raffleAvailableNumbers > 0){

                        /* Se a quantidade de números exceder a quantidade de números, pega o total */
                        if($quantityOfNumbers > $raffleAvailableNumbers){
                            $quantityOfNumbers = $raffleAvailableNumbers;
                        }

                        $myNumbers = [];

                        //echo $quantityOfNumbers;exit;
        
                        

                        // gera um número entre o intervalo de números disponíveis
                        
                        shuffle($avaiables);

                        foreach($avaiables as $av){

                            if (count($myNumbers) < $quantityOfNumbers) {
                                // Verifica novamente se o número está disponível e se já não foi escolhido
                                if(isset($raffleNumbers[$av['id']]) && $raffleNumbers[$av['id']]->status == 0 && !in_array($av['number'],$myNumbers)){
                                    // Adiciona ao array myNumbers */
                                    $myNumbers[] = $raffleNumbers[$av['id']]->number;
                                }
                            }
                        }
                        

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
                                    if ($quantityOfNumbers == $pack->qnt_numbers){
                                        $price = currencyToDecimal($pack->price);
                                    }
                                }
                            }
                           
                            $newOrder = [
                                "id_user" => $user->id,
                                "id_raffle" => $raffle->id,
                                "status" => 0, // 1 pra pagamento aprovado
                                "numbers" => json_encode($newNumbers),
                                "expires_in" => strtotime('+10minutes'),
                                "price" => $price,
                                "original_price" => $original_price
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
                            session()->set('user', $user);
                            

                            /* Atualiza a porcentagem da rifa */
                            $this->setRafflePercent($raffle->id);
                            
                            // direciona para meus pedidos, onde terá o botão de checkout 
                            return redirect()->to('/meus-pedidos');
                            
                            
                        } else {
                            /* Todas as rifas foram compradas */
                            session()->setFlashdata('status', ['message' => 'Os números já foram esgotados','status' => 'error']);
                            return redirect()->to('/');
                        }   
                        

                    } else {
                        // Retorna com erro de números indisponíveis
                        session()->setFlashdata('status', ['message' => 'Os números já foram esgotados','status' => 'error']);
                        return redirect()->to('/');
                    }
                }
                
            } else {
                session()->setFlashdata('status', ['message' => 'Ocorreu um erro, verifique os dados e tente novamente','status' => 'error']);
                return redirect()->to('/');
            }


        } 



    }


    /* Pesquisa pelos pedidos */
    public function findMyOrders() {
       
        $userModel = new UserModel();

        $validation = \Config\Services::Validation();

        $phone = $this->request->getJson('phone');

        $user = $userModel->where(['phone' => $phone])->first();
        
        if($user){
            session()->set('user', $user);
            echo json_encode(['result'=>true, 'user' => json_encode($user, JSON_UNESCAPED_UNICODE)], JSON_UNESCAPED_UNICODE);
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
            'status' => '',
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
        $data['user'] = session()->get('user');
        //$data['request'] =  session()->get('request');
        
        if(!$data['user']){
            /* VOLTA PRA RIFA */
            return redirect()->back();
        }
        $orderModel = new OrderModel();
        $raffleModel = new RaffleModel();
        $categoryModel = new CategoryModel();
        
        /* Pega os pedidos do cliente */
        $data['orders'] = $orderModel->where('id_user',$data['user']->id)->orderBy('id', 'DESC')->findAll();

        /* Pega alguns dados complementares da rifa */
        foreach($data['orders'] as $orderKey => $orderValue){

            $raffle = $raffleModel->select('id,title, slug, images,wp_group, gateway')->where(['id' => $orderValue->id_raffle])->orderBy('id', 'DESC')->asObject()->first();
            $data['orders'][$orderKey]->raffle = $raffle;
            
            if ($orderValue->status == 0){

                /* Verifica se o pagamento ainda é válido */
                if( time() < intval($orderValue->expires_in) ){

                    /* O pedido ainda não foi processado pelo gateway de pagamento */
                    if(!$orderValue->payment_qrcode){

                        if($raffle->gateway == 'mp'){

                            $payment = new \MercadoPago\Payment();
                            $payment->transaction_amount = $orderValue->price;
                            $payment->description = $raffle->title;
                            $payment->external_reference = $orderValue->id;
                            $payment->payment_method_id = "pix";
                            $payment->date_of_expiration = date('Y-m-d\TH:i:s.vP', strtotime('+10minutes'));
                            $payment->notification_url = base_url('api/v1/update_order'); 
                            $payment->payer = array(
                                "email" => "test@test.com",
                                "first_name" => $data['user']->name,
                                "identification" => array(
                                    "type" => "customer",
                                    "number" => $data['user']->id
                                )
                            );
                            
                            $payment->save();
        
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
                                "client_key"    => PAGGUE_CLIENT_KEY,
                                "client_secret" => PAGGUE_CLIENT_SECRET
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
                                "payer_name"    => $data['user']->name,
                                "amount"        => $orderValue->price * 100,
                                "external_id"   => $orderValue->id,
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
            
                            // Debug
                            // $ticket_url = "#";
                            
                            $orderModel->update($orderValue->id, [
                                'payment_url' => '',
                                'payment_image' => '',
                                'payment_qrcode' => $payment_response->payment
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

                // aqui aqui ta valido
            }

            
        }

        echo view('frontend/partials/header', $data);
        echo view('frontend/my_orders', $data);
        echo view('frontend/partials/footer');
    }

    /* Checkout */
    public function checkout()
    {
        $data['title'] = "Checkout";

        $categoryModel = new CategoryModel();


        echo view('frontend/partials/header', $data);
        echo view('frontend/checkout', $data);
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
