<!-- PASSO A PASSO -->
<section class="passo-a-passo">
    <div class="container">
        <div class="section-body step-body">
            <!-- STEP -->
            <div class="step">
                <div class="step-header">
                    <i class="fa-solid fa-magnifying-glass"></i>   
                    <h2>ESCOLHA O SORTEIO</h2> 
                </div>
                <div class="step-body">
                    <p>
                        Escolha o prêmio que gostaria de concorrer, verifique a descrição, regulamento do sorteio e fotos. Em caso de dúvidas entre em contato com o administrador.
                    </p>
                </div>
                <span>1</span>
            </div>
            <!-- STEP -->
            <div class="step">
                <div class="step-header">
                    <i class="fa-solid fa-circle-check"></i>
                    <h2>SELECIONE SEUS NÚMEROS</h2> 
                </div>
                <div class="step-body">
                    <p>
                        Escolha o prêmio que gostaria de concorrer, verifique a descrição, regulamento do sorteio e fotos. Em caso de dúvidas entre em contato com o administrador.
                    </p>
                </div>
                <span>2</span>
            </div>
            <!-- STEP -->
            <div class="step">
                <div class="step-header">
                <i class="fa-solid fa-money-bill"></i>
                    <h2>FAÇA O PAGAMENTO</h2> 
                </div>
                <div class="step-body">
                    <p>
                        Escolha o prêmio que gostaria de concorrer, verifique a descrição, regulamento do sorteio e fotos. Em caso de dúvidas entre em contato com o administrador.
                    </p>
                </div>
                <span>3</span>
            </div>
            <!-- STEP -->
            <div class="step">
                <div class="step-header">
                <i class="fa-solid fa-clock"></i>
                    <h2>AGUARDE O SORTEIO</h2> 
                </div>
                <div class="step-body">
                    <p>
                        Escolha o prêmio que gostaria de concorrer, verifique a descrição, regulamento do sorteio e fotos. Em caso de dúvidas entre em contato com o administrador.
                    </p>
                </div>
                <span>4</span>
            </div>

        </div>
    </div>
  
</section>

<!-- MODAL MY REQUESTS -->
<div class="modal modal_my_requests">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Meus pedidos</h2>
            <span onclick="toggleMyRequestModal()">x</span>
        </div>
        <div class="modal-body">
            <p>Para consultar seus pedidos, digite seu número</p>
            <form method="POST">
                <label for="">
                    <input type="tel" name="phone_number" class="phone-number" maxlength="15" minlength="15">
                </label>
                <input type="submit" id="consult-request-btn" value="Consultar">
            </form>
        </div>
    </div>
</div>

<!-- BANNER INSTALL PWA -->
<div class="block__install" id="BlockInstall">
    <div class="inner">
        <div class="close" id="BlockInstallClose">
            <span>
              X
            </span>
        </div>
        <div class="logo">
            <img src="<?php echo base_url('public/img/favicon-32x32.png')?>" />
        </div>
        <div class="name">
            <span class="title">Nando MKT</span>
            <span class="description">Instale nosso aplicativo oficial.</span>
        </div>
        <div class="cta">
            <button id="BlockInstallButton" class="btn btn-outline">Instalar</button>
        </div>
    </div>
</div>


<footer>

    <p><a href="https://api.whatsapp.com/send?phone=553172465814" target="_blank">  Desenvolvido com ❤️ </a></p>

</footer>

<script src="<?php echo base_url('public/js/swiper-bundle.min.js')?>"></script>

<script type="module">

    var Swipes = new Swiper('.swiper-container', {
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
        },
    });

  //const swiper = new Swiper(...);
</script>


