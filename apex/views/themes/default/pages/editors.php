<?php
$authors=$this->load->module('news')->getAuthors();
?>
<style>
    .mse{
        background: #f4f4f4;
    }
    .author-img {
        width: 200px;
        height: 200px;
        border: 5px solid rgb(51, 157, 219);
        border-radius: 100px;
    }
    .flexable{
        display: flex;
        flex-wrap: wrap;
    }
    .author{
        margin: 20px;
        padding: 10px;
        display: flex;
        flex-direction: row;
        background: white;
        cursor: pointer;
        transition: transform 100ms linear 0ms, box-shadow 100ms linear 0ms, z-index 100ms linear 0ms;
    }
    .author-desc{
        margin-left: 20px;
    }
    .author-name{
        padding: 10px 10px 5px 10px;
        font-size: 2.5em;
    }
    .author-occ{
        padding-left: 24px;
        font-size: 1.2em;
        color: gray;
    }
    .author-info{
        margin: 15px 15px 15px 24px;
    }
    .author:hover{
        box-shadow: 9px 9px 5px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);
        transform: rotate(0deg) scale(1.05,1.05);
        z-index: 999;
        transition: transform 100ms linear 0ms, box-shadow 100ms linear 0ms, z-index 100ms linear 0ms;
    }
    .author-nick{
        display: none;
    }
</style>
<section class="mse">
    <div class="container">
        <div class="head-redact">
            <h1>Наша редакция:</h1>
        </div>
        <br>
        <div class="flexable">
            <?php foreach ($authors as $author){?>
            <div class="author">
                <div class="author-nick">
                    <? echo $author['nickname'];?>
                </div>
                <div class="author-img-block">
                    <img class="author-img" src="<? echo $author['image_full'];?>">
                </div>
                <div class="author-desc">
                    <div class="author-name">
                        <? echo $author['name'];?>
                    </div>
                    <div class="author-occ">
                        <? echo $author['occ'];?>
                    </div>
                    <div class="author-info">
                        <? echo $author['info'];?>
                    </div>
                </div>
            </div>
            <? } ?>
        </div>
    </div>
</section>
<script>
    $('.author').click(function() {
        let a_name=$(this).find('.author-nick').text();
        console.log(a_name);
        window.location='/editor?name='+del_probel(a_name);
    });
    function del_probel(str) {
        str = str.replace(/\s/g, '');
        return str;
    }
</script>