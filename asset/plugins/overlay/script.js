spinner = '';
function overlaySpinner($text, $duration)
{
    if($text === undefined) {
        $text = '';
    }
    
    if($duration === undefined) {
        $duration = 2e3;
    }
    
    var opts = {
		lines: 13, // The number of lines to draw
		length: 11, // The length of each line
		width: 5, // The line thickness
		radius: 17, // The radius of the inner circle
		corners: 1, // Corner roundness (0..1)
		rotate: 0, // The rotation offset
		color: '#FFF', // #rgb or #rrggbb
		speed: 1, // Rounds per second
		trail: 60, // Afterglow percentage
		shadow: false, // Whether to render a shadow
		hwaccel: false, // Whether to use hardware acceleration
		className: 'spinner', // The CSS class to assign to the spinner
		zIndex: 2e9, // The z-index (defaults to 2000000000)
		top: 'auto', // Top position relative to parent in px
		left: 'auto' // Left position relative to parent in px
	};
	var target = document.createElement("div");
	document.body.appendChild(target);
	spinner = new Spinner(opts).spin(target);
	iosOverlay({
		text: $text,
		duration: $duration,
		spinner: spinner
	});
}
function overlaySpinnerStop()
{    
    spinner.stop();   
    $('.ui-ios-overlay').remove();
}

function overlayMessage($text, $duration)
{
    
    if($text === undefined) {
        $text = 'Данных нет';
    }
    
    if($duration === undefined) {
        $duration = 2e3;
    }
    
    /*iosOverlay({
		text: $text,
		duration: $duration,
        icon: "/asset/plugins/overlay/img/cross.png"
	});*/
}