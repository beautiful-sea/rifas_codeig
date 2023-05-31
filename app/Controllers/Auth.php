<?php

namespace App\Controllers;

use App\Models\RaffleModel;
use App\Models\OrderModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login(){

        $data = [
            'title' => 'Login'
        ];

        $email = filter_var($this->request->getPost('email'), FILTER_SANITIZE_STRIPPED);
        $password = filter_var($this->request->getPost('password'), FILTER_SANITIZE_STRIPPED);


        if($email && $password){

            $userModel = new UserModel();

            $user = $userModel->where(['email' => $email])->first();

            if($user){

                if(password_verify($password,$user->password)){

                    $user_session = [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'is_admin' => $user->is_admin,
                        'logged_in' => TRUE
                    ];

                    session()->set('user', $user_session);
                    return redirect()->to('/dashboard');

                } else {
                    $data['errors'] = ['Dados inválidos'];
                }

            } else {
        
                $data['errors'] = ['Dados inválidos'];
            }

            //return redirect()->to('/auth/login');



        }
    
        echo view('auth/partials/header', $data);
        echo view('auth/login', $data);
        echo view('auth/partials/footer', $data);
     
        
    }

    public function register(){

        $data = [
            'title' => 'Cadastro'
        ];


    
        if($this->request->getMethod() == 'post')
        {
   
            $userModel = new UserModel();
                 
            //set rules validation form
            $rules = [
                'name'          => 'required|min_length[3]|max_length[100]',
                'email'         => 'required|min_length[6]|max_length[100]|valid_email|is_unique[users.email]',
                'password'      => 'required|min_length[6]|max_length[200]'
            ];
            
            if($this->validate($rules)){

                $userModel = new UserModel();
              
                $newUser = [
                    'name'     => ucwords($this->request->getVar('name')),
                    'email'    => strtolower($this->request->getVar('email')),
                    'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                ];

             
                /* Aqui, preciso requisitar se o usuário já é clinte BTC, se sim, ganha assinatura anual */
                $userModel->save($newUser);

                $userId = $userModel->insertID();
                
                $user = $userModel->select('id, name, email, is_admin')->where('id',$userId)->first();
                /* JOGA NA SESSION E MANDA PRO DASHBOARD */

                $user_session = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_admin' => $user->is_admin,
                    'logged_in' => TRUE
                ];
              
                session()->set('user',$user_session);
                /* Salvo as informações de login */


                return redirect()->to('/dashboard');

            }else{
                
                $data['errors'] = $this->validator->getErrors();
            }

        }
    
        echo view('auth/partials/header', $data);
        echo view('auth/register', $data);
        echo view('auth/partials/footer', $data);
     
        
    }


    public function logout()
    {
        
        session()->destroy();
        return redirect()->to('/login');
    }



}