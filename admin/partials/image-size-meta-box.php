<div id="single-post-meta-manager">

	<?php
		$params = get_post_meta(get_the_id(), '_mis_params', true);
		// Make sure we are working with an array
		$params = (is_string($params)) ? array() : $params;

	 ?>

	<input type="hidden" name="mis_rules[]" id="mis-rules" value="">

	<div id="mis-pickers">

		<div class="dimensions">
			<label for="mis_params[dimensions][]">Width</label>
			<input type="text" name="mis_params[dimensions][]">
			<label for="mis_params[dimensions][]">Height</label>
			<input type="text" name="mis_params[dimensions][]">
		</div>

		<div class="post-types selection-box" data-paramtype="post_type">
			<div class="head">
				<h4>Post Types</h4>
				<h4>Selected</h4>
			</div>
			<div class="all">
				<div class="search-wrap">
					<input type="search" placeholder="search...">
				</div>
				<?php $post_types = get_post_types(); ?>
				<?php foreach($post_types as $type): ?>
					<div class="not-selected param <?php if(array_key_exists('post_type', $params) && in_array($type, $params['post_type'])){echo "inactive";}?>" data-slug="<?php echo $type; ?>">
						<?php echo $type; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="user-selected">
				<?php if(array_key_exists('post_type', $params)): ?>
					<?php $sel_post_types = $params['post_type']; ?>
					<?php foreach($sel_post_types as $post_type): ?>
						<div class="param selected-param" data-slug="<?php echo $post_type; ?>">
							<?php echo $post_type; ?>
							<input type="hidden" value="<?php echo $post_type; ?>" name="mis_params[post_type][]">
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>

		<div class="post-types selection-box" data-paramtype="posts">
			<div class="head">
				<h4>All Posts</h4>
				<h4>Selected</h4>
			</div>
			<div class="all">
				<div class="search-wrap">
					<input type="search" placeholder="search...">
				</div>
				<?php
				$all_post_types = get_post_types();
				$args = array(
					'posts_per_page'   => -1,
					'orderby'          => 'post_titl',
					'order'            => 'DESC',
					'exclude'          => '',
					'post_type'        => $all_post_types,
					'post_status'      => 'publish',
					'suppress_filters' => true );

				$all_posts = get_posts($args);

				?>

				<?php foreach ($all_posts as $p): ?>
					<div class="not-selected param " data-slug="<?php echo $p->post_name;?>">
					<?php echo $p->post_title; ?>
					</div>
				<?php endforeach; ?>


			</div>
			<div class="user-selected">
				<?php if(array_key_exists('posts', $params)): ?>
					<?php $sel_posts = $params['posts'];?>
					<?php foreach($sel_posts as $sel_post): ?>
						<div class="param selected-param" data-slug="<?php echo $sel_post; ?>">
							<?php echo $sel_post; ?>
							<input type="hidden" value="<?php echo $sel_post; ?>" name="mis_params[posts][]">
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>

		<div class="page_templates selection-box" data-paramtype="page_template">
			<h3>Page Templates</h3>
			<div class="all">
				<div class="search-wrap">
					<input type="search" placeholder="search...">
				</div>
				<?php $templates = get_page_templates(); ?>
				<?php foreach($templates as $template): ?>
					<div class="not-selected param" data-slug="<?php echo $template; ?>">
						<?php echo $template; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="user-selected">
				<?php if(array_key_exists('page_template', $params)): ?>
					<?php $sel_templates = $params['page_template'];?>
					<?php foreach($sel_templates as $template): ?>
						<div class="param selected-param" data-slug="<?php echo $template; ?>">
							<?php echo $template; ?>
							<input type="hidden" value="<?php echo $template; ?>" name="mis_params[page_template][]">
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>

		<div class="custom_fields selection-box" data-paramtype="custom_fields">
			<h3>Custom Fields</h3>
			<div class="all">
			<input type="search">
				<?php $custom_fields = mis_get_all_keys();  ?>
				<?php foreach($custom_fields as $field): ?>
					<div class="param not-selected" data-slug="<?php echo $field->meta_key; ?>">
						<?php echo $field->meta_key; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="user-selected">
				<?php if(array_key_exists('custom_fields', $params)): ?>
					<?php $sel_fields = $params['custom_fields'];?>
					<?php foreach($sel_fields as $sel_field): ?>
						<div class="param selected-param" data-slug="<?php echo $sel_field; ?>">
							<?php echo $sel_field; ?>
							<input type="hidden" value="<?php echo $sel_field; ?>" name="mis_params[custom_fields][]">
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>

	</div>

</div><!-- #single-post-meta-manager -->