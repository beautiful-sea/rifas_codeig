<?php

namespace App\Controllers;

use App\Models\RaffleModel;
use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\SettingModel;
use App\Models\CustomerModel;
use App\Models\UserModel;
use App\Models\WinnerModel;

use Exception;

class Dashboard extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $data = [
            'title' => 'Dashboard',
            'active' => 'dashboard'
        ];

        /* Pego todas as ordens pendentes de pagamento */
        $data['profit'] = $orderModel->select('(SELECT SUM(price) FROM orders WHERE status = 1) as profit')->where('id_user', session()->get('user')['id'])->first()->profit ?? 0;
        $data['pending_profit'] = $orderModel->select('(SELECT SUM(price) FROM orders WHERE status = 0) as pending_profit')->where('id_user', session()->get('user')['id'])->first()->pending_profit ?? 0;
        $data['count_orders'] = $orderModel->select('(SELECT COUNT(*) FROM orders) as count_orders')->where('id_user', session()->get('user')['id'])->first()->count_orders ?? 0;
        $data['count_pending_orders'] = $orderModel->select('(SELECT COUNT(*) FROM orders WHERE status = 0) as count_pending_orders')->where('id_user', session()->get('user')['id'])->first()->count_pending_orders ?? 0;

        
        echo view('backend/partials/header', $data);
        echo view('backend/home',$data);
        echo view('backend/partials/footer');
    }

    /* PEDIDOS */
    public function orders()
    {

        $ordersModel = new OrderModel();
        $customerModel = new CustomerModel();
        $raffleModel = new RaffleModel();

        $data = [
            'title' => 'Pedidos',
            'active' => 'pedidos',
            'orders' => []
        ];

        /* Define o ganhador da rifa */

        $id_customer = $this->request->getVar('id_customer');
        $name = $this->request->getVar('name');
        $id_raffle = $this->request->getVar('id_raffle');
        $number = $this->request->getVar('number');
        $position = $this->request->getVar('position');

        // DEFINE O GANHADOR E ENCERRA A RIFA
        if($id_customer && $name && $id_raffle && $number && $position)
        {
            /* Adiciono o ganhador, finalizo a rifa e volto para pedidos */

            // Busco se a rifa já tem ganhador 

            $raffle = $raffleModel->select('title, id, winners, draw_date')->where(['id' => $id_raffle, 'id_user' => session()->get('user')['id']])->first();

            if($raffle) {

                $winners = json_decode($raffle->winners, true);
                $winners[$position] = ["name" => ucwords($name),"number" => $number, "position" => $position, "id_customer" => $id_customer]; 
                
            } else {

                $winners = [
                    $position => [
                        "name" => $name,
                        "position" => $position,
                        "number" => $number,
                        "id_customer" => $id_customer,
                    ]
                ];

            }

            if(!preg_match('/[1-9]/', $raffle->draw_date)){
                $updatedRaffle = ["winners" => json_encode($winners), "status" => 2, "draw_date" => date('Y-m-d H:i:s')];
            }   else {
                $updatedRaffle = ["winners" => json_encode($winners), "status" => 2]; // 2 de pausada ou encerrada
            }

            $raffleModel->update($id_raffle, $updatedRaffle); 
            /* Notifica que o pedido foi aprovado com sucesso! */
            session()->setFlashdata('status',['message' => 'Dados atualizados com sucesso', 'status' => 'success']);
        
            return redirect()->to(base_url());



        }
 
        // PEGO AS RIFAS PRA FILTRAGEM
        $data['raffles'] = $raffleModel->select('id, title')->where(['id_user' => session()->get('user')['id']])->findAll();
        
        $data['search'] = $this->request->getGet('search');
        $data['status'] = $this->request->getGet('status');
        $data['id_raffle'] = $this->request->getGet('id_raffle');


        $ordersModel->select('orders.*, customers.name, raffles.title');
        $ordersModel->join('raffles','raffles.id = orders.id_raffle','LEFT');
        $ordersModel->join('customers','customers.id = orders.id_customer','LEFT');
                
        // PESQUISA PEDIDOS DE UMA ÚNICA RIFA
        if ($data['search'] || $data['status']) {

            if($data['status']){
                $ordersModel->where(['orders.status' => ($data['status'] == 'pago')? 1:0]);
            }

            if($data['id_raffle']){
                $ordersModel->where(['raffles.id' => $data['id_raffle']]);
            } 

            if($data['search']){
                $ordersModel->like('raffles.title', $data['search'], 'match')
                ->orLike('customers.name',$data['search'], 'match')
                ->orLike('orders.numbers',$data['search'], 'match'); 
            }


            $ordersModel->where('orders.id_user', session()->get('user')['id']);
            $data['orders'] = $ordersModel->orderBy('orders.id','DESC')->paginate(10);

            //echo $ordersModel->getLastQuery();exit;

        } else{

            // Pego os pedidos apenas das rifas do cliente
            $ordersModel->where('orders.id_user', session()->get('user')['id']);
            $data['orders'] = $ordersModel->orderBy('orders.id','DESC')->paginate(10);
        }

        $data['pager'] = $ordersModel->pager;

    
        if($data['orders']){
            
            foreach($data['orders'] as $key => $o){

                $raffle = $raffleModel->select('title')->where(['id' => $o->id_raffle])->first();
                $customer = $customerModel->select('name, phone')->where(['id' => $o->id_customer])->first();

                $data['orders'][$key]->customer = $customer; 
                $data['orders'][$key]->raffle = $raffle; 
            }

        }

        echo view('backend/partials/header', $data);
        echo view('backend/orders',$data);
        echo view('backend/partials/footer');
    }


     /* RANQUEAMETNO DOS COMPRADORES */
    public function ranking()
    {
 
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        $raffleModel = new RaffleModel();
 
        $data = [
            'title' => 'Ranqueamento compradores',
            'active' => 'ranqueamento'
        ];

        $data['id_raffle'] = $this->request->getGet('id_raffle');
        $data['raffles'] = $raffleModel->select('id, title')->where('id_user', session()->get('user')['id'])->findAll();

        if($data['id_raffle']) {
            /* Pego a rifa */
            $data['raffle'] = $raffleModel->select('id, title')->where(['id' => $data['id_raffle'], 'id_user' => session()->get('user')['id'] ])->first();
            
            
            /* Pego os pedidos dessa rifa */
            $data['ranking_orders'] = $orderModel->query(" SELECT id_customer, SUM(quantity) as totalNumbers, SUM(price) as totalPrice  FROM orders WHERE id_raffle = '".$data['raffle']->id."' AND status = '1' GROUP BY id_customer ORDER BY totalNumbers DESC LIMIT 5")->getResult();


            /* Pego os dados do cliente de cada pedido */
            if($data['ranking_orders']){

    
                foreach($data['ranking_orders'] as $keyOrder => $o){
                    $data['ranking_orders'][$keyOrder]->customer = $customerModel->where(['id' => $o->id_customer])->first();
                }
            }

            //print_r($data['ranking_orders']);exit;


             /* Pega o ganhador de maior ocorrencia */
            //$rankingOrder = $orderModel->query(" SELECT id_user, SUM(quantity) as totalNumbers  FROM orders WHERE id_raffle = '".$raffle->id."' AND status = '1' GROUP BY id_user ORDER BY totalNumbers DESC LIMIT 1")->getRow();
            /*
            if($rankingOrder){
                $data['raffles'][$raffle_key]->user = $customerModel->select('id,name,phone')->where('id',$rankingOrder->id_user)->first();
                //$data['raffles'][$raffle_key]->user->quantity = $rankingOrder->totalNumbers;
            }
            */
          

            //echo $ordersModel->getLastQuery();exit;

        } 

        echo view('backend/partials/header', $data);
        echo view('backend/ranking',$data);
        echo view('backend/partials/footer');
    }
 

    /* Excluir pedido */

    public function delete_order($id){
        
        $orderModel = new OrderModel();
        $raffleModel = new RaffleModel();

        $order = $orderModel->where(['id' => $id])->first();

        $numbers = json_decode($order->numbers);
        // var_dump($numbers);
        // exit();

        if($order){

            $raffle = $raffleModel->where(['id' => $order->id_raffle])->first();

            if($raffle){

                $raffle_numbers = json_decode($raffle->numbers);

                /* Deixa o número ativo novamente */
                foreach($numbers as $n){

                    $i = array_search($n, array_column($raffle_numbers, 'number'));
                    
                    $raffle_numbers[$i]->status = 0;
                    $raffle_numbers[$i]->order_id = 0;
                    $raffle_numbers[$i]->user = '';
                    $raffle_numbers[$i]->reserved_at = 0;

                }

                $raffleModel->update($raffle->id, [ 'numbers' => json_encode($raffle_numbers)]);
                $this->setRafflePercent($raffle->id);

            }


            /* Por fim, remove o pedido */
            $orderModel->delete($id);

            
            /* Notifica que o pedido foi excluído com sucesso! */
            session()->setFlashdata('status',['message' => 'Pedido excluído com sucesso', 'status' => 'success']);
            
        }

        return redirect()->to(base_url('/dashboard/pedidos'));

    }

    /* Aprovar o pedido */
    public function approve_order($id){
        
        $orderModel = new OrderModel();
        $raffleModel = new RaffleModel();
        $customerModel = new CustomerModel();

        $order = $orderModel->where(['id' => $id])->first();

        $numbers = json_decode($order->numbers);

        if($order){

            $raffle = $raffleModel->where(['id' => $order->id_raffle])->first();
            $customer = $customerModel->select('name')->where(['id' => $order->id_customer])->first();

            if($raffle){

                $raffle_numbers = json_decode($raffle->numbers);

                /* Deixa o número ativo novamente */
                foreach($numbers as $n){

                    $i = array_search($n, array_column($raffle_numbers, 'number'));
                    
                    $raffle_numbers[$i]->status = 2; // PEDIDO CONFIRMADO, 1 é aguardando pagamento
                    $raffle_numbers[$i]->order_id = $order->id;
                    $raffle_numbers[$i]->user = $customer->name;
                    $raffle_numbers[$i]->reservet_at = $order->created_at;

                }

                $raffleModel->update($raffle->id, [ 'numbers' => json_encode($raffle_numbers)]);
            }

            /* Por fim, aprova o pedido */
            $orderModel->update($id, ['status' => 1]); // passa pra pedido confirmado

            /* Notifica que o pedido foi aprovado com sucesso! */
            session()->setFlashdata('status',['message' => 'Pedido aprovado com sucesso', 'status' => 'success']);  
        }
        return redirect()->to(base_url('/dashboard/pedidos'));
    }

    public function aprovar()
    {
        $orderModel = new OrderModel();
        $raffleModel = new RaffleModel();

        $orderId = $this->request->getPost('order');
        $raffle_numbers = json_decode($this->request->getPost('numbers'));

        $order = $orderModel->where(['id' => $orderId])->first();
        

        if($order){

            $raffle = $raffleModel->where(['id' => $order->id_raffle])->first();

            if($raffle){

                $raffleModel->update($raffle->id, [ 'numbers' => json_encode($raffle_numbers)]);
            }

            /* Por fim, aprova o pedido */
            $orderModel->update($orderId, ['status' => 1]); // passa pra pedido confirmado

            /* Notifica que o pedido foi aprovado com sucesso! */
            session()->setFlashdata('status',['message' => 'Pedido aprovado com sucesso', 'status' => 'success']);  
            echo json_encode('success');
        }
        
    }


    /* RIFAS */
    public function raffles()
    {


        $raffleModel = new RaffleModel();
        $categoryModel = new CategoryModel();
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();

        $data = [
            'title' => 'Rifas',
            'active' => 'rifas'
        ];

        $data['search'] = $this->request->getGet('search');
        $data['type'] = $this->request->getGet('type');
        $data['id_category'] = $this->request->getGet('c');
        
        $raffleModel->select('categories.title, raffles.*');
        $raffleModel->join('categories','raffles.id_category = categories.id','LEFT');

        if($data['search'] || $data['type'] || $data['id_category']){
            
            if ($data['search']){
                $raffleModel->like('raffles.title', $data['search'], 'match')
                    ->orLike('description',$data['search'],'match')
                    ->orLike('slug',$data['search'],'match');
            }
            
            if ($data['type']){
                $raffleModel->where(['raffles.status' => $data['type'] == 'active'? 1:0]);
            }
            if ($data['id_category']) {
                $raffleModel->where(['id_category' => $data['id_category']]);
            }
        }

        
        
        $data['categories'] = $categoryModel->findAll();
        $data['raffles'] = $raffleModel->where(['id_user' => session()->get('user')['id']])->orderBy('id', 'DESC')->paginate(10);


        if($data['raffles']){

            foreach($data['raffles'] as $raffle_key => $raffle){
                // pego os pedidos de cada uma
                $order = $orderModel->select('SUM(price) as total_paid, SUM(quantity) as total_quantity')->where(['status' => 1, 'id_raffle' => $raffle->id])->first();

                $data['raffles'][$raffle_key]->free_total = $raffle->number_of_numbers - $order->total_quantity;
                $data['raffles'][$raffle_key]->free = ($raffle->number_of_numbers - $order->total_quantity) * $raffle->price;

                $data['raffles'][$raffle_key]->paid = $order->total_paid;
                $data['raffles'][$raffle_key]->paid_total = $order->total_quantity;

                //$data['raffles'][$raffle_key]->paid = $paid * $raffle->price;
                //$data['raffles'][$raffle_key]->paid_total = $paid;

                //$data['raffles'][$raffle_key]->reserved = $reserved * $raffle->price;
                // $data['raffles'][$raffle_key]->reserved_total = $reserved;

                $data['raffles'][$raffle_key]->total = ($raffle->number_of_numbers * $raffle->price);

                // DEFINO O QRCODE PRO PAGAMENTO DA RIFA 
                if($raffle->payment_status == 0)
                {
                    // PAGGUE
                    require_once(APPPATH.'ThirdParty/phpqrcode/qrlib.php');

                    ob_start();
                    \QRCode::png($raffle->payment_qrcode, null);
                    $imageString = base64_encode( ob_get_contents() );
                    ob_end_clean();

                    $newPayment['image'] = $imageString;
                    $newPayment['qrcode'] = $raffle->payment_qrcode;

                    $data['raffles'][$raffle_key]->payment = $newPayment;

                }
            }
    


        }
   
    
        /*
        foreach($data['raffles'] as $raffle_key => $raffle){
            
            $numbers = json_decode($raffle->numbers);

            $paid = 0;
            $reserved = 0;
            $free = 0;
            $total = 0;

            foreach($numbers as $n){

                // Pego o status do número para calcular os lucros 
                switch($n->status){
                    case 0:
                        $free++;
                        break;
                    case 1:
                        $reserved++;
                        break;
                    case 2:
                        $paid++;
                        break;
                }

                $total++;
            }

         
            

            $data['raffles'][$raffle_key]->free = $free * $raffle->price;
            $data['raffles'][$raffle_key]->free_total = $free;

            $data['raffles'][$raffle_key]->paid = $paid * $raffle->price;
            $data['raffles'][$raffle_key]->paid_total = $paid;

            $data['raffles'][$raffle_key]->reserved = $reserved * $raffle->price;
            $data['raffles'][$raffle_key]->reserved_total = $reserved;

            $data['raffles'][$raffle_key]->total = $total * $raffle->price;

            //print_r($raffle);exit;

          

            
            

        }
        */

        
        

        $data['pager'] = $raffleModel->pager;


        echo view('backend/partials/header', $data);
        echo view('backend/raffles',$data);
        echo view('backend/partials/footer');
    }

    /* ADICIONAR RIFA */
    public function add_raffle()
    {
        $data = [
            'title' => 'Adicionar rifa',
            'active' => 'add_rifa'
        ];


        $categoryModel = new CategoryModel();
        $data['categories'] = $categoryModel->findAll();

        if($this->request->getMethod() == 'post'){

            $validation =  \Config\Services::validation();

            $rules = [
                "title" => [
                    "label" => "title", 
                    "rules" => "required|trim|min_length[5]|max_length[100]"
                ],
                "number_of_numbers" => [
                    "label" => "number_of_numbers", 
                    "rules" => "required|min_length[1]|max_length[6]"
                ],
                "price" => [
                    "label" => "price", 
                    "rules" => "required|trim"
                ],
                "type" => [
                    "label" => "type", 
                    "rules" => "required"
                ]
            ];

            if( $this->validate($rules)){

                $title = $this->request->getPost('title');
                /* generate slug */
                $slug = slug($title,'-');

                $number_of_numbers = $this->request->getPost('number_of_numbers');

                /* generate json numbers */
                $numbers = $number_of_numbers;

                switch($numbers)
                {
                    case '10':
                        $price_rate = 2.50;
                        break;
                    case '20':
                        $price_rate = 5.00;
                        break;
                    case '50':
                        $price_rate = 10.00;
                        break;
                    case '100':
                        $price_rate = 14.90;
                        break;
                    case '500':
                        $price_rate = 34.90;
                        break;
                    case '1000':
                        $price_rate = 49.90;
                        break;
                    case '10000':
                        $price_rate = 99.90;
                    case '20000':
                        $price_rate = 159.90;
                        break;
                    case '100000':
                        $price_rate = 299.90;
                        break;
                }

                $arr = [];

                for($x = 0;$x < $numbers; $x++){
                    $arr[$x] = [
                        'number' => str_pad($x, strlen((string)$numbers),  '0',STR_PAD_LEFT),
                        'status' => 0, // 0 disponivel, 1 reservado, 2 pago
                        'user' => '', // dados do usuário
                        'reserved_at' => '', // data e horário da reserva
                    ];
                }

                $numbers = json_encode($arr);
                $price = currencyToDecimal($this->request->getPost('price'));
                $id_category = $this->request->getPost('id_category');
                $type = $this->request->getPost('type');
                $gateway = $this->request->getPost('gateway');

                $draw_date = $this->request->getPost('draw_date');
                $description = $this->request->getPost('description'); 

                $discount_status = $this->request->getPost('discount_status')?? false;
                $discount_type = $this->request->getPost('discount_type') ?? false;
                $discount_quantity = $this->request->getPost('discount_quantity')?? false;
                $discount_price = currencyToDecimal($this->request->getPost('discount_price')) ?? false;
                $wp_group = $this->request->getPost('wp_group');
                $pixels = $this->request->getPost('pixels'); 

                /* Verificador simples pra disconto */
                if($discount_status){
                    if($discount_quantity == false or $discount_price == false){
                        $discount_status = false;
                    }
                }
                
                $packs = $this->request->getPost('packs');
                
            
                $raffleModel = new RaffleModel();
                
                $newData = array(
                    'id_user' => session()->get('user')['id'],
                    'hash' => md5(session()->get('user')['id'].time().rand(0,9999)),
                    'title' => $title,
                    'slug' => $slug,
                    'number_of_numbers' => $number_of_numbers,
                    'numbers' => $numbers,
                    'price' => $price,
                    'payment_price' => $price_rate,
                    'payment_status' => 2, // rifa pendente de pagamento
                    'packs' => $packs, 
                    'status' => 0, // Rifa pausada, 1 - rifa ativa - 2  - rifa encerrada
                    'id_category' => $id_category,
                    'type' => $type === 'auto'? 0 : 1,
                    'parcial' => $type === 'auto' ? 0 : 1,
                    'gateway' => $gateway ?? 'mp',
                    'draw_date' => $draw_date,
                    'description' => $description,
                    'wp_group' => $wp_group,
                    'pixels' => $pixels,
                    'discount_status' => $discount_status === 'on' ? 1 : 0,
                    'discount_type' => $discount_type,
                    'discount_quantity' => $discount_quantity,
                    'discount_price' => $discount_price,
                    'show_percent_level' => 1,
                    'percent_level' => 0
                );

        
                /* Verifica se enviou as imagens */
                if ($this->request->getFileMultiple('files')) {

                    include(APPPATH . 'ThirdParty/class.fileuploader.php');
                    
                    $images = [];
                    
                    // initialize the FileUploader
                    $FileUploader = new \FileUploader('files', array(
                        'uploadDir' => 'public/images/',
                        'title' => ['auto', 15],
                        'fileMaxSize' => 4,
                        'extensions' => ['jpg','jpeg','png','webp'],
                    ));
                
                    // call to upload the files
                    $upload = $FileUploader->upload();
                
                    if($upload['isSuccess']) {
                        // get the uploaded files
                        $files = $upload['files'];

                        if($files){
                            foreach($files as $f){
                                $images[] = $f['name'];

                                /* Dá o resize 
                                try {

                                    $crop = [
                                        'left' => 0,
                                        'top' => 0,
                                        'width' => 672,
                                        'height' => 639,
                                        'cfWidth' => 0.36,
                                        'cfHeight' =>  0.366

                                    ];
                                    $res = \FileUploader::resize(str_replace('\\','/',FCPATH)  . $f['file'] , null, null, null, $crop, 70, null);
                                   
                                }catch(Exception $e){

                                }
                                */
                                
                            }
                        }
                    } else {
                        // get the warnings
                        $warnings = $upload['warnings'];

                        session()->setFlashdata('status',['message' => 'Tipo de imagem inválida.', 'status' => 'error']);
                        return redirect()->to('/dashboard/rifas/adicionar');
                    }

                }
                
                $newData['images'] = json_encode($images);

                $raffleModel->save($newData);
                $raffle_id = $raffleModel->getInsertID();
                
                // Atualizando Slug com ID (para ser unico)
                $raffleModel->update($raffle_id, ['slug' => $raffle_id . '-' . $slug]);

                
                //if($raff)

                $raffle = $raffleModel->select('id,hash,title,payment_price, payment_qrcode')->where('id',$raffle_id)->first();

                // INSTANCIA O PAGAMENTO NA PAGGUE 
                if(!$raffle->payment_qrcode){

                    $payload = array(
                        "client_key"    => "670606519245911678521929",
                        "client_secret" => "120379377482010536468"
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
                        "payer_name"    => session()->get('user')['name'],
                        "amount"        => $raffle->payment_price * 100,
                        "external_id"   => $raffle->hash,
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
                        

                    // ATUALIZA O STATUS DO PAGAMENTO
                    $raffleModel->update($raffle->id, [
                        'payment_qrcode' => ($payment_response->payment)??''
                    ]);


                }

                
                
                session()->setFlashdata('status',['message' => 'Rifa adicionada com sucesso.', 'status' => 'success']);
                return redirect()->to('/dashboard/rifas');

            } else {
                // FAIL
                session()->setFlashdata('status',['message' => 'Falha ao adicionar rifa, verifique os dados e tente novamente', 'status' => 'error']);
                return redirect()->to('/dashboard/rifas/adicionar');
            }
        }

        echo view('backend/partials/header', $data);
        echo view('backend/add_raffle',$data);
        echo view('backend/partials/footer');
        
    }

    /* EDITAR RIFA */
    public function edit_raffle($id)
    {
        $data = [
            'title' => 'Editar rifa',
            'active' => 'edit_rifa',
            'images' => ''
        ];

        $raffleModel = new RaffleModel();
        $categoryModel = new CategoryModel();

        $data['raffle'] = $raffleModel->where(['id' => $id, 'id_user' => session()->get('user')['id']])->first();
        $data['categories'] = $categoryModel->findAll();
        

        if($data['raffle']){

            if($this->request->getMethod() == 'post'){

                $validation =  \Config\Services::validation();
    
                $rules = [
                    "title" => [
                        "label" => "title", 
                        "rules" => "required|trim|min_length[5]|max_length[100]"
                    ],
                    "price" => [
                        "label" => "price", 
                        "rules" => "required|trim"
                    ]
      
                ];
                
                if( $this->validate($rules)){
                    
                    include(APPPATH . 'ThirdParty/class.fileuploader.php');

                    $title = $this->request->getPost('title');
                    /* generate slug */
                    //$slug = slug($title,'-',true);
                    $number_of_numbers = $this->request->getPost('number_of_numbers');
    
                    /* generate json numbers */
                    $numbers = $number_of_numbers;
    
                    if($number_of_numbers >= 100000){
                        $numbers = 100000;
                    }
    
                    $arr = [];
    
                    for($x = 0;$x < $numbers; $x++){
                        $arr[$x] = [
                            'number' => str_pad($x, strlen((string)$numbers),  '0',STR_PAD_LEFT),
                            'status' => 0, // 0 disponivel, 1 reservado, 2 pago
                            'user' => '', // dados do usuário
                            'reserved_at' => '', // data e horário da reserva
                        ];
                    }
    
                    $numbers = json_encode($arr);
                    $price = currencyToDecimal($this->request->getPost('price'));
                    $id_category = $this->request->getPost('id_category');
                   
                    $draw_date = $this->request->getPost('draw_date');
                    $description = $this->request->getPost('description');
                    $wp_group = $this->request->getPost('wp_group');
                    $gateway = $this->request->getPost('gateway');
                    $pixels = $this->request->getPost('pixels'); 
                    
                    $discount_status = $this->request->getPost('discount_status')?? false;
                    $discount_type = $this->request->getPost('discount_type') ?? false;
                    $discount_quantity = $this->request->getPost('discount_quantity');
                    $discount_price = currencyToDecimal($this->request->getPost('discount_price'));
                    $current_images = $this->request->getPost('fileuploader-list-files');


                    /* Verificador simples pra disconto */
                    if($discount_status){
                        if($discount_quantity == '' or $discount_price == ''){
                            $discount_status = false;
                        }
                    }
                        
                    $packs = $this->request->getPost('packs');
    
                    $raffleModel = new RaffleModel();
                    
                    $newData = array(
                        'title' => $title,
                        //'slug' => $slug,
                        'price' => $price,
                        'id_category' => $id_category,
                        'packs' => $packs,
                        'draw_date' => $draw_date,
                        'description' => $description,
                        'pixels' => $pixels,
                        'gateway' => $gateway ?? 'mp',
                        'wp_group' => $wp_group,
                        'discount_status' => $discount_status === 'on' ? 1 : 0,
                        'discount_type' => $discount_type,
                        'discount_quantity' => $discount_quantity,
                        'discount_price' => $discount_price,
                    );

                    /* EDIÇÃO DE IMAGEM */
                    if(isset($_POST['fileuploader-list-files'])){
                        $files = json_decode($_POST['fileuploader-list-files'], true);
                        
                        if($files){
                            
                            foreach($files as $file){

                                $editor = $file['editor'] ?? false;
                            
                                $filename = explode('/',$file['file']);
                                $filename = end($filename);
                                
                                \FileUploader::resize(FCPATH . 'public/images/' . $filename , null, null, null, $editor['crop']??null, 90, $editor['rotation']??null);
                            
                            }
                        
                        }

                    }


                    /*Verifica se foi exluída alguma imagem antiga */
                    if($current_images) {

                        $current_images = json_decode($current_images);

                        $current = [];
                        foreach($current_images as $img){
                            $current[] = $img = substr($img->file, strrpos($img->file, '/') + 1);
                        }

                        $raffle_images = json_decode($data['raffle']->images);
                        
                        $images = [];
                        
                        if(!empty($raffle_images)){
                            foreach($raffle_images as $img){
                            
                                if(!in_array($img, $current)){
                                    if(file_exists(FCPATH.'public/images/'.$img)){
                                        unlink(FCPATH.'public/images/'.$img);
                                    }
                                } else{
                                    $images[] = $img;
                                }
    
                            }

                        }
                       
                    }
    
                    /* Verifica se enviou as imagens */
                    if ($this->request->getFileMultiple('files')) {
                 
                        // initialize the FileUploader
                        $FileUploader = new \FileUploader('files', array(
                            'uploadDir' => 'public/images/',
                            'title' => ['auto', 15],
                            'fileMaxSize' => 4,
                            'extensions' => ['jpg','jpeg','png','webp']
                        ));
                    
                        // call to upload the files
                        $upload = $FileUploader->upload();
                    
                        if($upload['isSuccess']) {
                            // get the uploaded files
                            $files = $upload['files'];

                            if($files){
                                foreach($files as $f){
                                    $images[] = $f['name'];
                                }
                            }
                        }
      
                    }
                    
                    if(isset($images) && !empty($images)){
                        $newData['images'] = json_encode($images);
                    }

                    $raffleModel->update($data['raffle']->id, $newData);
    
                  
                    session()->setFlashdata('status',['message' => 'Rifa atualizada com sucesso.', 'status' => 'success']);
                    return redirect()->to('/dashboard/rifas');

    
                } else {
                    // FAIL
                    session()->setFlashdata('status',['message' => 'Erro ao tentar adicionar rifa, verifique os dados e tente novamente.', 'status' => 'error']);
                    return redirect()->to('/dashboard/rifas/adicionar');
                }
                
            }   

            $images = $data['raffle']->images;
            
            if($images){
                $data['images'] = json_decode($images);
            }


    
            echo view('backend/partials/header', $data);
            echo view('backend/edit_raffle',$data);
            echo view('backend/partials/footer');




        } else {
            return redirect()->to('/dashboard/rifas');
        }

        
        
    }

    /* EXCLUIR A RIFA */

    public function delete_raffle($id){
        
        $orderModel = new OrderModel();
        $raffleModel = new RaffleModel();


        $raffle = $raffleModel->select('id,images')->where(['id' => $id])->first();

        if($raffle){

            /* Apago as imagens da rifa */
            $images = json_decode($raffle->images);

            if(!empty($images)){
                foreach($images as $img) {

                    if(file_exists(FCPATH .'public/images/'.$img)){
                        unlink(FCPATH.'public/images/'.$img);
                    }
                }

            }
          
            /* Apago os pedidos da rifa */
            $orderModel->where(['id_raffle' => $id])->delete();
            /* Por fim, deleto a rifa */
            $raffleModel->delete($id);


            /* Notifica que o pedido foi excluído com sucesso! */
            session()->setFlashdata('status',['message' => 'Rifa excluida com sucesso!', 'status' => 'success']);

        } else {
            /* Notifica que a rifa não existe */
            session()->setFlashdata('status',['message' => 'Erro ao tentar excluir rifa inexistente.', 'status' => 'error']);
        }
       

        return redirect()->to(base_url('/dashboard/rifas'));

    }

    /* ATIVA E DESATIVA A RIFA */

    public function toggle_raffle($id){

        $raffleModel = new RaffleModel();
        $userModel = new UserModel();

        if($id)
        {
            $raffle = $raffleModel->select('id,status')->where(['id' => $id, 'id_user' => session()->get('user')['id']])->first();
         
            if($raffle)
            {
                if($raffle->status == 0)
                {

                   // Antes de ativar, eu preciso ver se o cara configurou os gateways de pagamento 
                   
                   $userSettings = $userModel->select('mp_access_token, paggue_client_secret, paggue_client_key')->where('id', session()->get('user')['id'])->first();
                   

                   if($userSettings->mp_access_token || ($userSettings->paggue_client_secret && $userSettings->paggue_client_key))
                   {
                
                        // ATIVA  RIFA
                        $raffleModel->update($raffle->id, ['status' => 1]); 
                        session()->setFlashdata('status',['message' => 'Rifa ativada com sucesso', 'status' => 'success']);

                   } else {
                        // A PESSOA PRECISA CONFIGURAR AS CREDENCIAIS
                        session()->setFlashdata('status',['message' => 'Antes de ativar a rifa, você precisa configurar algum gateway de pagamento.', 'status' => 'error']);
                   }
                  
                } else {
                    // DESATIVA A RIFA 
                    $raffleModel->update($raffle->id, ['status' => 0]); 
                    session()->setFlashdata('status',['message' => 'Rifa desativada com sucesso', 'status' => 'success']);

                }
                
            }
        } 
        return redirect()->to('/dashboard/rifas');
    }

    /* ATIVA E DESATIVA A PARCIAL DA RIFA */

    public function toggle_parcial($id){

        $raffleModel = new RaffleModel();

        if($id)
        {
            $raffle = $raffleModel->select('id,parcial')->where(['id' => $id])->first();
            
            if($raffle)
            {
                if($raffle->parcial == 0)
                {
                    $raffleModel->update($raffle->id, ['parcial' => 1]); 
                    session()->setFlashdata('status',['message' => 'Parcial ativada com sucesso', 'status' => 'success']);
                    
                } else {
                    // DESATIVA A RIFA 
                    $raffleModel->update($raffle->id, ['parcial' => 0]); 
                    session()->setFlashdata('status',['message' => 'Parcial desativada com sucesso', 'status' => 'success']);
                }
                
            }
        } 
        return redirect()->to('/dashboard/rifas');
    }


    /* Clientes - SOMENTE ADMIN */

    public function customers(){
        
        /* SOMENTE ADMIN */
        if(session()->get('user')['is_admin']){

            $customerModel = new CustomerModel();
            $ordersModel = new OrderModel();
            $raffleModel = new RaffleModel();

            $data = [
                'title' => 'Clientes',
                'active' => 'clientes'
            ];

            $data['search'] = $this->request->getGet('search');

            if ($data['search']) {

                $data['users'] = $customerModel->like('name', $data['search'], 'match')
                ->orLike('email', $data['search'],'match')
                ->orLike('phone', $data['search'],'match')
                ->paginate(10);
            } else {
                $data['customers'] = $customerModel->paginate(10);
            }

            $data['pager'] = $customerModel->pager;

            /*
            MAIS PRA FRENTE POSSO PEGAR OS PEDIDOS DO USUÁRIO 
            foreach($data['users'] as $key => $o){

                $raffle = $raffleModel->select('title')->where(['id' => $o->id_raffle])->first();
                $customer = $customerModel->select('name, phone')->where(['id' => $o->id_user])->first();

                $data['orders'][$key]->user = $customer; 
                $data['orders'][$key]->raffle = $raffle; 
            }
            */

            echo view('backend/partials/header', $data);
            echo view('backend/customers',$data);
            echo view('backend/partials/footer');

        } else {
           
            return redirect()->to(base_url('/dashboard'));
            
        }
        
    
    }

    /* Remove o cliente e todas as suas relações */
    public function delete_customer($id){

        if(session()->get('user')['id_admin']){

            $orderModel = new OrderModel();
            $raffleModel = new RaffleModel();
            $customerModel = new CustomerModel();

            $customer = $customerModel->where(['id' => $id])->first();

            if($customer){
            
                $orders = $orderModel->where(['id_user' => $customer->id])->findAll();

                if($orders){

                    foreach($orders as $order){
                        
                        $raffle = $raffleModel->where(['id' => $order->id_raffle])->first();

                        if($raffle){

                            $raffle_numbers = json_decode($raffle->numbers);
                            $numbers = json_decode($order->numbers);

                            /* Deixa o número ativo novamente */
                            foreach($numbers as $n){

                                $i = array_search($n, array_column($raffle_numbers, 'number'));
                                
                                $raffle_numbers[$i]->status = 0;
                                $raffle_numbers[$i]->order_id = 0;
                                $raffle_numbers[$i]->user = '';
                                $raffle_numbers[$i]->reservet_at = 0;

                            }

                            $raffleModel->update($raffle->id, [ 'numbers' => json_encode($raffle_numbers)]);

                        }

                        /* Remove o pedido */
                        $orderModel->delete($order->id);
                    }

                }

                /* Por fim, remove o usuário */
                $customerModel->delete($id);
                
                /* Notifica que o pedido foi excluído com sucesso! */
                session()->setFlashdata('status',['message' => 'Usuário excluído com sucesso', 'status' => 'success']);
                
            }

            return redirect()->to(base_url('/dasboard/clientes'));

        } else {
            return redirect()->to(base_url('/dashboard'));
        }
        
        

    }

 

    /* FINANCEIRO */
    public function financial()
    {
        $data = [
            'title' => 'Financeiro',
            'active' => 'financeiro'
        ];
        echo view('backend/partials/header', $data);
        echo view('backend/financial',$data);
        echo view('backend/partials/footer');
    }

 

    /* CATEGORIAS */
    public function categories()
    {
        $categoryModel = new CategoryModel();

        $data = [
            'title' => 'Categorias',
            'active' => 'categorias'
        ];

        /* Adiciona categoria */
        if($this->request->getMethod() == 'post'){

            $title = $this->request->getPost('title');
            $description = $this->request->getPost('description');
            $id_category = $this->request->getPost('id_category');
    
            if($title){

                if($id_category){
                     # Edita
                     $newCategory = [
                        'title' => $title,
                        'description' => $description
                    ];
                    $categoryModel->update($id_category,$newCategory);
                    session()->setFlashdata('status', ['message' => 'Categoria atualizada com sucesso!','status' => 'success']);

                } else{
                     # Adiciona
                    $newCategory = [
                        'title' => $title,
                        'description' => $description,
                        'status' => 1
                    ];
                    $categoryModel->save($newCategory);
                    session()->setFlashdata('status', ['message' => 'Categoria adicionada com sucesso!','status' => 'success']);

                }
                
                return redirect()->to(base_url('admin/categorias'));
            }
        }

        $data['search'] = $this->request->getGet('search');
        $data['type'] = $this->request->getGet('type');

        if($data['search'] || $data['type']){
            if ($data['search']){
                $categoryModel->like('title', $data['search']);
            }
            if ($data['type']){
                $categoryModel->where(['status' => $data['type'] == 'active'?1:0]);
            }
        }

        $data['categories'] =  $categoryModel->orderBy('id','DESC')->paginate(10);
        $data['pager'] = $categoryModel->pager;

        echo view('backend/partials/header', $data);
        echo view('backend/categories',$data);
        echo view('backend/partials/footer');
    }

    /* Deleta categoria */

    public function delete_category($id){

        if($id){

            $categoryModel = new CategoryModel();
            $categoryModel->delete($id);
            
            session()->setFlashdata('status', ['message' => 'Categoria excluída com sucesso!','status' => 'success']);
        }

        return redirect()->to(base_url('admin/categorias'));
    }

    /* CONFIGURAÇÕES */
    public function settings()
    {
        $data = [
            'title' => 'Configurações',
            'active' => 'configuracoes'
        ];

        $userModel = new UserModel();

        if($this->request->getMethod() == 'post'){

            //$title = $this->request->getPost('title');
            $expires_time = $this->request->getPost('expires_time');

            $paggue_client_key = $this->request->getPost('paggue_client_key');
            $paggue_client_secret = $this->request->getPost('paggue_client_secret');

            $mp_access_token = $this->request->getPost('mp_access_token');

            $newUser = [
                'paggue_client_key' => $paggue_client_key,
                'paggue_client_secret' => $paggue_client_secret,
                'mp_access_token' => $mp_access_token,
                'expires_time' => $expires_time
            ];

            $userModel->update(
                session()->get('user')['id'],
                $newUser
        
            );
           
            
            session()->setFlashdata('status',['message' => 'Dados atualizados com sucesso','status' => 'success']);
            return redirect()->to('/dashboard/configuracoes');     
        }

        $data['user'] = $userModel->where('id', session()->get('user')['id'])->first();
    
        echo view('backend/partials/header', $data);
        echo view('backend/settings',$data);
        echo view('backend/partials/footer');

    }

     /* Configura a porcentagem da rifa */
     private function setRafflePercent($id){
        
        $orderModel = new OrderModel();
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


    /* Ganhadores */
    public function winners()
    {
        $winnerModel = new WinnerModel();
        $raffleModel = new RaffleModel();
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();

        /* Aqui já faz os disparos das notificações */

        $data = [
            'title' => 'Ganhadores',
            'active' => 'ganhadores',
            'number' => ''
        ];

        /* Adiciona um ganhador */

        if($this->request->getMethod()  == 'post')
        {
            $name = $this->request->getPost('name');
            $number = $this->request->getPost('number');
            $id_raffle = $this->request->getPost('id_raffle');
            $id_winner = $this->request->getPost('id_winner');

            if($name && $number && $id_raffle){

                if($id_winner){

                    $newWinner = [
                        'id' => $id_winner,
                        'name' => $name,
                        'number' => $number,
                        'id_raffle' => $id_raffle
                    ];

                } else {

                    $newWinner = [
                        'name' => $name,
                        'number' => $number,
                        'id_raffle' => $id_raffle
                    ];
                }
                
                print_r($newWinner);exit;
                $winnerModel->save($newWinner);

                session()->setFlashdata('status', ['message' => 'Atualizado com sucesso!','status' => 'success']);
            }

            return redirect()->to('admin/ganhadores');

        }

        /* Verifica se tem um ganhador */

        $order_id = $this->request->getGet('order');
        $search = $this->request->getGet('search');
        

        if($order_id){

            $order = $orderModel->where('id',$order_id)->first();

            if($order){

                $data['user'] = $customerModel->select('name')->where('id',$order->id_user)->first();
                $data['raffle'] = $raffleModel->select('id,title')->where('id',$order->id_raffle)->first();

            }

            if($search && is_numeric($search)){
                $data['number'] = $search;
            }

        }

        $data['raffles'] = $raffleModel->select('id,title')->findAll();
        $data['winners'] = $winnerModel->orderBy('id','DESC')->findAll();

        $data['winners'] = $winnerModel->orderBy('id','DESC')->findAll(5);

        if($data['winners']){
            foreach($data['winners'] as $key_winner => $winner){
                $data['winners'][$key_winner]->raffle =  $raffleModel->select('slug, title, images')->where('id',$winner->id_raffle)->first();
            }
        }


        echo view('backend/partials/header', $data);
        echo view('backend/winners',$data);
        echo view('backend/partials/footer');

    }

     /* Deleta o ganhador */

     public function delete_winner($id){

        if($id){

            $winnerModel = new WinnerModel();
            $winnerModel->delete($id);
            
            session()->setFlashdata('status', ['message' => 'Ganhador excluído com sucesso!','status' => 'success']);
        }

        return redirect()->to(base_url('admin/ganhadores'));
    }


    public function findPaymentStatus(){

        $raffleModel = new RaffleModel();
    
        $data = [
            'error' => '',
            'status' => ''
        ];

        $raffle_id = filter_var($this->request->getJsonVar('raffle_id'), FILTER_SANITIZE_STRIPPED);
       
        if($raffle_id){
            $raffle = $raffleModel->select('payment_status')->where(['id' => $raffle_id])->first();

            if($raffle){

                if($raffle->payment_status == 0){
                    $data['status'] = 'pending';
                } else {
                    $data['status'] = 'approved';
                }

            } else {
                $data['error'] = 'Rifa não encontrada';
            }
        }

        echo json_encode($data);exit;


    }

    public function getNumber($id)
    {
        $orderModel = new OrderModel();
        $raffleModel = new RaffleModel();
        $customerModel = new CustomerModel();

        $order = $orderModel->where(['id' => $id])->first();
        $raffle = $raffleModel->where(['id' => $order->id_raffle])->first();

        $response = [
            'orderNumbers' => $order->numbers,
            'raffleNumbers' => $raffle->numbers
        ];

        return json_encode($response);
        //echo json_encode($order->numbers);
    }
}
