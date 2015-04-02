<div id="wrapper">
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
		<div class="container">
		
			<div class="navbar-header">	
				<!-- Лого -->
				<a href="/"><img src="<?=base_url();?>media/img/logo.png" height="50" alt="<?= lang('logo'); ?>"></a>		
			</div>
			
			<!-- Меню -->
			<div class="collapse navbar-collapse navbar-right navbar-ex1-collapse">
				<ul id="main_nav" class="nav navbar-nav">
					<li>
						<a href="<?php echo base_url(); ?>user/projects">
							<span class="glyphicon glyphicon-user"></span>
							<?= " ".$this->session->userdata('is_user_logged'); ?>
						</a>
					</li>
					
					<li>
						<a id="logout" href="<?php echo base_url(); ?>guest/auth/logout">
							<span class="glyphicon glyphicon-log-out"></span>
							<?= " ".lang('exit'); ?>
						</a>
					</li>
				</ul>
			</div>
			
		</div>
	</nav>
	<!-- /.navbar-static-top -->