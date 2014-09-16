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
					<?php echo get_the_term_list( get_the_ID(), 'categorie', '<p class="cat">', ', ', '</p>' ); ?>
					
					<h3 class="sing-tit"><?php echo getSceneTitle($post->ID, 'long', true); ?></h3>
	
				
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

						<?php echo get_the_term_list( get_the_ID(), 'scenetags', '<p><span class="tag"> <i class="fa fa-tag"></i> ' ,' , ', '</small></p>');  ?>
						
						
						<hr /> 
						
						<div id="comments">
						        
							<?php comments_template(); ?>
						
						</div> 

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
					
					if(get_field('la_replique_qui_tue')){
						echo '<h3>La r&eacute;plique qui tue : </h3><p>"' . get_field('la_replique_qui_tue') . '"</p>';
						echo '<hr>';
					}
																
					if(get_field('on_aime')){
						echo '<h3>On aime pour : </h3><p>' . get_field('on_aime') . '</p>';
						echo '<hr>';
					}	
							
						echo get_the_term_list( get_the_ID(), 'categorie', '<h3>Type de crevaison : </h3><p>', ', ', '</p><hr>' ); 
						echo get_the_term_list( get_the_ID(), 'scenetags', '<p><span class="tag-post"> <i class="fa fa-tag"></i> ' ,' , ', '</small></p><hr>'); 

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