		<div id="filter_part">
			<form>
				<input type="text" name="keywords" id="keywords" class="form-control" placeholder="<?= lang("keywords"); ?>">
				
				<hr class="project_border">
				
				<div>
					<span class="filter_title"><?= lang("birji"); ?></span>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="odesk" name="sites[]" checked="checked">
						<img src="<?= base_url("media/img/odesk.ico"); ?>" alt="Лого"> Odesk
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="Elance" name="sites[]" checked="checked">
						<img src="<?= base_url("media/img/elance.ico"); ?>" alt="Лого"> Elance
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="Guru" name="sites[]" checked="checked">
						<img src="<?= base_url("media/img/guru.ico"); ?>" alt="Лого"> Guru
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="Freelance" name="sites[]" checked="checked">
						<img src="<?= base_url("media/img/freelance.ico"); ?>" alt="Лого"> Freelance
					  </label>
					</div>
				</div>
				
				<hr class="project_border">
				
				<div>
					<span class="filter_title"><?= lang("skills"); ?></span>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="anye" name="requirements[]" checked="checked">
						 <?= lang("anye"); ?>
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="php,codeigniter,yii,zend,symfony" name="requirements[]">
						 PHP
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="ruby,ruby on rails" name="requirements[]">
						 Ruby
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="python,django" name="requirements[]">
						 Python
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="javascript,jquery" name="requirements[]">
						 Javascript/jQuery
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="html,css" name="requirements[]">
						 HTML/CSS
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="database,oracle,foxpro,postgre,mongodb,sql,mariadb" name="requirements[]">
						 Databases
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="Android,iOS,iPhone,objective c" name="requirements[]">
						 Mobile Development
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="java" name="requirements[]">
						 Java
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="c#,.net" name="requirements[]">
						 C#/.NET
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="Wordpress,Joomla,Drupal,CMS" name="requirements[]">
						 CMS
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="flash,actionscript" name="requirements[]">
						 ActionScript/Flash
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="c,c++" name="requirements[]">
						 C/C++
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="VB Script,VBA, VB.NET" name="requirements[]">
						 VB Script/VBA/VB.NET
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="seo,marketing" name="requirements[]">
						 SEO
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="linux,apache,xampp,administration" name="requirements[]">
						 Administration
					  </label>
					</div>
					
				</div>
				
				<hr class="project_border">
				
				<div>
					<span class="filter_title"><?= lang("budget"); ?></span><br>
					<div class="radio">
					  <label>
						<input type="radio" id="fixed_budget" value="fixed_budget" name="budget">
							<?= lang("fixed_rate"); ?>
					  </label>
					</div>
					<div class="radio">
					  <label>
						<input type="radio" id="hourly_rate_budget" value="hourly_rate_budget" name="budget">
							<?= lang("hourly_rate"); ?>
					  </label>
					</div>
					<div class="radio">
					  <label>
						<input type="radio" id="any_budget" value="any_budget" name="budget" checked="checked">
							<?= lang("any"); ?>
					  </label>
					</div>
				</div>
				
				<hr class="project_border">
				
				<div>
					<span class="filter_title"><?= lang("date"); ?></span><br>
					<div class="radio">
					  <label>
						<input type="radio" id="per_day" value="per_day" name="dates" checked="checked">
							<?= lang("per_day"); ?>
					  </label>
					</div>
					<div class="radio">
					  <label>
						<input type="radio" id="per_week" value="per_week" name="dates">
							<?= lang("per_week"); ?>
					  </label>
					</div>
					<div class="radio">
					  <label>
						<input type="radio" id="per_month" value="per_month" name="dates">
							<?= lang("per_month"); ?>
					  </label>
					</div>
					<div class="radio">
					  <label>
						<input type="radio" id="over_month" value="over_month" name="dates">
							<?= lang("over_month"); ?>
					  </label>
					</div>
				</div>
				
				<hr class="project_border">
				
				<div id="result_filter_agree" style="text-align:center;"></div>
				<button type="button" id="filter_agree" name="filter_agree" class="btn btn-default"><?= lang("filter_agree"); ?></button>
				
				<hr class="project_border">
				
				<input type="text" id="filter_name" name="filter_name" class="form-control" placeholder="<?= lang("filter_name"); ?>">
				
				<div id="result_filter_save" style="text-align:center; margin-top:15px;"></div>
				<button type="button" id="save_filter" name="save_filter" class="btn btn-default" style="margin-top:15px;"><?= lang("save_filter"); ?></button>
			</form>
				<hr class="project_border">
				
				<div id="saved_filters">
				<span class="filter_title"><?= lang("filter_list"); ?></span><br><br>
				<table class="filter_list_item">
					<?php foreach($selected_filters as $key): ?>
						<tr>
							<td>
								<a href="/user/projects/go_filter/<?= $key['id'];?>" class="go_filter" title="<?= lang("agree"); ?>"><?= $key['name']; ?></a>
							</td>
							<td>
								<form style="display:inline;">
									<input type="hidden" name="filter_id" value="<?=$key['id'];?>">&nbsp;
									<button class="del_filter" title="<?= lang("delete"); ?>"><i class="glyphicon glyphicon-trash"></i></button>
								</form>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
				</div>
		</div>