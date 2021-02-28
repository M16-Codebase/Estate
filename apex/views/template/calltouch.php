<script type="text/javascript">
(function($){$.fn.replaceText=function(b,a,c){return this.each(function(){var f=this.firstChild,g,e,d=[];if(f){do{if(f.nodeType===3){g=f.nodeValue;e=g.replace(b,a);if(e!==g){if(!c&&/</.test(e)){$(f).before(e);d.push(f)}else{f.nodeValue=e}}}}while(f=f.nextSibling)}d.length&&$(d).remove()})}})(jQuery);
jQuery(document).ready(function() {
$("body *").replaceText( /688 88 85/gi, "<div style='display:inline' class='call_phone_1'>688 88 85</div>" );
$("body *").replaceText( /688-88-85/gi, "<div style='display:inline' class='call_phone_2'>688-88-85</div>" );
});
</script>

<script type="text/javascript">
   (function(w, d, e) {
        var a = 'all', b = 'tou'; var src = b + 'c' +'h'; src = 'm' + 'o' + 'd.c' + a + src;
        var jsHost = (("https:" == d.location.protocol) ? "https://" : "http://")+ src;
        s = d.createElement(e); p = d.getElementsByTagName(e)[0]; s.async = 1; s.src = jsHost +"."+"r"+"u/d_client.js?param;ref"+escape(d.referrer)+";url"+escape(d.URL)+";cook"+escape(d.cookie)+";";
        if(!w.jQuery) { jq = d.createElement(e); jq.src = jsHost  +"."+"r"+'u/js/jquery-1.7.min.js'; p.parentNode.insertBefore(jq, p);}
        p.parentNode.insertBefore(s, p);
    }(window, document, 'script'));
</script>