<?php get_header(); ?>		

		<div class="col-md-9">
		
		<div class="">
					
			<?php if (have_posts()) {

				?> 


			
						<header class="page-header">
							<h2 ><?php printf( __( 'Bonne pioche Fdp : %s', 'upbootwp' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
						</header><!-- .page-header -->
			
						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) { the_post(); ?>
			
							<?php get_template_part( 'content', 'search' ); ?>
			
						<?php } ?>
			
						<?php ///upbootwp_content_nav( 'nav-below' ); ?>
			
				
			
			<?php } else { ?>
			
		<?php get_template_part( 'no-results', 'search' ); 
		
				}?>

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

		<?php get_sidebar( 'primary' ); ?>		
		    
	</div>
	
<?php get_footer(); ?>	