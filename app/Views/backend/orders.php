<div class="container orders_page">

    <div class="container-header">

        <h1 class="title">Todos os pedidos</h1>

        <div class="row">

            <form action="">

                <div class="row-input">

                    <label for="">

                        <input type="text" name="search" placeholder="Pesquise pelo nome do cliente ou pelo número ganhador" value="<?php echo $search ?>" autocomplete="off">

                    </label>

                </div>



                <div class="row-input">

                    <label for="">

                        <input type="text" name="phone" class="phone-number" placeholder="Telefone" value="<?php echo $phone ?>" autocomplete="off">

                    </label>

                </div>

                <div class="row-input">

                    <label for="">

                        <select name="id_raffle" id="">

                            <option value="">Todas</option>



                            <?php foreach ($raffles as $raffle) : ?>

                                <option <?php echo $id_raffle == $raffle->id ? 'selected' : '' ?> value="<?php echo $raffle->id ?>"><?php echo $raffle->title ?></option>

                            <?php endforeach ?>



                        </select>

                    </label>

                </div>

                <div class="row-input">

                    <label for="">

                        <select name="status" id="">

                            <option value="">Todos</option>

                            <option <?php echo $status == 'pago' ? 'selected' : '' ?> value="pago">Pago</option>

                            <option <?php echo $status == 'pendente' ? 'selected' : '' ?> value="pendente">Aguardando pgto.</option>

                        </select>

                    </label>

                </div>

                <button><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>

            </form>

        </div>

    </div>

    <div class="container-body">



        <div class="orders">

            <div class="table">

                <table>

                    <thead>

                        <tr>

                            <th>Cliente</th>

                            <th>Rifa</th>

                            <th>Qnt.</th>

                            <th>Total</th>

                            <th>Data</th>

                            <th>Status</th>

                            <th>Ações</th>

                        </tr>

                    <tbody>



                        <?php if ($orders) : ?>



                            <?php foreach ($orders as $order) : ?>



                                <?php

                                $numbers = json_decode($order->numbers);

                                $n = str_replace('(', '', $order->customer->phone);

                                $n = str_replace(')', '', $n);

                                $n = str_replace('-', '', $n);

                                $n = str_replace(' ', '', $n);

                                ?>



                                <tr>

                                    <td>

                                        <a href="https://api.whatsapp.com/send?phone=55<?php echo $n ?>" target="_blank"><i class="fa-brands fa-whatsapp"></i> <?php echo $order->customer->name ?></a>

                                    </td>

                                    <td>

                                        <?php echo $order->raffle->title ?>

                                    </td>

                                    <td>

                                        <?php echo count($numbers) ?>

                                    </td>

                                    <td>

                                        R$ <?php echo number_format($order->price, 2, ',', '.'); ?>

                                    </td>

                                    <td>

                                        <?php echo date('d/m H:i', strtotime($order->created_at)); ?>

                                    </td>

                                    <td>

                                        <?php echo $order->status ? 'Pago' : 'Aguardando pgto'; ?>

                                    </td>

                                    <td>

                                        <a href="#" onclick="myNumbers(this)" data-numbers='<?php echo json_encode($numbers) ?>' class="see-numbers">Ver números</a>


                                        <script>
                                            async function getNumbers(el) {
                                                var loading = document.getElementById('loadingSystem');
                                                loading.classList.remove('d-none');

                                                var orderId = el.dataset.order;
                                                var numbers = null;

                                                await $.ajax({
                                                    url: "/dashboard/pedido/getNumber/" + el.dataset.order,
                                                    type: 'GET',
                                                    success: function(response) {
                                                        var response = JSON.parse(response);

                                                        var orderNumbers = JSON.parse(response.orderNumbers);
                                                        var raffleNumbers = JSON.parse(response.raffleNumbers);



                                                        raffleNumbers.forEach(function(num) {
                                                            if (num.order_id == orderId) {
                                                                num.status = 2;
                                                            }
                                                        })

                                                        numbers = raffleNumbers;
                                                    }
                                                })

                                                aprovarOrder(orderId, numbers);
                                            }

                                            function aprovarOrder(orderId, raffleNumbers) {
                                                $.ajax({
                                                    url: "/aprovarPedido",
                                                    type: 'POST',
                                                    dataType: 'json',
                                                    data: {
                                                        order: orderId,
                                                        numbers: JSON.stringify(raffleNumbers)
                                                    },
                                                    success: function(response) {
                                                        location.reload()
                                                    }
                                                })
                                            }
                                        </script>

                                        <?php if (!$order->status) : ?>



                                            <a href="#" onclick="getNumbers(this)" data-order="<?php echo $order->id ?>" class="approve">Aprovar</a>



                                        <?php else : ?>



                                            <a href="#" data-order="<?php echo htmlspecialchars(json_encode($order)) ?>" onclick="setNewWinner(this)" class="winner">Ganhador</a>



                                        <?php endif ?>





                                        <a href="<?php echo base_url('dashboard/pedidos/excluir') ?>/<?php echo $order->id ?>" onclick="return confirm('Tem certeza que deseja excluir o pedido?')" class="delete">Excluir</a>

                                    </td>

                                </tr>

                            <?php endforeach ?>

                        <?php endif ?>

                    </tbody>

                    </thead>



                </table>

            </div>

            <?php if (isset($pager)) : ?>

                <?php echo $pager->links() ?>

            <?php endif ?>



        </div>

    </div>

