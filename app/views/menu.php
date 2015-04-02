<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
	
		<div class="navbar-header">
			<!-- Кнопка раскрытия меню для моб. -->
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<!-- Лого -->
			<a id="main_logo" class="navbar-brand" href="/"><img src="<?= base_url(); ?>media/img/logo.png" alt="<?= lang('logo'); ?>"></a>
		</div>
		
		<!-- Меню -->
		<div class="collapse navbar-collapse navbar-right navbar-ex1-collapse">
			<ul id="main_nav" class="nav navbar-nav">
				<li>
					<a href="/"><span class="glyphicon glyphicon-home"></span><?= " ".lang('main'); ?></a>
				</li>
				
				<li>
					<a href="#" class="registration_modal" data-toggle="modal" data-target="#modal_registration_block">
						<span class="glyphicon glyphicon-road"></span>
						<?= " ".lang('registration'); ?>
					</a>
				</li>
			</ul>
		</div>
		
	</div>
</nav>