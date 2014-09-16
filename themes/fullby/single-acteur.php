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
					
					<h3 class="sing-tit"><?php the_title(); ?></h3>
		
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
						
						<h3>Casier judiciaire</h3>	
					
						<?php 
						
							if(get_post_type( $post->ID ) == 'acteur'){
								$table = getScenesByActor($post->ID);	
								$tags = "acteurtags";
							}else{
								$table = getFilmsByReal($post->ID);
								$tags = "realtags";
							}
									
						?>

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

					if(get_field('naissance')){
						echo '<h3>Date de naissance : </h3><p>' . get_field('naissance') . '</p>';
					}
					if(get_field('mort')){
						echo '<h3>D&eacute;c&egrave;s : </h3><p>' . get_field('mort') . '</p>';
					}
					if(get_field('on_laime_pour')){
						echo '<h3>On l\'aime pour : </h3><p>' . get_field('on_laime_pour') . '</p>';
					}
					if(get_field('on_le_deteste_pour')){
						echo '<h3>On le d&eacute;teste pour : </h3><p>' . get_field('on_le_deteste_pour') . '</p>';
					}
					if(get_field('film_culte')){
						echo '<h3>Film culte : </h3><p>' . get_field('film_culte') . '</p>';
					}
					if(get_field('phrase_culte')){
						echo '<h3>Phrase culte :</h3><p>' . get_field('phrase_culte') . '</p>';
					}
					
					echo get_the_term_list( get_the_ID(), $tags, '<p><span class="tag-post"> <i class="fa fa-tag"></i> ' ,' , ', '</small></p><hr />'); 

					edit_post_link('<i class="fa fa-pencil-square-o"></i> Editer', ' ', ''); 
					
				?>

				<?php get_sidebar( 'secondary' ); ?>	
										
		    </div>
		   
		 </div>

	</div>			

	<div class="col-md-3 sidebar">

		<?php get_sidebar( 'primary' ); ?>	
			    
	</div>

<?php get_footer(); ?>