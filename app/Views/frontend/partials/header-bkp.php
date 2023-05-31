<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo base_url('public/css/swiper-bundle.min.css')?>" />
    <link rel="stylesheet" href="<?php echo base_url('public/css/swiper.css')?>" />
    <link rel="stylesheet" href="<?php echo base_url('public/css/libraries.css')?>" />
    <link rel="stylesheet" href="<?php echo base_url('public/css/frontend.css')?>" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js" integrity="sha512-efUTj3HdSPwWJ9gjfGR71X9cvsrthIA78/Fvd/IN+fttQVy7XWkOAXb295j8B3cmm/kFKVxjiNYzKw9IQJHIuQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <?php if(isset($metas)):?>
        <?php echo $metas ?>
    <?php endif ?>

    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url('public/img')?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('public/img')?>/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="512x512" href="<?php echo base_url('public/img')?>/favicon-512x512.png">
    

    <link rel="apple-touch-icon" href="touch-icon-iphone.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url('public/img')?>/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('public/img')?>/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="167x167" href="<?php echo base_url('public/img')?>/apple-touch-icon.png">


    <?php if(isset($pixels) && !empty($pixels)):?>

        <?php
            $pixels = explode(',',$pixels);  
        ?>

        <!-- Meta Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');

        <?php foreach($pixels as $pixel):?>
            fbq('init', '<?php echo $pixel ?>' );
        <?php endforeach ?>
        
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=3278217582446945&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Meta Pixel Code -->

    <?php endif ?>

    <link rel="manifest" href="manifest.json" />

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Mundo Cell">
    <meta name="apple-mobile-web-app-title" content="Mundo Cell">
    <meta name="theme-color" content="#000000">
    <meta name="msapplication-navbutton-color" content="#000000">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="/">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-91C341EXFR"></script>
    <script>

    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-91C341EXFR');
    </script>


    <!-- SCRIPTS HEAD -->
    <?php if( isset($scripts) && !empty($scripts) ):?>

        <?php echo $scripts->my_orders_scripts ?>

        <?php if(isset($status) && $status == 'approved'):?>
            <?php echo $scripts->thanks_scripts ?>
        <?php endif ?>

       

    <?php endif ?>

    <title><?php echo $title ?></title>

    <script type="module">
    import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwaupdate';
    const el = document.createElement('pwa-update');
    document.body.appendChild(el);
    </script>

</head>
<body data-url="<?php echo base_url()?>">

<header>
    <div class="container" id="menu">
        <div class="logo">
            <a href="<?php echo base_url('/')?>">
                <img src="<?php echo base_url('public/img/logo-white.png')?>" alt="">
            </a>
        </div>
        <!-- FIND MY ORDERS -->

        
        <nav>
            <ul>
                <li><a href="<?php echo base_url('/')?>"> Início</a></li>
                <li><a href="?c=todas">Sorteios</a></li>
                
                
                <li><a href="#ganhadores">Ganhadores</a></li>
                <li><a href="#" onclick="toggleMyRequestModal()">Meus pedidos</a></li>
                <li><a href="/login">Acessar meu site</a></li>
                
            </ul>
        </nav>
        <nav id="menu_mobile" onclick="toggleMyRequestModal()" style="margin-left: 100px;">
            <i class="fa-solid fa-cart-shopping"></i>
        </nav>
        
        <nav id="menu_mobile" onclick="toggleMobileMenu()">
            <i class="fa-sharp fa-solid fa-bars-staggered"></i>
        </nav>
    </div>

    <div class="menu-mobile-content">
        <div class="menu-mobile-header">
            <span onclick="toggleMobileMenu()"><i class="fa-solid fa-xmark"></i></span>
        </div>
        <div class="menu-mobile-body">
            <nav>
                <ul>
                    <li><a href="<?php echo base_url('/')?>">Início</a></li>
                    <li class="categories_list">
                        <a href="?c=todas" onclick="document.querySelector('.menu-mobile-content  .categories_list .categories').classList.toggle('active')">Sorteios</a>
                        
                            <ul>
                            

                            
                    </li>
                    <li><a href="#ganhadores">Ganhadores</a></li>
                    <li><a href="#" onclick="toggleMyRequestModal()">Meus pedidos</a></li>
                    <li><a href="/login">Meu site</a></li>
                    
                </ul>
            </nav>
        </div>

    </div>
</header>



<script>
    function toggleMobileMenu(){
        document.querySelector('.menu-mobile-content').classList.toggle('active');
    }
</script>