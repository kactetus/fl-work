<?php echo $template['partials']['menu_top']; ?>
<?php echo $template['partials']['menu_left']; ?>

	<div id="pr_container">
		<div id="container" class="site_statistics"></div>
		
		<div class="set_date_type">
			<br>
				<form>
					<label><input type="radio" id="per_day"    value="per_day"    name="dates"> <?= lang("per_day"); ?> </label>&nbsp;
					<label><input type="radio" id="per_week"   value="per_week"   name="dates" checked="checked"> <?= lang("per_week"); ?> </label>&nbsp;
					<label><input type="radio" id="per_month"  value="per_month"  name="dates"> <?= lang("per_month"); ?> </label>&nbsp;
					<label><input type="radio" id="over_month" value="over_month" name="dates"> <?= lang("per_year"); ?> </label>&nbsp;
					<button class="btn btn-default" id="build_sites"><?= lang("to_build"); ?></button>
				<form>
			<br><br>
		</div>
	</div>
</div>
<script src="<?=base_url();?>media/js/jquery.js"></script>  	 
<script src="<?=base_url();?>media/css/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=base_url();?>media/js/main.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script src="<?=base_url();?>media/js/site_statistic.js"></script>