</div>





<div class="modal my-numbers">

    <div class="modal-content">

        <div class="modal-header">

            <h2>Números</h2>

            <span onclick="toggleMyNumbers()">x</span>

        </div>

        <div class="modal-body">

            <div class="numbers">

                <div class="number"></div>

                <div class="number"></div>

            </div>

        </div>

    </div>

</div>



<!-- MODAL GANHADOR -->

<div class="modal modal-winner <?php echo (isset($user)) ? 'active' : '' ?>">

    <div class="modal-content">

        <div class="modal-header">

            Novo ganhador

            <span onclick="toggleNewWinner()">X</span>

        </div>



        <div class="modal-body">



            <form method="POST">

                <input type="hidden" name="id_customer">

                <label for="">

                    Nome

                    <input type="text" name="name">

                </label>



                <label for="">

                    Cota

                    <input type="text" name="number">

                </label>



                <label for="">

                    Posição

                    <select name="position" id="">

                        <option value="1">1º Lugar</option>

                        <option value="2">2º Lugar</option>

                        <option value="3">3º Lugar</option>

                        <option value="4">4º Lugar</option>

                        <option value="5">5º Lugar</option>

                    </select>

                </label>



                <label for="">

                    Rifa

                    <select name="id_raffle" id="">

                        <?php if ($raffles) : ?>

                            <?php foreach ($raffles as $r) : ?>

                                <option value="<?php echo $r->id ?>" <?php echo (isset($raffle) && $raffle->id == $r->id) ? 'selected' : '' ?>><?php echo $r->title ?></option>

                            <?php endforeach ?>

                        <?php endif  ?>

                    </select>

                </label>

                <input type="submit" value="DEFINIR GANHADOR E FINALIZAR A RIFA">

            </form>



        </div>

    </div>

</div>





<script>
    function toggleNewWinner() {

        document.querySelector('.modal.modal-winner').classList.toggle('active');

    }



    function toggleMyNumbers() {

        document.querySelector('.modal.my-numbers').classList.toggle('active');

    }



    function myNumbers(obj) {

        let numbers = JSON.parse(obj.getAttribute('data-numbers'));



        document.querySelector('.my-numbers .numbers').innerText = "";

        numbers.forEach((n) => {

            let div = document.createElement('div');

            div.classList.add('number');

            div.innerText = n;

            document.querySelector('.my-numbers .numbers').appendChild(div);

        });



        toggleMyNumbers();

    }



    function setNewWinner(obj)

    {

        toggleNewWinner();



        let data = JSON.parse(obj.getAttribute('data-order'));

        let search = document.querySelector('input[name=search]').value;


        if (data)

        {

            //console.log(data);



            document.querySelector('input[name=name]').value = data.customer.name;

            document.querySelector('input[name=id_customer]').value = data.id_customer;

            document.querySelector('select[name=id_raffle]').value = data.id_raffle;



            if (isNumeric(search))

            {

                document.querySelector('input[name=number]').value = search;

            }

        }





    }



    function isNumeric(str) {

        var er = /^[0-9]+$/;

        return (er.test(str));

    }
</script>

<script>
    function mascara(o, f) {
        v_obj = o
        v_fun = f
        setTimeout("execmascara()", 1)
    }

    function execmascara() {
        v_obj.value = v_fun(v_obj.value)
    }
    function mtel(v){
        v=v.replace(/\D/g,""); //Remove tudo o que não é dígito
        v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
        v=v.replace(/(\d)(\d{4})$/,"$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
        return v;
    }

    document.querySelectorAll('.phone-number').forEach((e) => {
        e.onkeyup = function() {
            mascara(this, mtel);
        }
    });
</script>