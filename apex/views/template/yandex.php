<?php
$isMobile=0;
if ( stripos( $_SERVER["HTTP_USER_AGENT"], 'android' ) or stripos( $_SERVER["HTTP_USER_AGENT"], 'iPhone' ) ) {
    $isMobile = 1;
}
if ( stripos( $_SERVER["HTTP_USER_AGENT"], 'iPad' ) ) {
    $isIpad   = 1;
    $isMobile = 1;
}
if(!$isMobile){
?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(29760432, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true,
        trackHash:true
   });

</script>
<?}?>
<noscript><div><img src="https://mc.yandex.ru/watch/29760432" style="position:absolute; left:-9999px;" alt="" /></div></noscript>