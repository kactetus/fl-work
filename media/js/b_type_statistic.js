$(document).ready(function(){


	//блок загрузки
	var loading = "<div class='loading'> <center><img src='/media/img/loading.gif' style='padding-bottom:10px;'></center></div>";
	
	/**
	 *  Приём типа даты
	 */ 
	$("#build_b_type").click(function(event){
		
		//Ближайшая форма
		var form = $(this).closest('form');
		
		//Блокируем все события кнопки по умолчанию
		event.preventDefault();
		
		//Отправка аякс запроса
		$.ajax({
		   url:  "/user/statistics/build_b_type", 
		   data: form.serializeArray(), 
		   type: 'POST',
		   dataType: 'json',
		   beforeSend: function(){
				$('.b_type_statistics').html(loading);
				$(this).attr("disabled");
		   },
		   success:function(result){
				$('.b_type_statistics').highcharts(result);
		   }
		});
	});
	
	$( "#build_b_type" ).click();

});