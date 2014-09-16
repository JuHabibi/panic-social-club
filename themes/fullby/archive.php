<?php get_header(); ?>		

		<div class="col-md-9 cont-grid">
		<?php $postType =  get_post_type( $post ) ?>
		<?php 
		
			if ( $postType =='film' || $postType =='scene' ) {
				movieGlossary(); 
			}
		
			$taxonomy = get_queried_object(); 
		?>

		<div class="grid">
					
			<?php if (have_posts()) :?><?php while(have_posts()) : the_post(); ?> 

				<div class="item">
				
					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					
						
						<?php 
						
						//$taxonomy->taxonomy
						if($taxonomy->name == 'film' || $taxonomy->taxonomy == 'glossary'){
							echo get_the_term_list( get_the_ID(), 'genre', '<p class="grid-cat">', ', ', '</p>' ); 
						}else if($taxonomy->name == 'scene' ){
							echo get_the_term_list( get_the_ID(), 'categorie', '<p class="grid-cat">', ', ', '</p>' );
						}
						
						?>
						
						
						<h2 class="grid-tit"><a href="<?php the_permalink(); ?>">
						<?php 
						
						if($postType =='scene'){
							echo getSceneTitle($post->ID);
						}else{
							the_title(); 
						}
						
						
						
						?></a></h2>
						
												
							<?php 
							$video = get_post_meta($post->ID, 'fullby_video', true );
							
							if($video != '') { ?>
						 			
						 		<i class="fa fa-video-camera"></i> Video
						 			
						 	<?php } else if (strpos($post->post_content,'[gallery') !== false) { ?>
						 			
						 		<i class="fa fa-th"></i> Gallery
						
								<?php } else {?>
						
								<?php } ?>
								
						</p>
						 
						<?php $video = get_post_meta($post->ID, 'fullby_video', true );
						
						if($video != '') {?>
						
						
					    	<a href="<?php the_permalink(); ?>" class="link-video">
								<img src="http://img.youtube.com/vi/<?php echo $video ?>/hqdefault.jpg" class="grid-cop"/>
								<i class="fa fa-play-circle fa-4x"></i> 
							</a>
						
						<?php 				                 
						
							} else if ( has_post_thumbnail() ) { ?>
						
						   <a href="<?php the_permalink(); ?>">
						        <?php the_post_thumbnail('medium', array('class' => 'grid-cop')); ?>
						   </a>
						
						<?php } ?>
						
						<div class="grid-text">
						
							<?php the_content('More...');?>
							
						</div>
						
						<?php echo get_the_term_list( get_the_ID(), 'filmtags', '<p><span class="tag-post"> <i class="fa fa-tag"></i> ' ,' , ', '</span></p>'); ?>
						
					</div>
					
				</div>	

			<?php endwhile; ?>
	        <?php else : ?>

	                <p>Sorry, no posts matched your criteria.</p>

	        <?php endif; ?> 

		</div>	

		<div class="pagination">
		
			<?php
			global $wp_query;
			
			$big = 999999999; // need an unlikely integer
			
			echo paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, get_query_var('paged') ),
				'total' => $wp_query->max_num_pages
			) );
			?>
			
		</div>
			
	</div>
	
	<div class="col-md-3 sidebar">
		
		<?php 
		
		if($taxonomy->name !='film' && $taxonomy->name !='scene' && $taxonomy->name !='glossary' ){
		?>
		
		<h2><span><?php echo  $taxonomy->name; ?></span></h2>	
		<hr />
		<?php echo  $taxonomy->description; ?>
		<hr />
		
		<?php } ?>
		
		<?php get_sidebar( 'primary' ); ?>		
		    
	</div>
	
<?php get_footer(); ?>	