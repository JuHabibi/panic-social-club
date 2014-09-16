<?php get_header(); ?>			
		
	<div class="col-md-12 catalogue">
	
		<div class="col-md-12 single-in">
		
			<?php if (have_posts()) :?><?php while(have_posts()) : the_post(); ?> 

				<?php if ( has_post_thumbnail() ) { ?>

                    <?php the_post_thumbnail('single', array('class' => 'sing-cop')); ?>

                <?php } else { ?>
                
                	<div class="row spacer-sing"></div>	
                
                 <?php }  ?>
				
				<div class="sing-tit-cont">
					
					<h3 class="sing-tit"><?php the_title(); ?></h3>
				
				</div>

				<div class="sing-cont">
					
					<div class="sing-spacer">
					
						<?php
							
								$args = array(
									'taxonomy' => 'categorie',
									'hierarchical' => 0
								);
								$categorie = get_categories($args);
								
								$args2 = array(
									'taxonomy' => 'genre',
									'hierarchical' => 0
								);
								$genre = get_categories($args2);

								$args3 = array(
								'post_type'=> 'film',
								'orderby' => 'title',
								'order' => 'ASC',
								'post_staus'=> 'publish',
								);
								
								$args4 = array(
								'post_type'=> 'acteur',
								'orderby' => 'title',
								'order' => 'ASC',
								'post_staus'=> 'publish',
								);

							?>
						
							<div class="col-md-3">
								<h2>Crevaisons</h2>
								<ul class="list-unstyled ">
									<?php 
										foreach ($categorie as $theme){
											echo '<li><a href="/categorie/'.$theme->slug.'" class="" data-type="humeur" data-term-id="'.$theme->term_id.'" title="'.$theme->name.'"><span class="pull-left">'.$theme->name.'</span><span class="label label-default pull-right">'.$theme->count.'</span></a></li>';
										}	
										?>
								</ul>
							</div>
								
							<div class="col-md-3">
								<h2>Acteurs</h2>
								<ul class="list-unstyled ">
									<?php query_posts($args4); 		
										if ( have_posts() ) {
											while ( have_posts() ) { the_post(); ?>
												<li><a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><span class="pull-left"><?php the_title();?></span><span class="label label-default pull-right"><?php getScenesCountByActor($post->ID);  ?></span></a></li>
											<?php } ?>
										<?php } ?>
									<?php wp_reset_query(); ?>	
								</ul>
							</div>
								
							<div class="col-md-3">
								<h2>Films</h2>
								<ul class="list-unstyled ">
									<?php query_posts($args3); 		
										if ( have_posts() ) {
											while ( have_posts() ) { the_post(); ?>
												<li><a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><span class="pull-left"><?php the_title();?></span><span class="label label-default pull-right"><?php getScenesCountByFilm($post->ID);  ?></span></a></li>
											<?php } ?>
										<?php } ?>
									<?php wp_reset_query(); ?>	
								</ul>
							</div>

							<div class="col-md-3">
								<h2>Genres</h2>
								<ul class="list-unstyled ">
									<?php 
										foreach ($genre as $theme){
											echo '<li><a href="/genre/'.$theme->slug.'" class="" data-type="humeur" data-term-id="'.$theme->term_id.'" title="'.$theme->name.'"><span class="pull-left">'.$theme->name.'</span><span class="label label-default pull-right">'.$theme->count.'</span></a></li>';
										}	
										?>
								</ul>
							</div>
						
						
							<h2>Sac &agrave; vomis</h2>
		
							<?php 
								$args = array(
									'taxonomy'  => array('filmtags','scenetags','acteurtags'), 
								); 
								wp_tag_cloud($args);
							?>
						
						

					</div>

				</div>	
				 					
			<?php endwhile; ?>
	        <?php else : ?>

	                <p>Sorry, no posts matched your criteria.</p>
	         
	        <?php endif; ?> 
	        
		</div>	
		 
		

	</div>			

<?php get_footer(); ?>