<meta name="cmsmagazine" content="5867e6bf0d59c3149baf15b4c798b8df" />
<meta name="google-site-verification" content="5qTzzABuqC8408fLehdZJjAADsIhSSQ0PvUv4_y1NHk" />
<meta name='wmail-verification' content='3c52aa7d94db07e4420fae89dfa60966' />
<meta name="msvalidate.01" content="998BF65CE40ABD8F1DACF00755BFC533" />
<meta name="theme-color" content="#019cdf"/>
<meta name="yandex-verification" content="51b405d7879d2c0b" />



<?php
$isMobile=0;
if ( stripos( $_SERVER["HTTP_USER_AGENT"], 'android' ) or stripos( $_SERVER["HTTP_USER_AGENT"], 'iPhone' ) ) {
    $isMobile = 1;
}
if ( stripos( $_SERVER["HTTP_USER_AGENT"], 'iPad' ) ) {
    $isIpad   = 1;
    $isMobile = 1;
}
$nurl=explode('/',$_SERVER['REQUEST_URI']);
$nurl=array_pop($nurl);
$str = explode(' ',$nurl);
$str2 = explode(' ', '0 1 2 3 4 5 6 7 8 9');
$res = array_intersect($str, $str2);
if($res){
	echo '<meta name="robots" content="noindex,follow" />';
}
?>

<link rel="manifest" href="/push">
<link rel="manifest" href="/manifest">