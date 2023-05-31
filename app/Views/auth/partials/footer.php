        <!-- END RIGHT-SIDE -->
        </div>
    <!-- END MAIN CONTENT -->
    </div>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="<?php echo base_url('public/js/notify.min.js')?>"></script>

<script>

    <?php if(isset($errors) && !empty($errors)):?>

        <?php foreach($errors as $error):?>
            $.notify('<?php echo $error ?>', "error");
        <?php endforeach ?>

    <?php endif ?>
</script>
</body>
</html>