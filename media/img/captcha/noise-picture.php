<?php
	/**
	 * Каптча
	 */ 
	 
	//Старт сессии
	session_start();

	//Кол-во генерируемых символов
	$nchars = 5;

	//Массив с символами
	$arr = array("a","b","c","d","e","f","g","h","i","j",
				 "k","l","m","n","o","p","q","r","s","t",
				 "u","v","w","x","y","z","1","2","3","4",
				 "5","6","7","8","9","0");

	//Генерируем строку в каптче
	for($i = 0; $i <= $nchars; $i++){
		$str .= $arr[rand(0,35)];
	}

	//Добавляем строку в сессию
	$_SESSION['randStr']=$str;

	//Работа с gd2 библиотекой
	$img = imageCreateFromJpeg("noise.jpg");
	$red = imageColorAllocate($img,120,0,0);
	imageAntiAlias($img,true); 
	$x      = 7;
	$y      = 30;
	$deltaX = 20;
	$size = rand(18,30);

	//В цикле рандомно размещаем символы
	for($j = 0; $j <= $nchars; $j++){
		$size  = rand(18,30);
		$angle = -30 + rand(0,60);
		$x+=$deltaX;
		imageTtfText($img,$size,$angle,$x,$y,$red,"georgia.ttf",$str{$j});
	}

	//Добавляем фигуры в каптчу
	imageArc($img,30,10,20,10,90,0,$red);	
	imageLine($img,5,5,200,5,$red);
	imageLine($img,5,30,200,30,$red);
	imageLine($img,5,40,200,40,$red);

	//Отправляем тип заголовока 
	header("Content-Type: image/jpg");
	imageJPEG($img,"",90);
?>
