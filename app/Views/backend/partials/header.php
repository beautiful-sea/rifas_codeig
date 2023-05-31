<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo base_url('public/css/libraries.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('public/css/backend.css') ?>" />


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js" integrity="sha512-efUTj3HdSPwWJ9gjfGR71X9cvsrthIA78/Fvd/IN+fttQVy7XWkOAXb295j8B3cmm/kFKVxjiNYzKw9IQJHIuQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- IMAGE LOADER VIEWER JAVASCRIPT -->
    <?php if ($active == 'add_rifa' || $active == 'edit_rifa') : ?>


        <link href="<?php echo base_url('public/dist/font/font-fileuploader.css') ?>" media="all" type="text/css" rel="stylesheet" />
        <link href="<?php echo base_url('public/dist/jquery.fileuploader.min.css') ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo base_url('public/dist/jquery.fileuploader-theme-thumbnails.css') ?>" type="text/css" rel="stylesheet" />

    <?php endif ?>

    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('public/img') ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url('public/img') ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('public/img') ?>/favicon-16x16.png">
    <link rel="manifest" href="<?php echo base_url('public/img') ?>/site.webmanifest">

    <title><?php echo $title ?> Hd Produtora - Administrativo </title>

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

        .d-none {
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
                <!--<img src="<?php echo base_url('public/img/logo-white.png') ?>" alt="Admin img">-->
                <img src="<?= $url_da_imagem ?>" alt="Logotipo">
            </div>
            <div>
                <?php if (session()->has('errors')) : ?>
                    <span class="" style="color: red;font-size: 30;"><?php echo session()->get('errors')['logofile']; ?></span>
                <?php endif; ?>
                <?php if (session()->has('uploaded')) : ?>
                    <span class="text text-success"><?php echo session()->get('uploaded'); ?></span>
                <?php endif; ?>
                <?php if (session()->get('user')['is_admin']) : ?>
                    <form action="<?php echo url_to('upload-logomarca');  ?>" method="post" enctype="multipart/form-data">
                        <input type="file" name="logofile" class="form-control-file" style="margin-top: 10px;">
                        <button type="submit" class="success" style="margin-top: 5px;">Alterar Logomarca</button>
                    </form>
                <?php endif; ?>
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
                    <a href="<?php echo base_url('dashboard') ?>" class="<?php echo $active === 'dashboard' ? 'active' : '' ?>">
                        <i class="fa-solid fa-gauge"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a class="<?php echo $active === 'pedidos' ? 'active' : '' ?>" href="<?php echo base_url('dashboard/pedidos') ?>">
                        <i class="fa-solid fa-receipt"></i>
                        Pedidos
                    </a>
                </li>

                <li>
                    <a class="<?php echo $active === 'ranqueamento' ? 'active' : '' ?>" href="<?php echo base_url('dashboard/ranqueamento') ?>">
                        <i class="fa-solid fa-ranking-star"></i>
                        Ranqueamento
                    </a>
                </li>

                <li class="dropdown <?php echo ($active === 'rifas' || $active === 'categorias' || $active === 'add_rifa' || $active === 'edit_rifa') ? 'active' : '' ?>">

                    <a href="#">
                        <i class="fa-solid fa-list-check"></i>
                        Rifas
                    </a>

                    <ul>
                        <li>
                            <a class="<?php echo ($active === 'add_rifa') ? 'active' : '' ?>" href="<?php echo base_url('dashboard/rifas/adicionar') ?>">
                                <i class="fa-solid fa-circle-plus"></i>
                                Adicionar Rifa
                            </a>
                        </li>
                        <li>
                            <a class="<?php echo ($active === 'rifas' || $active == 'edit_rifa') ? 'active' : '' ?>" href="<?php echo base_url('dashboard/rifas') ?>">

                                <i class="fa-solid fa-list"></i>
                                Todas as Rifas
                            </a>
                        </li>

                        <?php if (session()->get('user')['is_admin']) : ?>
                            <!-- SOMENTE ADMIN -->
                            <li>
                                <a class="<?php echo ($active === 'categorias') ? 'active' : '' ?>" href="<?php echo base_url('dashboard/categorias') ?>">
                                    <i class="fa-solid fa-layer-group"></i>
                                    Categorias
                                </a>
                            </li>
                        <?php endif ?>
                    </ul>
                </li>
                <?php if (session()->get('user')['is_admin']) : ?>
                    <!-- SOMENTE ADMIN -->
                    <li>
                        <a class="<?php echo $active === 'clientes' ? 'active' : '' ?>" href="<?php echo base_url('/dashboard/clientes') ?>">
                            <i class="fa-solid fa-users"></i>
                            Clientes
                        </a>
                    </li>

                    <li>
                        <a class="<?php echo $active === 'planos' ? 'active' : '' ?>" href="<?php echo base_url('/dashboard/planos') ?>">
                            <i class="fa-solid fa-receipt"></i>
                            Planos
                        </a>
                    </li>

                    <!-- <li>
                    <a  class="<?php echo $active === 'ganhadores' ? 'active' : '' ?>" href="<?php echo base_url('/dashboard/ganhadores') ?>">
                        <i class="fa-solid fa-clover"></i>
                    
                        Ganhadores
                    </a>
                </li> -->

                <?php endif ?>

                <li>
                    <a class="<?php echo $active === 'configuracoes' ? 'active' : '' ?>" href="<?php echo base_url('/dashboard/configuracoes') ?>">
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
        setInterval(function () {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: "/clear-pendentes",
                success: function(data) {
                },
            });
        }, 10000);

        document.querySelectorAll('.dropdown').forEach(e => e.setAttribute('onclick', 'toggleDropdown(this)'));

        function toggleDropdown(obj) {
            document.querySelectorAll('#menu-body a').forEach(e => e.classList.remove('active'));
            obj.classList.toggle('active');
            obj.querySelector('a').classList.toggle('active');
        }

        function toggleMobileMenu() {
            document.querySelector('#menu').classList.toggle('active');
        }
    </script>
    <script>
        // enable fileuploader plugin
        $('input.imagen-logomarca').fileuploader({
            extensions: ["jpg", "jpeg", "png", "webp"],
            changeInput: " ",
            theme: "thumbnails",
            enableApi: true,
            addMore: true,
            thumbnails: {
                box: '<div class="fileuploader-items"><ul class="fileuploader-items-list"><li class="fileuploader-thumbnails-input"><div class="fileuploader-thumbnails-input-inner"><span>+</span></div></li></ul></div>',
                item: '<li class="fileuploader-item"><div class="fileuploader-item-inner"><div class="thumbnail-holder">${image}</div><div class="actions-holder"><a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="remove"></i></a><span class="fileuploader-action-popup"></span></div><div class="progress-holder">${progressBar}</div></div></li>',
                item2: '<li class="fileuploader-item"><div class="fileuploader-item-inner"><div class="thumbnail-holder">${image}</div><div class="actions-holder"><a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="remove"></i></a><span class="fileuploader-action-popup"></span></div></div></li>',
                startImageRenderer: !0,
                canvasImage: !1,
                _selectors: {
                    list: ".fileuploader-items-list",
                    item: ".fileuploader-item",
                    start: ".fileuploader-action-start",
                    retry: ".fileuploader-action-retry",
                    remove: ".fileuploader-action-remove"
                },
                onItemShow: function(e, i) {
                    i.find(".fileuploader-thumbnails-input").insertAfter(e.html), "image" == e.format && e.html.find(".fileuploader-item-icon").hide()
                }
            },
            afterRender: function(e, i, a, l) {
                var n = e.find(".fileuploader-thumbnails-input"),
                    r = $.fileuploader.getInstance(l.get(0));
                n.on("click", function() {
                    r.open()
                })
            },
            editor: {
                // editor cropper
                cropper: {
                    // cropper ratio
                    // example: null
                    // example: '1:1'
                    // example: '16:9'
                    // you can also write your own
                    ratio: '4:3',

                    // cropper minWidth in pixels
                    // size is adjusted with the image natural width
                    minWidth: null,

                    // cropper minHeight in pixels
                    // size is adjusted with the image natural height
                    minHeight: null,

                    // show cropper grid
                    showGrid: true
                },

                // editor on save quality (0 - 100)
                // only for client-side resizing
                quality: 70,

                // editor on save maxWidth in pixels
                // only for client-side resizing
                maxWidth: null,

                // editor on save maxHeight in pixels
                // only for client-size resizing
                maxHeight: 630,

            },
            captions: {
                button: function(options) {
                    return 'Browse ' + (options.limit == 1 ? 'file' : 'imagen-logomarca');
                },
                feedback: function(options) {
                    return 'Choose ' + (options.limit == 1 ? 'file' : 'imagen-logomarca') + ' to upload';
                },
                feedback2: function(options) {
                    return options.length + ' ' + (options.length > 1 ? 'files were' : 'file was') + ' chosen';
                },
                confirm: 'Confirmar',
                cancel: 'Cancelar',
                name: 'Nome',
                type: 'Tipo',
                size: 'Tamanho',
                dimensions: 'Dimensões',
                duration: 'Duração',
                crop: 'Crop',
                rotate: 'Girar',
                sort: 'Ordenar',
                download: 'Download',
                remove: 'Deletar',
                drop: 'Solte os arquivos aqui para fazer upload',
                paste: '<div class="fileuploader-pending-loader"></div> Colando imagem, clique para cancelar.',
                removeConfirmation: 'Tem certeza que deseja excluir?',
                errors: {
                    filesLimit: function(options) {
                        return 'Apenas ${limit} ' + (options.limit == 1 ? 'file' : 'imagen-logomarca') + ' pode ser carregado.'
                    },
                    filesType: 'Somente ${extensions} são aceitos.',
                    fileSize: '${name} é muito grande. Escolha um arquivo até ${fileMaxSize}MB.',
                    filesSizeAll: 'Os arquivos escolhidos são muito grandes! Selecione arquivos até ${maxSize} MB.',
                    fileName: 'Um arquivo com o mesmo nome ${name} já foi selecionado.',
                    remoteFile: 'Arquivos remotos não são permitidos.',
                    folderUpload: 'Pastas não são permitidas.'
                }
            },
        });

        

    </script>