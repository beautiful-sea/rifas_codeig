<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\RaffleModel;
use App\Models\UserModel;

class Api extends BaseController
{
    /* Recebe as notificações do mercado pago */
    public function update_order()
    {

        $data = json_decode(file_get_contents('php://input')); //outputs nothing

        $userMp = $this->request->getGet('user_mp');

        if ($userMp) {

            $userModel = new UserModel();
            $userSettings = $userModel->select('mp_access_token')->where('id', $userMp)->first();

            if ($userSettings) {
                // INICIO O MERCADO PAGO COM O TOKEN DO USUÁRIO
                \MercadoPago\SDK::setAccessToken($userSettings->mp_access_token);
            }
        }

        if (isset($data) && isset($data->type)) {

            switch ($data->type) {
                case "payment":
                    $payment = \MercadoPago\Payment::find_by_id($data->data->id);
                    break;
            }

            if ($payment) {

                // Aprova o pagamento do cliente
                if ($payment->status == 'approved') {
                    $order_hash = $payment->external_reference;
                    $orderModel = new OrderModel();
                    $orderModel->query("UPDATE orders SET status = '1' WHERE hash = '" . $order_hash . "'");
                }
            }
        }


        echo json_encode(['status' => True, 'msg' => 'Pedidos atualizados com sucesso!']);
        exit;
    }

    public function confirmPaymentCustomer($data)
    {
        $fp = fopen("/requestMP.txt", "wb");
        fwrite($fp, $data);
        fclose($fp);

        $access = 'APP_USR-5261151288450206-070203-6d3620d89d07ea1a4b47999ea3b80252-781237219';

        \MercadoPago\SDK::setAccessToken($access);

        if (isset($data) && isset($data->type)) {

            switch ($data->type) {
                case "payment":
                    $payment = \MercadoPago\Payment::find_by_id($data->data->id);
                    break;
            }

            if ($payment) {

                // Aprova o pagamento do cliente
                if ($payment->status == 'approved') {
                    $order_hash = $payment->external_reference;
                    $raffleModel = new RaffleModel();
                    $raffleModel->query("UPDATE raffles SET payment_status = '2' WHERE id = '" . $order_hash . "'");
                }
            }
        }
    }

    /* SE EU MANIPULAR A RIFA DIRETAMENTE DAQUI, VAI ELEVAR O PROCESSAMENTO DAS RIFAS, ENTÃO, VOU PEGAR OS PEDIDOS LÁ DA RIFA PRA CONTABILIZAR NO TOTAL */
    public function webhook_paggue()
    {

        $res = [];

        $data = json_decode(file_get_contents('php://input'));

        if ($data) {
            if ($data->status == '1') {

                $hash = $data->external_id;
                $orderModel = new OrderModel();
                $orderModel->query("UPDATE orders SET status = '1' WHERE hash = '" . $hash . "'");
            }

            $res = ['status' => "Dados atualizados com sucesso"];
        } else {
            $res = ['status' => "Acesso negado"];
        }

        return $this->response->setJSON($res);
        exit;
    }



    public function webhook_raffle_paggue()
    {
        $res = [];

        $data = json_decode(file_get_contents('php://input'));

        if ($data) {

            if ($data->status == '1') {



                $hash = $data->external_id;
                $raffleModel = new RaffleModel();
                $raffleModel->query("UPDATE raffles SET payment_status = '1' WHERE hash = '" . $hash . "'");
            }

            $res = ['status' => "Dados atualizados com sucesso"];
        } else {
            $res = ['status' => "Acesso negado"];
        }

        return $this->response->setJSON($res);
    }

    /*
    public function update(){
        $raffleModel = new RaffleModel();
        $orderModel = new OrderModel();
        $userModel = new UserModel();


        $json = file_get_contents(APPPATH.'controllers/data.json');
        $data = json_decode($json);


        // Aqui eu já tenho o usuário, agora, crio o pedido e atualizo na rifa 

        $raffle = $raffleModel->where(['id' => 40])->first();
        $raffle_numbers = json_decode($raffle->numbers);


        $pedidos = [];

        foreach($data as $order){
            if($order->status != 'Disponivel'){
                
                //Primeira etapa, se não existir no banco, cria a conta do comprador
                $nome_comprador = $order->nome_comprador;
                $telefone_comprador = $order->telefone_comprador;
                $data_reserva = $order->data_reserva;
                $numero = $order->numero;
                $status = $order->status == 'Pago'? 2:1;

                $user = $userModel->where(['phone' => $telefone_comprador])->first();
                
                if(!$user){
                
                    // Cadastra e cria um id
                    $newUser = [
                        "name" => ucwords($nome_comprador),
                        "phone" => $telefone_comprador,
                    ];

                    $userModel->save( $newUser );
                    $userId = $userModel->getInsertID();

                    $user = $userModel->where('id',$userId)->first();

                }

                $i = array_search($numero, array_column($raffle_numbers,'number'));

                // Atualizo o número na rifa 
                $raffle_numbers[$i]->status = $status; // aguardando pagamento
                $raffle_numbers[$i]->order_id = 0; // id do pedido
                $raffle_numbers[$i]->user = $user->name; // nome do usuário
                $raffle_numbers[$i]->reserved_at = $data_reserva;

                // Adiciona os números no array de pedidos, pra criar um por um depois 

                if(!isset($pedidos[$user->id])){
                    $pedidos[$user->id] = [
                        'name' => $user->name,
                        'phone' => $user->phone,
                        'id' => $user->id,
                        'status',
                        'data_reserva' => $data_reserva,
                        'pagos' => [],
                        'reservados' => []
                    ];
                }

                if($status == 2){
                    // pago
                    $pedidos[$user->id]['pagos'][] = $numero;
                } else {
                    // reservado
                    $pedidos[$user->id]['reservados'][] = $numero;
                }
            }
        }
        
        foreach($pedidos as $p){


            if(!empty($p['pagos'])){

                $newOrder = [
                    "id_user" => $p['id'],
                    "id_raffle" => $raffle->id,
                    "status" => 1, // 1 pra pagamento aprovado
                    "numbers" => json_encode($p['pagos']),
                    "expires_in" => strtotime('-10minutes'),
                    "price" => $raffle->price * count($p['pagos']),
                    "original_price" => 0,
                    'created_at' => $p['data_reserva']
                ];
                
                $orderModel->save($newOrder);
            }

            if(!empty($p['reservados'])){

                $newOrder = [
                    "id_user" => $p['id'],
                    "id_raffle" => $raffle->id,
                    "status" => 0, // 1 pra pagamento aprovado
                    "numbers" => json_encode($p['reservados']),
                    "expires_in" => strtotime('+100 hours'), // para nunca expirar
                    "price" => $raffle->price * count($p['reservados']),
                    "original_price" => 0,
                    'created_at' => $p['data_reserva']
                ];
                
                $orderModel->save($newOrder);

            }

        }

        $raffleModel->update($raffle->id, ['numbers'=> json_encode($raffle_numbers)]);
    }

    */
}
