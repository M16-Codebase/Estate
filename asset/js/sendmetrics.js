var sendEvent;

$(document).ready(function(){

	$('.sm_search').click(function(e){
		var label = $(this).attr("data-label");
		var category = $(this).attr("data-category");
        //var reach = $(this).data("reach");
		
        if (typeof ga !== 'undefined') {
            ga('send', 'event', category, 'click', label);
        }
        if (typeof yaCounter29760432 !== 'undefined') {
            yaCounter29760432.reachGoal(label);
        }
        if ('roistat' in window) {
            roistat.event.send(label);
        }
	});

	$('.sm_selectpage .filter-result-paging-link').click(function(e){
		var label = $(this).closest('.sm_selectpage').attr("data-label");
		var category = $(this).closest('.sm_selectpage').attr("data-category");
		
        if (typeof ga !== 'undefined') {
            ga('send', 'event', category, 'click', label);
        }
        if (typeof yaCounter29760432 !== 'undefined') { 
            yaCounter29760432.reachGoal(label);
        }
        if ('roistat' in window) {
            roistat.event.send(label);
        }
	});

	$('.form-zakaz .inp-name').keypress(function(e){
		
		if ($(".sm_zakaz").length > 0){
			var label = $(".sm_zakaz").attr("data-label");
			var category = 'Form.reserve';
			
            if (typeof ga !== 'undefined') {
                ga('send', 'event', category, 'FillName', label);
            }
            if (typeof yaCounter29760432 !== 'undefined') {
                yaCounter29760432.reachGoal(label);
            }
            if ('roistat' in window) {
                roistat.event.send(label);
            }
		}
	});

	$('.form-zakaz .inp-phone').keypress(function(e){
		
		if ($(".sm_zakaz").length > 0){
			var label = $(".sm_zakaz").attr("data-label");
			var category = 'Form.reserve';
            if (typeof ga !== 'undefined') {
                ga('send', 'event', category, 'FillPhone', label);
            }
            if (typeof yaCounter29760432 !== 'undefined') {
                yaCounter29760432.reachGoal(label);
            }
            if ('roistat' in window) {
                roistat.event.send(label);
            }
		}
	});

	$('.form-zakaz #ch-1').click(function(e){
   		if ($(".sm_zakaz").length > 0){
			var label = $(".sm_zakaz").attr("data-label");
			var category = 'Form.reserve';
			
            if (typeof ga !== 'undefined') {
                ga('send', 'event', category, 'FillPay', label);
            }
            if (typeof yaCounter29760432 !== 'undefined') {
                yaCounter29760432.reachGoal(label);
            }
            if ('roistat' in window) {
                roistat.event.send(label);
            }
		}
	});

	$('.form-zakaz #ch-2').click(function(e){
   		if ($(".sm_zakaz").length > 0){
			var label = $(".sm_zakaz").attr("data-label");
			var category = 'Form.reserve';
			
            if (typeof ga !== 'undefined') {
                ga('send', 'event', category, 'FillCredit', label);
            }
            if (typeof yaCounter29760432 !== 'undefined') {
                yaCounter29760432.reachGoal(label);
            }
            if ('roistat' in window) {
                roistat.event.send(label);
            }
		}
	});

	$('.form-zakaz #ch-3').click(function(e){
   		if ($(".sm_zakaz").length > 0){
			var label = $(".sm_zakaz").attr("data-label");
			var category = 'Form.reserve';
			
            if (typeof ga !== 'undefined') {
                ga('send', 'event', category, 'FillNews', label);
            }
            if (typeof yaCounter29760432 !== 'undefined') {
                yaCounter29760432.reachGoal(label);
            }
            if ('roistat' in window) {
                roistat.event.send(label);
            }
		}
	});

	$('.form-zakaz .submit-remodal').click(function(e){
   		if ($(".sm_zakaz").length > 0){
			var label = $(".sm_zakaz").attr("data-label");
			var category = 'Form.reserve';
			
            if (typeof ga !== 'undefined') {
                ga('send', 'event', category, 'Button', label);
            }
            if (typeof yaCounter29760432 !== 'undefined') {
                yaCounter29760432.reachGoal(label);
            }
            if ('roistat' in window) {
                roistat.event.send(label);
            }
		}
	});

    $('.submit-ask').click(function(e){
        let name   = $('#name').val();
        let phone   = $('#phone').val();
        let mail   = $('#email').val();
        let msg   = $('#comment').val();
        console.log(name);
        console.log(phone);
        console.log(mail);
        console.log(msg);
        $.ajax({
            type: 'POST',
            data:{
                'oauth_token': '05fa7144fd39051c2b3e0e512f357239',
                'name': name,
                'phone': phone,
                'email': mail,
                'message': msg
            },
            url: 'https://m16.kv1.ru/api/orders/post',
            success: function (data) {
                Swal.fire({
                    title: 'Ваша заявка отправлена!',
                    showConfirmButton: true,
                    text: 'Наши операторы вам перезвонят',
                    type: 'success',
                 });
                console.log(data);
                yaCounter29760432.reachGoal('novostroy-ost-zayav');
            },
            error: function (data) {
                Swal.fire({
                     title: 'Ваша заявка отправлена!',
                     showConfirmButton: true,
                     text: 'Наши операторы вам перезвонят',
                     type: 'success',
                });
                console.log(data);
            }
        });
    });
    
    /*
    event = {
        eventCategory: '',
        eventAction: '',
        eventLabel: ''
    }*/
    sendEvent = function (event) {
        if (typeof ga !== 'undefined') {
            ga('send', 'event', event.eventCategory, event.eventAction, event.eventLabel);
        }
        if (typeof yaCounter29760432 !== 'undefined') {
            yaCounter29760432.reachGoal(event.eventCategory);
        }
        if ('roistat' in window) {
            roistat.event.send(event.eventCategory);
        }
    }
});