<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
    </head>
<body>
<?php
    $this->load->model('catalog/admin_catalog_model','admin_catalog_model');
    $catalog = $this->admin_catalog_model->parent_id();
    $catalog_id[0] = '-- выбрать --';
    foreach($catalog as $p)
    {
        $catalog_id[$p['id']] = $p['name'];
    }
?>

<style>.inp { width: 150px; }</style>

<?php echo $error;?>

<?php echo form_open_multipart('parser/do_upload');?>

    <label>Выбрать комплекс:</label><br>
    <select name="catalog" class="inp">
    <?php foreach($catalog_id as $k=>$v): ?>
        <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
    <?php endforeach; ?>
    </select>

    <br><br>

    <label>Введи название папки(например: complex1):</label><br>
    <input type="text" name="folder" class="inp" />


    <br><br>

    <label>Выбрать файл(*.txt):</label><br>
    <input type="file" name="userfile" size="20" />

    <br /><br />

    <input type="submit" value="Парсить" />

</form>

<br>
<br>

<?php if(!empty($upload_data)): ?>
<?php if($upload_data['file_size']) echo 'Файл загружен.'; ?>
<br>
<?php endif; ?>

</body>
</html>