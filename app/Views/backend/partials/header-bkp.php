<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" 
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo base_url('public/css/libraries.css')?>" />
    <link rel="stylesheet" href="<?php echo base_url('public/css/backend.css')?>" />

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js" integrity="sha512-efUTj3HdSPwWJ9gjfGR71X9cvsrthIA78/Fvd/IN+fttQVy7XWkOAXb295j8B3cmm/kFKVxjiNYzKw9IQJHIuQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <!-- IMAGE LOADER VIEWER JAVASCRIPT -->
    <?php if($active == 'add_rifa' || $active == 'edit_rifa'):?>
        
 
    <link href="<?php echo base_url('public/dist/font/font-fileuploader.css')?>" media="all" type="text/css" rel="stylesheet" />
    <link href="<?php echo base_url('public/dist/jquery.fileuploader.min.css')?>" type="text/css" rel="stylesheet" />
    <link href="<?php echo base_url('public/dist/jquery.fileuploader-theme-thumbnails.css')?>" type="text/css" rel="stylesheet" />

    <?php endif ?>

    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('public/img')?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url('public/img')?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('public/img')?>/favicon-16x16.png">
    <link rel="manifest" href="<?php echo base_url('public/img')?>/site.webmanifest">

    <title><?php echo $title ?> Nando MKT - Administrativo </title>

    <style>
        #loadingSystem {
            background: rgba(206, 206, 206, 0.5) url("../../../../public/img/loading.gif") no-repeat scroll center center;
            background-size: 100px 100px;
            height: 100%;
            left: 0;
            overflow: visible;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9999999;
        }

        .d-none{
            display: none;
        }

        .table-responsive tbody tr td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div id="loadingSystem" class="d-none"></div>

<header>
    <div class="mobile-menu" onclick="toggleMobileMenu()">
        <i class="fa-solid fa-bars"></i>
    </div>
    
    </nav>
</header>

<div id="menu" class="block-copy">
    <div id="menu-header">
        <div class="logo">
            <img src="<?php echo base_url('public/img/logo-white.png')?>" alt="Admin img">
        </div>
        <br>
        <br>
        <div class="profile-info">
                <?php
                    $name = strstr(session()->get('user')['name'], ' ', true);

                ?>
                <p><?php echo $name ?></p>
                
                </div>
        <span onclick="toggleMobileMenu()">X</span>
       
    </div>
    <div id="menu-body">
        <ul>
            <li>
                <a href="<?php echo base_url('dashboard')?>" class="<?php echo $active === 'dashboard'?'active':''?>">
                    <i class="fa-solid fa-gauge"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a  class="<?php echo $active === 'pedidos'?'active':''?>" href="<?php echo base_url('dashboard/pedidos')?>">
                    <i class="fa-solid fa-receipt"></i>
                    Pedidos
                </a>
            </li>

            <li>
                <a class="<?php echo $active === 'ranqueamento'?'active':''?>" href="<?php echo base_url('dashboard/ranqueamento')?>">
                    <i class="fa-solid fa-ranking-star"></i>
                    Ranqueamento
                </a>
            </li>

            <li  class="dropdown <?php echo ($active === 'rifas' || $active === 'categorias' || $active === 'add_rifa' || $active === 'edit_rifa')?'active':''?>">

                <a href="#">
                    <i class="fa-solid fa-list-check"></i>
                    Rifas
                </a>

                <ul>
                    <li>
                        <a class="<?php echo ($active === 'add_rifa')?'active':''?>" href="<?php echo base_url('dashboard/rifas/adicionar')?>" >
                            <i class="fa-solid fa-circle-plus"></i>
                            Adicionar Rifa
                        </a>
                    </li>
                    <li>
                        <a class="<?php echo ($active === 'rifas' || $active == 'edit_rifa')?'active':''?>" href="<?php echo base_url('dashboard/rifas')?>">

                            <i class="fa-solid fa-list"></i>
                            Todas as Rifas
                        </a>
                    </li>

                    <?php if( session()->get('user')['is_admin'] ):?>
                        <!-- SOMENTE ADMIN -->
                        <li>
                            <a class="<?php echo ($active === 'categorias')?'active':''?>" href="<?php echo base_url('dashboard/categorias')?>">
                                <i class="fa-solid fa-layer-group"></i>
                                Categorias
                            </a>
                        </li>
                    <?php endif ?>
                </ul>
            </li>
            <?php if(session()->get('user')['is_admin']):?>
                <!-- SOMENTE ADMIN -->
                <li>
                    <a class="<?php echo $active === 'clientes'?'active':''?>" href="<?php echo base_url('/dashboard/clientes')?>">
                        <i class="fa-solid fa-users"></i>
                        Clientes
                    </a>
                </li>
            
                <li>
                    <a  class="<?php echo $active === 'ganhadores'?'active':''?>" href="<?php echo base_url('/dashboard/ganhadores')?>">
                        <i class="fa-solid fa-clover"></i>
                    
                        Ganhadores
                    </a>
                </li>

            <?php endif ?>

            <li>
                <a class="<?php echo $active === 'configuracoes'?'active':''?>" href="<?php echo base_url('/dashboard/configuracoes')?>">
                    <i class="fa-solid fa-gear"></i>
                    Configurações
                </a>
            </li>
            <li>
                <a href="https://wa.me/5561999999999">
                    <i class="fa-solid fa-circle-question"></i>
                    Suporte
                </a>
            </li>
        </ul>
    </div>
  
</div>



<script>
    document.querySelectorAll('.dropdown').forEach(e=>e.setAttribute('onclick', 'toggleDropdown(this)'));

    function toggleDropdown(obj)
    {
        document.querySelectorAll('#menu-body a').forEach(e=>e.classList.remove('active'));
        obj.classList.toggle('active');
        obj.querySelector('a').classList.toggle('active');
    }

    function toggleMobileMenu(){
        document.querySelector('#menu').classList.toggle('active');
    }

</script>

    
