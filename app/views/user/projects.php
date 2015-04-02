<?php echo $template['partials']['menu_top']; ?>
<?php echo $template['partials']['menu_left']; ?>

	<div id="pr_container">
		<?php echo $template['partials']['filters']; ?>
		<div id="projects">
			<!-- Per Page Menu -->
			<ul class="nav nav-pills" id="per_page">
				<li> <a href="#"><?= lang("per_page"); ?></a></li>
				<li <?php if($per_page == "10")  echo "class='active'"; ?>><a href="/user/projects/set_per_page/10">10</a></li>
				<li <?php if($per_page == "25")  echo "class='active'"; ?>><a href="/user/projects/set_per_page/25">25</a></li>
				<li <?php if($per_page == "50")  echo "class='active'"; ?>><a href="/user/projects/set_per_page/50">50</a></li>
				<li <?php if($per_page == "75")  echo "class='active'"; ?>><a href="/user/projects/set_per_page/75">75</a></li>
				<li <?php if($per_page == "100") echo "class='active'"; ?>><a href="/user/projects/set_per_page/100">100</a></li>
			</ul><br>
			
			<hr class="project_border">
			
			<!-- Project -->
			<?php $i = 0; foreach($selected_data as $row): $i++; ?>
				<div class="project_post"  <?php if($row->is_favorite) echo "style='background:rgba(0,255,0,0.2);'"; ?> >
				
					<div class="project_title">	
						<?php 
							  switch($row->type){
								case 'odesk':    $ico = "odesk.ico";     break;
								case 'guru':     $ico = "guru.ico";      break;
								case 'elanc':    $ico = "elance.ico";    break;
								case 'freel':    $ico = "freelance.ico"; break;
							  }
						?>
						<img src="<?= base_url("media/img/{$ico}"); ?>" alt="Лого">
						
						<a href="<?= $row->url; ?>" target="_blank" class="job_title">
						  <b><?= $row->title; ?></b>
						</a>
					</div>
					
					<div class="project_description">
						<span class="job_price"> <b><?= lang("price"); ?> </b> <?=  str_replace(array("\\n","\\"),"",$row->price); ?> </span> 
						<span class="job_time">  <b><?= lang("time_arrive");?> </b> <?= date(" d M", strtotime($row->date))." | ".substr($row->time, 0, 5); ?> </span>
						
						<p id="job_description" class="content hideContent">
							<pre><?=  substr(str_replace(array("\\n","\\"),"",$row->description), 0, 200)."..."; ?></pre>
						</p>
					
						<span class="job_category"><b><?= lang("category");?> </b> <i><?=  str_replace(array("\\n","\\"),"",$row->category); ?></i></span><br>
						<span class="job_requirements"><b><?= lang("requirements");?> </b> <i><?=  str_replace(array("\\n","\\"),"",$row->requirements); ?></i></span>

						<div class="save_project">
							<?php if(!$row->is_favorite): ?>
							<form style="display:inline;">
								<input type="hidden" name="project_id" value="<?= $row->id; ?>"></hidden>
								<input type="hidden" name="project_site" value="<?= $row->type; ?>"></hidden>
								<button title="<?= lang('save_project'); ?>" class="save_project_link"><i class="glyphicon glyphicon-floppy-save"></i></button>
							</form>
							<?php else: ?>
								<a class="glyphicon glyphicon-saved" id="project_saved_link" title="<?= lang('added'); ?>"></a>
							<?php endif; ?>
						</div>
					</div>
				</div>	
					
				<hr class="project_border">

			<?php endforeach; ?>	
			<!-- End Project -->
			
			<br><br>
			<?php echo $pagination; ?>
		</div>
	</div>
</div>
<script src="<?=base_url();?>media/js/jquery.js"></script>  	 
<script src="<?=base_url();?>media/css/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=base_url();?>media/js/main.js"></script> 