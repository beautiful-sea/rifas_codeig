<?php

namespace App\Controllers;

use App\Models\RaffleModel;
use App\Models\OrderModel;
use App\Models\UserModel;

class Cron extends BaseController
{   


    /* Serve pra exluir os pedidos que já expiraram */
    public function update_orders()
    {

        $orderModel = new OrderModel();
        $raffleModel = new RaffleModel();

        $orders = $orderModel->where(['status' => 0])->where('expires_in < UNIX_TIMESTAMP()')->findAll();
        
        if($orders){
            foreach($orders as $order){

                $raffle = $raffleModel->where(['id' => $order->id_raffle])->first();

                /* Verifica se ainda existe a rifa */
                if($raffle){

                    $order_numbers = json_decode($order->numbers);
                    $raffle_numbers = json_decode($raffle->numbers);

                    /* Pega os números do pedido e reseta */
                    foreach($order_numbers as $n){
                        
                        $i = array_search($n, array_column($raffle_numbers,'number'));
                        
                        $raffle_numbers[$i]->status = 0;
                        $raffle_numbers[$i]->user =  '';
                        $raffle_numbers[$i]->reserved_at =  '';
                        $raffle_numbers[$i]->order_id =  '';
                    
                    }

                    /* Por fim, atualizo a rifa e a porcentagem dela */

                    $raffleModel->update($raffle->id, ['numbers' => json_encode($raffle_numbers)]);
                    $this->setRafflePercent($raffle->id);
                }
                /* Por fim, deleta o pedido */
                $orderModel->delete($order->id);
            }
        }

        echo json_encode(['status'=> True, 'msg' => 'Pedidos atualizados com sucesso!']);
        exit;
    }

    /*public function remove_duplicate()
    {
        $orderModel = new OrderModel();
        $raffleModel = new RaffleModel();

        // preciso dos números atuais da rifa 

        $raffle = $raffleModel->where(['id' => 3])->first();
        $orders = $orderModel->where(['status' => 1])->findAll();
        $matriz = json_decode($raffle->numbers);

        // Percorro todos os pedidos 

        foreach($orders as $order){

            $order_numbers = json_decode($order->numbers);

            // agora percorro cada número, busco pra ver se tem outro pedido com esse número, se sim, cancelo o atual número e troco o status dele nos números da rifa 

            foreach($order_numbers as $key => $n){

                $tem_outro = $orderModel->where(['id_raffle' => 3])->like('numbers', $n, 'match')->first();

                if($tem_outro){
                    // Tem outro, cancelo o número atual e pego outro disponível em numeros
                    unset($order_numbers[$key]);

                    foreach($matriz as $key_matriz => $m){
                        if($m->status == 0){

                            // Primeiro número encontrado, passa pra 2 de reservado
                            $matriz[$key_matriz]->status = 2;

                            // Adiciono o novo número no array order_numbers e dou um laço break no foreach atual

                            $order_numbers[] = $matriz[$key_matriz]->number;
                            break;
                        }
                    }

                    // Por fim, atualiza os números da nova ordem 
                    $orderModel->where(['id' => $order->id])->update(
                        [
                            'numbers' => json_encode($order_numbers)
                        ]
                    );

                }
            }
        }

        // por fim, atualizo a matriz 

        $raffleModel->where(['id' => 3])->update([
            'numbers' => json_encode($matriz)
        ]);


    }
    */
    
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
}