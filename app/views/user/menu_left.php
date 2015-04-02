<nav id="left_menu" class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="side-menu">
			<li>
				<a href="<?php echo base_url("user/projects"); ?>"><i class="glyphicon glyphicon-comment"></i> <?= lang("projects"); ?></a>
			</li>
			<li>
				<a href="<?php echo base_url("user/favorite"); ?>"><i class="glyphicon glyphicon-tasks"></i> <?= lang("favorite"); ?></a>
			</li>
			<li>
				<a href="<?php echo base_url("user/statistics/sites"); ?>"><i class="glyphicon glyphicon-stats"></i> <?= lang("site_statistics"); ?></a>
			</li>
			<li>
				<a href="<?php echo base_url("user/statistics/budget"); ?>"><i class="glyphicon glyphicon-stats"></i>  <?= lang("budget_statistics"); ?></a>
			</li>
			<li>
				<a href="<?php echo base_url("user/statistics/budget_types"); ?>"><i class="glyphicon glyphicon-stats"></i> <?= lang("b_type_statistics"); ?></a>
			</li>
			<li>
				<a href="<?php echo base_url("user/statistics/categories"); ?>"><i class="glyphicon glyphicon-stats"></i>  <?= lang("category_statistics"); ?></a>
			</li>
		</ul>
	</div>
</nav>