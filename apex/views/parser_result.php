<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <script src="/asset/admin/js/libs/jquery-1.9.1.min.js"></script>
    </head>
<body>

<style>.inp { width: 150px; }</style>

<?php echo $error;?>

<br>
<div class="ajax"></div>
<br>

<p><?php echo anchor('parser', 'Парсить новый файл'); ?></p>

<br>
<br>

<script>
$(document).ready(function(){

    $.ajax({
        type: 'POST',
        url: '/parser/sql_insert',
        dataType: 'json',
        data: {
            folder: '<?php echo $folder; ?>',
            catalog: '<?php echo $catalog; ?>',
            files: '<?php echo $files; ?>'
        },
        success: function(data) {
            $('.ajax').append(data.ok);
        }
    });

});
</script>

</body>
</html>