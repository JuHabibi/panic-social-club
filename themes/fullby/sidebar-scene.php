
	<?php
		$post_type = get_post_type( $post->ID );
		if($post_type == 'scene' ){
	
	?>
		<div class="tab-spacer">

			<!-- Nav tabs -->
			<ul class="nav nav-tabs" id="myTab">
			
				<li class="active"><a href="#home" data-toggle="tab"> <i class="fa fa-male"></i> Figurants</a></li>
				<li><a href="#film" data-toggle="tab"> <i class="fa fa-film"></i> Film</a></li>
				<li><a href="#scenes" data-toggle="tab"> <i class="fa fa-film"></i> Scènes</a></li>
				
			</ul>
				
			<!-- Tab panes -->
			<div class="tab-content">
				
				<div class="tab-pane fade in active" id="home">
					<?php getActorsByScene($post->ID); ?>
				</div>
				
				<div class="tab-pane fade" id="film">			  	
					<?php getFilmByScene($post->ID); ?>
				</div>
				
				<div class="tab-pane fade" id="scenes">			  	
					<?php getOtherScenesByScene($post->ID); ?>
				</div>
				
			</div>
		
		</div>
	
	<?php }else if($post_type == 'film' ){ ?>
	
		<div class="tab-content">
		
			<h3>Les sc&egrave;nes de rigolage de le film</h3>
				
			<?php getScenesTitleByFilm($post->ID,3); ?>
					
		</div>
	
	<?php } ?>
	
	<?php //if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Primary Sidebar') ) : ?>
	
	<?php //endif; ?>		