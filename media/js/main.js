/**
 *  Функции могут выполняться после полной загрузки страницы
 */
$(document).ready(function(){

	//блок загрузки
	var loading = "<div class='loading'> <center><img src='/media/img/loading.gif' style='padding-bottom:10px;'></center></div>";

	
	/**
	 *  Регистрация пользователя
	 */
	$("#join").click(function(event){
	
		//Эмуляция перезагрузки капчи
		$("#recaptcha_reload").click();
		
		//Ближайшая форма
		var form = $(this).closest('form');
		
		//Блокируем все события кнопки по умолчанию
		event.preventDefault();
		
		//Отправка аякс запроса
		$.ajax({
		   url:  "/guest/registration/do_registration", 
		   data: form.serializeArray(), 
		   type: 'POST',
		   dataType: 'json',
		   beforeSend: function(){
				$('#result_reg').html(loading);
				$(this).attr("disabled");
		   },
		   success:function(result){
				if( result.msg == 'ok') {
					$('#result_reg').css("display","none");
					window.location.href = "/user/projects";
					return;
				}
				else
					$('#result_reg').html(result.msg);
		   }
		});
	});
	
	
	/**
	 *  Авторизация пользователя
	 */
	$("#login").click(function(event){
		
		//Ближайшая форма
		var form =  $(this).closest('form');
		
		//Блокируем все события кнопки по умолчанию
		event.preventDefault();
		
		//Отправка аякс запроса
		$.ajax({
		   url:  "/guest/auth/login", 
		   data: form.serializeArray(), 
		   type: 'POST',
		   dataType: 'json',
		   beforeSend: function(){
				$('#result_login').html(loading);
				$(this).attr("disabled");
		   },
		   success:function(result){
				if( result.msg == 'ok') {
					$('#result_login').css("display","none");
					window.location.href = "/user/projects";
					return;
				}
				else
					$('#result_login').html(result.msg);
		   }
		});
	});
	
	
	/**
	 *  Напоминание пароля
	 */
	$("#rec_sbm").click(function(event){

		//Ближайшая форма
		var form  =  $(this).closest('form');
		
		//Блокируем все события кнопки по умолчанию
		event.preventDefault();
		
		//Отправка аякс запроса
		$.ajax({
		   url:  "/guest/auth/remind_pass", 
		   data: form.serializeArray(), 
		   type: 'POST',
		   dataType: 'json',
		   beforeSend: function(){
				$('#result_rec').html(loading);
				$(this).attr("disabled");
		   },
		   success:function(result){
				$('#result_rec').html(result.msg);
		   }
		});
	});
	
	
	/**
	 *  Filter Agree
	 */
	$("#filter_agree").click(function(event){

		//Ближайшая форма
		var form  =  $(this).closest('form');
		
		//Блокируем все события кнопки по умолчанию
		event.preventDefault();
		
		//Отправка аякс запроса
		$.ajax({
		   url:  "/user/projects/filter_agree", 
		   data: form.serializeArray(), 
		   type: 'POST',
		   dataType: 'json',
		   beforeSend: function(){
				$('#result_filter_agree').html(loading);
				$(this).attr("disabled");
		   },
		   success:function(result){
				if( result.msg == 'ok') {
					$('#result_filter_agree').css("display","none");
					window.location.href = "/user/projects";
					return;
				}
				else
					$('#result_filter_agree').html(result.msg);
		   }
		});
	});
	
	/**
	 *  Filter Save
	 */
	$("#save_filter").click(function(event){

		//Ближайшая форма
		var form  =  $(this).closest('form');
		
		//Блокируем все события кнопки по умолчанию
		event.preventDefault();
		
		//Отправка аякс запроса
		$.ajax({
		   url:  "/user/projects/filter_save", 
		   data: form.serializeArray(), 
		   type: 'POST',
		   dataType: 'json',
		   beforeSend: function(){
				$('#result_filter_save').html(loading);
				$(this).attr("disabled");
		   },
		   success:function(result){
				if( result.msg == 'ok') {
					$('#result_filter_save').css("display","none");
					window.location.href = "/user/projects";
					return;
				}
				else
					$('#result_filter_save').html(result.msg);
		   }
		});
	});
	
	
	/**
	 *  Project Save
	 */
	$(".save_project_link").click(function(event){

		//Ближайшая форма
		var form  =  $(this).closest('form');
		var div   =  $(this).closest('div');
		var project =  $(this).closest('div.project_post');
		
		//Блокируем все события кнопки по умолчанию
		event.preventDefault();
		
		//Отправка аякс запроса
		$.ajax({
		   url:  "/user/projects/save_project", 
		   data: form.serializeArray(), 
		   type: 'POST',
		   dataType: 'json',
		   beforeSend: function(){
				div.html(loading);
				$(this).attr("disabled");
		   },
		   success:function(result){
				if( result.msg == 'ok') {
					div.html("<a class='glyphicon glyphicon-saved' id='project_saved_link' title='Добавлено'></a>");
					project.css("background","rgba(0,255,0,0.2)");
					return;
				}
				else
					div.html("(o_O)");
		   }
		});
	});
	
	/**
	 *  Filter Delete
	 */
	$(".del_filter").click(function(event){

		//Ближайшая форма
		var form  =  $(this).closest('form');
		var div   =  $(this).closest('table.filter_list_item tr');
		
		//Блокируем все события кнопки по умолчанию
		event.preventDefault();

		//Отправка аякс запроса
		$.ajax({
		   url:  "/user/projects/del_filter", 
		   data: form.serializeArray(), 
		   type: 'POST',
		   dataType: 'json',
		   beforeSend: function(){
				div.html(loading);
				$(this).attr("disabled");
		   },
		   success:function(result){
				if( result.msg == 'ok')
					div.remove();
				else
					div.html("(o_O)");
		   }
		});
	});
	
	
	/**
	 *  Project Delete
	 */
	$(".delete_project_link").click(function(event){

		//Ближайшая форма
		var form    =  $(this).closest('form');
		var project =  $(this).closest('.project_post');
		
		//Блокируем все события кнопки по умолчанию
		event.preventDefault();
		
		//Отправка аякс запроса
		$.ajax({
		   url:  "/user/favorite/del_project", 
		   data: form.serializeArray(), 
		   type: 'POST',
		   dataType: 'json',
		   beforeSend: function(){
				project.html(loading);
				$(this).attr("disabled");
		   },
		   success:function(result){
				if( result.msg == 'ok'){
					project.next("hr").remove();
					project.remove();
				}
				else
					project.html("(o_O)");
		   }
		});
	});
	

});