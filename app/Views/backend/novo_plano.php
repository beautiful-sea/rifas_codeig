

<div class="container add_raffle">
    <div class="container-header">
        <h1 class="title">Adicionar Plano</h1>
    </div>
    
    <div class="container-body">

        <form action="<?php echo base_url('dashboard/planos/novo-plano')?>" id="upload-form" class="dropzone" method="POST" enctype="multipart/form-data">

            <div class="row">
                <label for="" style="width:60%" class="mr">
                    Qtd de cotas
                    <input type="number" name="cotas" required>
                </label>

                <label for="" style="width: 20%">
                    Valor
                    <input type="phone" name="valor" class="money" required>
                </label>
            </div>
            <input type="submit" class="success" value="Adicionar Plano">
        </form>
        
    </div>
</div>

<div class="modal add_pack">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Adicionar Pacote</h2>
            <span onclick="toggleAddPack()">x</span>
        </div>
        <div class="modal-body">
            <form class="form_add_pack" method="POST">
                <label for="">
                    Quantidade de números
                    <input type="number" name="add_pack_quantity">
                </label>
                <label for="">
                    Valor
                    <input type="phone" name="add_pack_price" class="money">
                </label>
                <input type="submit" value="Adicionar">
            </form>
        </div>
    </div>
</div>

<!-- IMAGE LOADER VIEWER JAVASCRIPT -->

<script src="<?php echo base_url('public/dist/jquery.fileuploader.min.js')?>"></script>
<script src="<?php echo base_url('public/js/jquery.mask.js')?>"></script>


<script>
    
    

// enable fileuploader plugin
$('input.files').fileuploader({
    extensions: ["jpg", "jpeg", "png","webp"],
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
        onItemShow: function (e, i) {
        i.find(".fileuploader-thumbnails-input").insertAfter(e.html), "image" == e.format && e.html.find(".fileuploader-item-icon").hide()
        }
    },
    afterRender: function (e, i, a, l) {
        var n = e.find(".fileuploader-thumbnails-input"),
        r = $.fileuploader.getInstance(l.get(0));
        n.on("click", function () {
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
            return 'Browse ' + (options.limit == 1 ? 'file' : 'files');
        },
        feedback: function(options) {
            return 'Choose ' + (options.limit == 1 ? 'file' : 'files') + ' to upload';
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
                return 'Apenas ${limit} ' + (options.limit == 1 ? 'file' : 'files') + ' pode ser carregado.'
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

<script>
    $('.money').mask('000.000.000.000.000,00', {reverse: true});

    
    /* Desconto ativo ou inativo */
    document.querySelector('select[name=discount_status]').addEventListener('change', toggleDiscount);
  
    /* Evento para o modal de adicionar pack */
    document.querySelector('.form_add_pack').addEventListener('submit', (e)=>{
        e.preventDefault();

        // Pega os packs já adicionados 
        let data = document.querySelector('input[name=number_pack]').getAttribute('data-packs');

        if(data){
            data = JSON.parse(data);
        } else {
            data = [];
        }

        /* Pega a quantidade de números */
        let qnt_numbers = document.querySelector('input[name=add_pack_quantity]').value;
        let price = document.querySelector('input[name=add_pack_price]').value;
        let obj = { 'price': price, 'qnt_numbers': qnt_numbers}

        // Verifica se no array de objetos já contém o objeto com a quantidade de números
        if(data){
            let index = data.findIndex(e=>e.qnt_numbers == obj.qnt_numbers);
            if(index != -1){
                data[index] = {
                    'price' : price,
                    'qnt_numbers' : qnt_numbers
                }
            } else {
                data.push(obj);
            }
        } else {
            data.push(obj);
        }

        /* Gera os ícones pra ficarem dentro do input */

        document.querySelector('input[name=number_pack]').setAttribute('data-packs',JSON.stringify(data));
        document.querySelector('input[name=packs]').value = JSON.stringify(data);

        renderPacks();
        toggleAddPack();

    });

    /* Render nos packs */
    function renderPacks(){

        document.querySelector('#add_pack .packs').innerHTML = "";
        let packs = document.querySelector('input[name=number_pack]').getAttribute('data-packs');
        if(packs){

            packs = JSON.parse(packs);

            packs.forEach((p, id)=>{

                let pack = document.createElement('div');
                pack.classList.add('pack');
                pack.setAttribute('data-id', id);
                pack.innerHTML = `${p.qnt_numbers} números - R$ <span>${p.price}</span>`;
                pack.setAttribute('onclick', 'removePack(this)');

                document.querySelector('#add_pack .packs').appendChild(pack);

            });
        }
    }

    function removePack(obj){
        let packId = obj.getAttribute('data-id');
        let packs = document.querySelector('input[name=number_pack]').getAttribute('data-packs');
        if(packs){
            packs = JSON.parse(packs);
            let newPacks = packs.filter((p,id)=>id != packId);
            if(newPacks){
                document.querySelector('input[name=number_pack]').setAttribute('data-packs', JSON.stringify(newPacks));
            } else {
                document.querySelector('input[name=number_pack]').removeAttribute('data-packs');
            }
        }
        renderPacks();
    }

    /* Toggle modal add pack */
    function toggleAddPack(){
        document.querySelector('input[name=add_pack_quantity]').value = "";
        document.querySelector('input[name=add_pack_price]').value = "";
        document.querySelector('.modal.add_pack').classList.toggle('active');
    }

    /* TOGGLE DISCOUNT */
    function toggleDiscount(e){
       
        if(e.target.value == 'on'){
            // Ativo 
            document.querySelectorAll('.discount').forEach((i)=>{
                i.removeAttribute('disabled');
            });



        } else {
            // Inativo
            document.querySelectorAll('.discount').forEach((i)=>{
                i.setAttribute('disabled','disabled');
            });


        }
    }



</script>