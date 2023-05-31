

<div class="container add_raffle">
    <div class="container-header">
        <h1 class="title">Editar rifa</h1>
    </div>
    
    <div class="container-body">

        <form action="<?php echo base_url('/dashboard/rifas/editar')?>/<?php echo $raffle->id ?>" id="upload-form" class="dropzone" method="POST" enctype="multipart/form-data">

            <div id="dropzonePreview" class="dz-default dz-message raffle-images" >
              
                <input type="file" name="files[]" class="files">
            </div>

            <div id="previewImages">

            </div>
            <div class="row">
                <label for="" style="width:60%" class="mr">
                    Título da rifa
                    <input type="text" name="title" required value="<?php echo $raffle->title ?>">
                </label>
                <label for="" style="width: 20%" class="mr">
                    Qnt de números
                    <input type="phone" maxlength="6" readonly  disabled value="<?php echo $raffle->number_of_numbers ?>"> 
                </label>

                <label for="" style="width: 20%">
                    Valor unitário
                    <input type="phone" class="money" name="price" required  value="<?php echo $raffle->price ?>">
                </label>
            </div>
            <div class="row">
                <label style="width:30%" class="mr">
                    Categoria da rifa
                    <select name="id_category">
                        <option value=""></option>
                        <?php if($categories):?>
                            <?php foreach($categories as $c):?>
                                <option value="<?php echo $c->id ?>" <?php echo $raffle->id_category == $c->id ? 'selected':''?>><?php echo $c->title ?></option>
                            <?php endforeach ?>
                        <?php endif ?>

                    </select>
                </label>

                <label style="width: 20%" class="mr">
                    Tipo de rifa
                    
                    <select class="type" value="<?php echo $raffle->type ?>" aria-disabled="true" disabled>
                        <option value="auto" <?php echo $raffle->type == 0?'selected':''?>>Automática</option>
                        <option value="manual" <?php echo $raffle->type == 1?'selected':''?>>Manual</option>
                    </select>
                </label>

                <label style="width: 20%" class="mr">
                    Data do sorteio (Opcional)
                    <input type="datetime-local" name="draw_date"  value="<?php echo $raffle->draw_date ?>">
                </label>
                <label style="width: 30%" >
                    Gateway de pagamento
                    <select name="gateway">
                        <option value="paggue" <?php echo $raffle->gateway == 'paggue' ? 'selected':''?>>Paggue</option>
                        <option value="mp" <?php echo $raffle->gateway == 'mp' ? 'selected':''?>>Mercado Pago</option>
                    </select>
                </label>
            </div>
            <div class="row">
                <label style="width:25%" class="mr">
                    Desconto
                    <select name="discount_status">
                        <option value="off" <?php echo $raffle->discount_status == 0?'selected':''?>>Desabilitado</option>
                        <option value="on" <?php echo $raffle->discount_status == 1?'selected':''?>>Habilitado</option>
                    </select>
                </label>

                <label style="width: 25%"  class="mr discount">
                    Tipo de desconto
                    <select name="discount_type" disabled class="discount"  value="<?php echo $raffle->discount_type ?>">
                        <option value="fixed_value" >Valor fixo</option>
                    </select>
                </label>

                <label  style="width: 25%"  class="mr discount">
                    A partir de 
                    <input type="phone" name="discount_quantity" disabled class="discount"  value="<?php echo $raffle->discount_quantity?>"> 
                </label>
                <label style="width: 25%" class="discount">
                    Cada número sai à
                    <input type="phone" name="discount_price" disabled class="discount money"  value="<?php echo $raffle->discount_price ?>">
                </label>
            </div>
            <label id="add_pack">
                Pacotes de números
                <input type="text" name="number_pack" readonly data-packs="<?php if($raffle->packs){ echo htmlspecialchars($raffle->packs); }?>">
                <span id="toggleAddPack" onclick="toggleAddPack()"><i class="fa-solid fa-circle-plus"></i></span>
                <div class="packs"></div>
            </label>
            <label id="add_pack">
                Grupo WhatsApp (opcional)
                <input type="url" name="wp_group" value="<?php echo $raffle->wp_group ?>">
            </label>
            <label id="pixels">
                Pixels (Separe por vírgula)
                <input type="text" name="pixels" value="<?php echo $raffle->pixels ?>">
            </label>
            <input type="hidden" name="packs" value="<?php echo htmlspecialchars($raffle->packs) ?>">
            <label>
                Descrição da rifa
                <textarea name="description" cols="30" rows="10"><?php echo $raffle->description ?></textarea>
            </label>
            <input type="submit" class="success" value="Atualizar rifa">
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
        startImageRenderer: true,
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
        maxHeight: null,

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
        removeConfirmation: '?',
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
    files: [
        <?php if($images):?>
            <?php foreach($images as $img): ?>
                {
                    name: '<?php echo $img ?>', // file name
                    size: 1024, // file size in bytes
                    type: 'image/<?php echo str_replace('.','',strstr($img, '.', false))?>', // file MIME type
                    file: '<?php echo str_replace('\\','/', base_url()) ?>/public/images/<?php echo $img ?>', // file path
                    local: '<?php echo str_replace('\\','/', base_url()) ?>/public/images/<?php echo $img ?>', // file local
                    data: {
                        thumbnail: '<?php echo base_url() ?>/public/images/<?php echo $img ?>',
                        readerCrossOrigin: 'anonymous', // fix image cross-origin issue (optional)
                        readerForce: true, // prevent the browser cache of the image (optional)
                        readerSkip: false, // skip file from reading by rendering a thumbnail (optional)
                        popup: false, // remove the popup for this file (optional)
                        listProps: {}, // custom key: value attributes in the fileuploader's list (optional)
                        

                    }
                },
            <?php endforeach ?>
        <?php endif ?>
    ],
    clipboardPaste: 2000
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
        if(data){
            document.querySelector('input[name=number_pack]').setAttribute('data-packs',JSON.stringify(data));
            document.querySelector('input[name=packs]').value = JSON.stringify(data);
        }
      

        renderPacks();
        toggleAddPack();

    });

    /* Render nos packs */
    function renderPacks(){

        document.querySelector('#add_pack .packs').innerHTML = "";
        let packs = document.querySelector('input[name=number_pack]').getAttribute('data-packs');

        if(packs){

            packs = JSON.parse(packs);

            if(packs){
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
    }

    function removePack(obj){

        let packId = obj.getAttribute('data-id');
        let packs = document.querySelector('input[name=number_pack]').getAttribute('data-packs');

        if(packs){

            packs = JSON.parse(packs);
            packs = packs.filter((p,id)=>id != packId);


            if(Object.keys(packs).length != 0 && packs.constructor == Object){
                document.querySelector('input[name=number_pack]').setAttribute('data-packs', JSON.stringify(packs));
            } else {
                document.querySelector('input[name=number_pack]').removeAttribute('data-packs');
                document.querySelector('input[name=packs]').value = '';
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
    function toggleDiscount(){
        e = document.querySelector('select[name=discount_status]');

        if(e.value == 'on'){
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


    renderPacks();



</script>