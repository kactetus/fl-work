<!-- Блок логина -->
<div class="intro-header">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="intro-message">
					<h1><?= lang("login"); ?></h1>
					
					<!-- Форма для входа -->
					<form id="main_login_form" class="form-horizontal" role="form">
						<hr class="intro-divider">
					
						<!-- Оповещения -->
						<div id="result_login" style="width:400px; margin:0 auto;"></div>
					
					  <div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10">
						  <input name="login_email" type="email" class="form-control" id="login_email" placeholder="Email">
						</div>
					  </div>
					  <div class="form-group">
						<label for="inputPassword3" class="col-sm-2 control-label"><?= lang("password"); ?></label>
						<div class="col-sm-10">
						  <input name="login_pass" type="password" class="form-control" id="login_pass" placeholder="<?= lang("password"); ?>">
						</div>
					  </div>
					  <div id="main_under_login1" class="form-group">
						<div id="main_under_login2" class="col-sm-offset-2 col-sm-10">
						  <div id="main_forgot">
							<a href="remind" id="main_link_forgot"  data-toggle="modal" data-target="#reminder_block"><?= lang("to_recover"); ?></a>
						  </div>
						  <div id="main_remember">
							<label><input id="remember" name="remember" type="checkbox"><?= lang("remember"); ?></label>
						  </div>
						</div>
					  </div>
					  <div id="main_signin" class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
						  <button id="login" type="submit" class="btn btn-default"><?= lang("enter"); ?></button>
						</div>
					  </div>
					</form>
					
					<!-- Вход через соц. сети -->
					<hr class="intro-divider">
					<h3><?= lang("enter_with_help"); ?></h3><br>
					<script src="//ulogin.ru/js/ulogin.js"></script>
					<div id="uLogin" data-ulogin="display=panel;fields=email,first_name,last_name;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=http%3A%2F%2Ffl-work.esy.es%2Fguest%2Fregistration%2Freg_by_social"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Модальный блок регистрации -->
<div id="modal_registration_block" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
	
		<!-- Форма для регистрации -->
		<form id="registration_form">
		<h3><?= lang('registration'); ?></h3>
		<hr class="intro-divider">
		
			<!-- Оповещения -->
			<div id="result_reg"></div>
		
		  <div class="input-group input-group-lg">
			  <span class="input-group-addon">@</span>
			  <input name="reg_email" id="reg_email" type="text" class="form-control" placeholder="Email">
		  </div>
		  <br>
		  <div class="input-group input-group-lg">
			  <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
			  <input name="reg_pass1" id="reg_pass1" type="password" class="form-control" placeholder="<?= lang('password'); ?>">
		  </div>
		  <br>
		  <div class="input-group input-group-lg">
			  <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
			  <input name="reg_pass2" id="reg_pass2" type="password" class="form-control" placeholder="<?= lang('repeat_password'); ?>">
		  </div>
		  <br>
		  
		  <!-- Капча -->
		  <div id="captcha">
			<?= $captcha; ?>
		  </div>
		  
		  <br>
		  <div id="main_join" class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			  <button name="reg_sbm" id="join" class="btn btn-success btn-lg"><?= lang("join"); ?></button>
			</div>
		  </div>
		</form>
		
    </div>
  </div>
</div>

<!-- Модальный блок для напоминания пароля -->
<div id="reminder_block" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

		<!-- Форма для напоминания пароля -->
		<form id="registration_form">
		<h3><?= lang("recover"); ?></h3>

		<!-- Блоки оповещений -->
		<div id="loading_rec" class="loading" style="text-align:center; padding-bottom:10px; display:none;"> <img src='<?= base_url(); ?>media/img/loading.gif'></div>
		<div id="result_rec"></div>
			
		  <div class="input-group input-group-lg">
			  <span class="input-group-addon">@</span>
			  <input name="rec_email" id="rec_email" type="text" class="form-control" placeholder="Email">
		  </div>
		  <br>
		  
		  <!-- Капча -->
		  <div id="captcha1">
			<img src="<?=base_url()?>media/img/captcha/noise-picture.php">
			<input name="rec_captcha" id="rec_captcha" type="text" class="form-control" placeholder="<?= lang("captcha_code");?>">
		  </div>
		  
		  <br>
		  <div id="main_join" class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			  <button name="rec_sbm" id="rec_sbm" class="btn btn-success btn-lg"><?= lang("send"); ?></button>
			</div>
		  </div>
		</form>
	
    </div>
  </div>
</div>


<!-- 1-ый блок контента -->
<div class="content-section-a">
	<div class="container">
		<div class="row">
			<div class="col-lg-5 col-sm-6">
				<hr class="section-heading-spacer">
				<div class="clearfix"></div>
				<h2 class="section-heading"><?php echo lang('img1_header'); ?></h2>
				<p class="lead"><?php echo lang('img1_content'); ?> </p>
			</div>
			<div class="col-lg-5 col-lg-offset-2 col-sm-6">
				<img class="img-responsive" src="media/img/img1.jpg" alt="<?php echo lang('img1_alt'); ?>">
			</div>
		</div>
	</div>
</div>

<!-- 2-ой блок контента -->
<div class="content-section-b">
	<div class="container">
		<div class="row">
			<div class="col-lg-5 col-lg-offset-1 col-sm-push-6  col-sm-6">
				<hr class="section-heading-spacer">
				<div class="clearfix"></div>
				<h2 class="section-heading"><?php echo lang('img2_header'); ?></h2>
				<p class="lead"><?php echo lang('img2_content'); ?> </p>
			</div>
			<div class="col-lg-5 col-sm-pull-6  col-sm-6">
				<img class="img-responsive" src="media/img/img2.jpg" alt="<?php echo lang('img2_alt'); ?>">
			</div>
		</div>
	</div>
</div>

<!-- 3-ий блок контента -->
<div class="content-section-a">
	<div class="container">
		<div class="row">
			<div class="col-lg-5 col-sm-6">
				<hr class="section-heading-spacer">
				<div class="clearfix"></div>
				<h2 class="section-heading"><?php echo lang('img3_header'); ?></h2>
				<p class="lead"><?php echo lang('img3_content'); ?> </p>
			</div>
			<div class="col-lg-5 col-lg-offset-2 col-sm-6">
				<img class="img-responsive" src="media/img/img3.jpg" alt="<?php echo lang('img3_alt'); ?>">
			</div>
		</div>
	</div>
</div>

<!-- Блок "Поделиться с друзьями" -->
<div class="banner">
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<h2><?php echo lang('img4_header'); ?></h2>
			</div>
			<div class="col-lg-6">
				<ul class="list-inline banner-social-buttons">
					<script type="text/javascript">
						(function() {
							if (window.pluso)if (typeof window.pluso.start == "function") return;
							if (window.ifpluso==undefined) { window.ifpluso = 1;
							var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
							s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
							s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
							var h=d[g]('body')[0];
							h.appendChild(s);
						}})();
					</script>
					<li><div class="pluso" data-background="transparent" data-options="big,round,line,horizontal,nocounter,theme=05" data-services="vkontakte,odnoklassniki,facebook,twitter,google,moimir,email,linkedin,print"></div></li>
				</ul>
			</div>
		</div>
	</div>
</div>