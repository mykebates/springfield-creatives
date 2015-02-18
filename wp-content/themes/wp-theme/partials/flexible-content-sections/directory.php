<section class="main">
	<div class="middlifier">
		<?php
		$title = get_sub_field('section_title');
		if(!empty($title))
			echo "<h2>$title</h2>";

		$intro = get_sub_field('section_intro_text');
		if(!empty($intro))
			echo "<p>$intro</p>";

		$dir_type = get_sub_field('directory_type');

		if($dir_type == 'members'){
			$render_func = 'render_person_item';
			$post_obj_name = 'directory_user_object';
			$view_all = 'members/list';
		}else{
			$render_func = 'render_directory_item';
			$post_obj_name = 'directory_post_object';
			$view_all = $dir_type;
		}

		$dir_data = get_sub_field('directory_data');

		echo '<ul class="directory ' . $dir_type . ' column-2">';

		// load posts/authors based on data selection method
		switch($dir_data){
			case 'specific':

				// name of ACF field containing the specified posts to load
				$list_name = 'selected_' . $dir_type;

				if( have_rows($list_name) ):

					while( have_rows($list_name) ): the_row(); 

						// vars
						$dir_post_data = get_sub_field($post_obj_name);
						
						$title = get_sub_field('subtitle');

						call_user_func($render_func, $dir_post_data, $title);

					endwhile;

				endif;
			break;

			case 'dynamic':

					$num_to_show = get_sub_field('number_of_listing_items');
					$orderby = get_sub_field('orderby');
					$order = get_sub_field('order');

					// query recent posts/users/whatever
					switch($dir_type) {
						case 'members':

							switch($orderby) {
								case 'date':
									$orderby = 'registered';
									break;

								case 'name':
									$orderby = 'display_name';
									break;
							}

							$args = array(
							    'role' => 'Member',
							    'orderby' => $orderby,
							    "order" => $order,
							    "number" => $num_to_show
							);
							$members = new WP_User_Query($args);

              foreach ($members->results as $member) {
                  render_person_item($member);
              }

							break;

						default:

							$dir_posts = new WP_Query(array(
								"post_type" => $dir_type,
						    'orderby' => $orderby,
						    "order" => $order,
								"posts_per_page" => $num_to_show
							));

							while( $dir_posts->have_posts() ) {
								$dir_posts->the_post();

								// render_post_list_item will use global $post by default					
								call_user_func($render_func);
              }

							wp_reset_postdata();

					}

			break;
		}

		echo '</ul>';

		if(get_sub_field('view_all_link'))
			echo '<a href="' . get_sub_field('view_all_link_url') . '" class="read-more secondary-button">' . get_sub_field('view_all_link_label') . '</a>';

		?>
	</div>
</section>