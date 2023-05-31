<!-- HEADER PROFILE NAV -->

<div class="profile-nav-menu">
    <ul>
        <li>
            <a href="<?php echo base_url('dashboard/configuracoes')?>">
                <i class="fa-solid fa-gear"></i>
                Configurações
            </a>
        </li>
        <li>
            <a href="<?php echo base_url('auth/logout')?>">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Sair
            </a>
        </li>
    </ul>
</div>


<script>
    document.querySelector('header nav .profile').addEventListener('click', toggleHeaderProfileMenu);
    
    /* TOGGLE NAV HEADER */
    
    function toggleHeaderProfileMenu(){

        document.querySelector('header nav .profile').classList.toggle('active');
        document.querySelector('.profile-nav-menu').classList.toggle('active');

        if( document.querySelector('.profile-nav-menu').classList.contains('active') ){
            document.querySelector('header nav .caret-icon').innerHTML = '<i class="fa-solid fa-caret-up"></i>';
        } else {
            document.querySelector('header nav .caret-icon').innerHTML = '<i class="fa-solid fa-caret-down"></i>';
        }
    }


    <?php if(session()->getFlashdata('status')):?>
        $.notify('<?php echo session()->getFlashdata('status')['message'] ?>','<?php echo session()->getFlashdata('status')['status'] ?>')
    <?php endif ?>


   

</script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/63b731e247425128790be62e/1gm1plno5';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

</body>
</html>