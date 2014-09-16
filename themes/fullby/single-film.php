<?php get_header(); ?>			
		
	<div class="col-md-9 single">
	
		<div class="col-md-9 single-in">
		
			<?php if (have_posts()) :?><?php while(have_posts()) : the_post(); ?>
			
				<?php $video = get_post_meta($post->ID, 'video', true );
				  
				if($video != '') {?>
	
					<div class="videoWrapper">
					
					 	<div class='video-container'><iframe title='YouTube video player' width='400' height='275' src='http://www.youtube.com/embed/<?php echo $video; ?>' frameborder='0' allowfullscreen></iframe></div>
					
					</div>

				<?php 				                 
           
             	} else if ( has_post_thumbnail() ) { ?>

                    <?php the_post_thumbnail('single', array('class' => 'sing-cop')); ?>

                <?php } else { ?>
                
                	<div class="row spacer-sing"> </div>	
                
                <?php }  ?>
				
				
				<div class="sing-tit-cont">
					
					<!--<p class="cat"> <?php the_category(','); ?></p> -->
					<?php echo get_the_term_list( get_the_ID(), 'genre', '<p class="cat">', ', ', '</p>' ); ?>
					
					<h3 class="sing-tit"><?php the_title(); ?></h3>
	
				
					<p class="meta">
					
						<i class="fa fa-clock-o"></i> <?php the_time('j M , Y') ?> 

						<?php if(function_exists('the_views')) { echo '&nbsp;|&nbsp; '; the_views(); echo '&nbsp;vues&nbsp;|&nbsp;'; } ?>
						
						<?php 
						$video = get_post_meta($post->ID, 'video', true );
						
						if($video != '') { ?>
		             			
		             		<i class="fa fa-video-camera"></i> Video
		             			
		             	<?php } else if (strpos($post->post_content,'[gallery') !== false) { ?>
		             			
		             		<i class="fa fa-th"></i> Gallery

	             		<?php } else {?>

	             		<?php } ?>
	             		
					</p>
					
				</div>

				<div class="sing-cont">
					
					<div class="sing-spacer">
					
						<?php the_content('Leggi...');?>
						
						<?php wp_link_pages('pagelink=Page %'); ?>

						<p>
							<?php $post_tags = wp_get_post_tags($post->ID); if(!empty($post_tags)) {?>
								<span class="tag"> <i class="fa fa-tag"></i> <?php the_tags('', ', ', ''); ?> </span>
							<?php } ?>
						</p>

						<hr /> 
						<!--
						<div id="comments">
						        
							<?php //comments_template(); ?>
						
						</div> 
						-->
					</div>

				</div>
				 					
			<?php endwhile; ?>
	        <?php else : ?>

	                <p>Sorry, no posts matched your criteria.</p>
	         
	        <?php endif; ?> 
	        
		</div>	
		 
		<div class="col-md-3">
			
			<div class="sec-sidebar">
		
				<?php 
			
					echo '<h3>R&eacute;alisateur : </h3><p>' .getRealisatorByFilm($post->ID);
					if(get_field('annee')){echo get_field('annee'); }
					echo '</p>';  
					
					if(get_field('annee')){echo '<h3>Ann&eacute;e : </h3><p>' . get_field('annee') . '</p>';	}

					echo get_the_term_list( $post->ID, 'genre', '<h3>Genre : </h3><p>', ', ', '</p></p>' ); 
					
					if(get_field('on_aime_pour')){
						echo '<h3>On aime pour : </h3><p>' . get_field('on_aime_pour') . '</p>';
					}
					
					if(get_field('on_deteste_pour')){
						echo '<h3>On d&eacute;teste pour : </h3><p>' . get_field('on_deteste_pour') . '</p>';
					}
					
					edit_post_link('<i class="fa fa-pencil-square-o"></i> Editer', ' ', ''); 
					
				?>

				<?php get_sidebar( 'secondary' ); ?>	
										
		    </div>
		   
		 </div>

	</div>			

	<div class="col-md-3 sidebar">

		<?php get_sidebar( 'scene' ); ?>	
			    
	</div>

<?php get_footer(); ?>