<script>
    /* FORMAT PHONE NUMBER */

    /* Máscaras ER */
    function mascara(o,f){
        v_obj=o
        v_fun=f
        setTimeout("execmascara()",1)
    }
    function execmascara(){
        v_obj.value=v_fun(v_obj.value)
    }
    function mtel(v){
        v=v.replace(/\D/g,""); //Remove tudo o que não é dígito
        v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
        v=v.replace(/(\d)(\d{4})$/,"$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
        return v;
    }
    
    document.querySelectorAll('.phone-number').forEach((e)=>{
        e.onkeyup = function(){
            mascara(this, mtel);
        }
    });


    document.querySelector('.modal_my_requests').onsubmit = async function(e){

        e.preventDefault();
        
        alert('AQUI');
        document.querySelector('#consult-request-btn').value = "Consultando...";
        document.querySelector('#consult-request-btn').setAttribute('disabled', 'disabled');

        
        let url = document.querySelector('body').getAttribute('data-url');
        let phone = document.querySelector('input[name=phone_number]').value;

        if(phone){

            let res = fetch(`${url}/meus-pedidos`,{
                method: 'POST',
                headers: {
                    'Content-Type' : 'Application/json',
                },
                body: JSON.stringify({phone})
            });
            
            let json = await res.json();
            
            if(json.user){
                window.location = `${url}/meus-pedidos`;
            } else {
                document.querySelector('#consult-request-btn').value = "Consultar";
                document.querySelector('#consult-request-btn').removeAttribute('disabled');
                alert('Não encontramos pedidos para seu número');
            }

        } else {
            alert('número inválido');
            document.querySelector('#consult-request-btn').innerText = "Consultar";
            document.querySelector('#consult-request-btn').removeAttribute('disabled');
        }

    }


    function toggleMyRequestModal(){
        document.querySelector('.modal_my_requests').classList.toggle('active');
        document.querySelector('input[name=phone_number]').focus();
    }

    <?php if(session()->getFlashdata('status')):?>
        $.notify('<?php echo session()->getFlashdata('status')['message'] ?>','<?php echo session()->getFlashdata('status')['status'] ?>')
    <?php endif ?>

    
    /* INSTALL APP */


    // ------------------------
    // Cookies methods found on w3schools - We need them to not annoy our visitors
    // https://www.w3schools.com/js/js_cookies.asp
    // ------------------------
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(";");
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == " ") {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function checkCookie() {
        var user = getCookie("username");
        if (user != "") {
            alert("Welcome again " + user);
        } else {
            user = prompt("Please enter your name:", "");
            if (user != "" && user != null) {
                setCookie("username", user, 365);
            }
        }
    }

    // ------------------------
    // Here starts our part
    // ------------------------
    $(document).ready(function () {
        // When the user clicks on Close, we need to keep this in mind and not annoy him again
        $("#BlockInstallClose").on("click", function (e) {
            $("#BlockInstall").removeClass("is-active");
            setCookie("BlockInstallCookieHide", 1, 14);
        });
    });

    // ------------------------
    // We listen to the `beforeinstallprompt` event
    // If the user has
    // ------------------------
    window.addEventListener("beforeinstallprompt", function (event) {
        // Don't display the standard one
        event.preventDefault();

        // We check if the user has the Don't Show Cookie stored. If not, we'll show him the banner.
        let cookieBlockInstallCookieHide = getCookie("BlockInstallCookieHide");

        console.log(cookieBlockInstallCookieHide);

        if (!cookieBlockInstallCookieHide) {
            $("#BlockInstall").addClass("is-active");
        }

        // Save the event to use it later
        window.promptEvent = event;
    });

    // If the visitor clicks on `Install` button, we'll show the banner
    document.addEventListener("click", function (event) {
        if (event.target.matches("#BlockInstallButton")) {
            addToHomeScreen();
        }
    });

    function addToHomeScreen() {
        // Install prompt
        window.promptEvent.prompt();

        // I added a Google Analytics Event so we can know how many installs we have
        window.promptEvent.userChoice.then(function (choiceResult) {
            if (choiceResult.outcome === "accepted") {
                gtag("event", "Installed PWA", {
                    event_category: "PWA",
                    value: 1,
                });
            } else {
                // Do nothing
            }
            window.promptEvent = null;
        });
    }


     
    
        


</script>

</body>
</html>