<?php echo $template['partials']['menu_top']; ?>
<?php echo $template['partials']['menu_left']; ?>

	<div id="pr_container">
		<div class="fav_projects">	
			<!-- Project -->
			<?php
					foreach($selected_data as $rows):
						foreach($rows as $row): 
			?>
				<div class="project_post" style="background:rgba(0,255,0,0.2);">
				
					<div class="project_title">	
						<?php 
							  switch($row['type']){
								case 'odesk':    $ico = "odesk.ico";     break;
								case 'guru':     $ico = "guru.ico";      break;
								case 'elanc':    $ico = "elance.ico";    break;
								case 'freel':    $ico = "freelance.ico"; break;
							  }
						?>
						<img src="<?= base_url("media/img/{$ico}"); ?>" alt="Лого">
						
						<a href="<?= $row['url']; ?>" target="_blank" class="job_title">
						  <b><?= $row['title']; ?></b>
						</a>
					</div>
					
					<div class="project_description">
						<span class="job_price"> <b><?= lang("price"); ?> </b> <?=  str_replace(array("\\n","\\"),"",$row['price']); ?> </span> 
						<span class="job_time">  <b><?= lang("time_arrive");?> </b> <?= date(" d M", strtotime($row['date']))." | ".substr($row['time'], 0, 5); ?> </span>
						
						<p id="job_description" class="content hideContent">
							<pre><?=  substr(str_replace(array("\\n","\\"),"",$row['description']), 0, 200)."..."; ?></pre>
						</p>
					
						<span class="job_category"><b><?= lang("category");?> </b> <i><?=  str_replace(array("\\n","\\"),"",$row['category']); ?></i></span><br>
						<span class="job_requirements"><b><?= lang("requirements");?> </b> <i><?=  str_replace(array("\\n","\\"),"",$row['requirements']); ?></i></span>
						<div class="save_project">
							<form style="display:inline;">
								<input type="hidden" name="project_id" value="<?= $row['id']; ?>"></hidden>
								<input type="hidden" name="project_site" value="<?= $row['type']; ?>"></hidden>
								<button style="color:red;" title="<?= lang('delete_project'); ?>" class="delete_project_link"><i  class="glyphicon glyphicon-trash"></i></button>
							</form>
						</div>
					</div>
				</div>		
				<hr class="project_border">
			
			<?php 
				endforeach; 
				endforeach;
			?>	
			<!-- End Project -->
			
			<br><br>
			<?php echo $pagination; ?>
		</div>
	</div>
</div>
<script src="<?=base_url();?>media/js/jquery.js"></script>  	 
<script src="<?=base_url();?>media/css/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=base_url();?>media/js/main.js"></script>