$(document).ready(function(){


	//блок загрузки
	var loading = "<div class='loading'> <center><img src='/media/img/loading.gif' style='padding-bottom:10px;'></center></div>";
	
	/**
	 *  Приём типа даты
	 */ 
	$("#build_category").click(function(event){
		
		//Ближайшая форма
		var form = $(this).closest('form');
		
		//Блокируем все события кнопки по умолчанию
		event.preventDefault();
		
		//Отправка аякс запроса
		$.ajax({
		   url:  "/user/statistics/build_category", 
		   data: form.serializeArray(), 
		   type: 'POST',
		   dataType: 'json',
		   beforeSend: function(){
				$('.category_statistics').html(loading);
				$(this).attr("disabled");
		   },
		   success:function(result){
				$('.category_statistics').highcharts(result);
		   }
		});
	});
	
	$( "#build_category" ).click();

});