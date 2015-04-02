$(document).ready(function(){


	//блок загрузки
	var loading = "<div class='loading'> <center><img src='/media/img/loading.gif' style='padding-bottom:10px;'></center></div>";
	
	/**
	 *  Приём типа даты
	 */ 
	$("#build_sites").click(function(event){
		
		//Ближайшая форма
		var form = $(this).closest('form');
		
		//Блокируем все события кнопки по умолчанию
		event.preventDefault();
		
		//Отправка аякс запроса
		$.ajax({
		   url:  "/user/statistics/build_sites", 
		   data: form.serializeArray(), 
		   type: 'POST',
		   dataType: 'json',
		   beforeSend: function(){
				$('.site_statistics').html(loading);
				$(this).attr("disabled");
		   },
		   success:function(result){
				$('.site_statistics').highcharts(result);
		   }
		});
	});
	
	$( "#build_sites" ).click();

});