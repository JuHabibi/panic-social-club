<?php
/*
Plugin Name: Ska film
Description: Le plugin indispensable pour g&eacute;rer du Viandox avec ta maman.
Version: 0.4
License: GPL
Author: Ska
Author URI: www.skapiso.com
*/

include('sm.php');

/***** TAXONOMY DEFINITION *****/

add_action('init', 'my_custom_init');
function my_custom_init(){
    register_post_type('film', array(
      'label' => __('Films'),
      'singular_label' => __('Film'),
      'public' => true,
      'show_ui' => true,
      'capability_type' => 'post',
      'hierarchical' => false,
	  'has_archive' => 'films',
	  'query_var' => true,
	  'rewrite' => array('slug' => 'film' ,'with_front' => FALSE), 
	  'show_in_nav_menus' => true,
      'supports' => array('title', 'editor', 'author', 'thumbnail','excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes')
    ));
    
    register_taxonomy( 'genre', 'film', array( 'hierarchical' => TRUE, 'label' => 'Genre', 'query_var' => true, 'rewrite' =>  array('slug' => 'genre') ) );  
    register_taxonomy( 'filmtags', 'film', array( 'hierarchical' => FALSE, 'label' => 'Tags', 'query_var' => true, 'rewrite' => array('slug' => 'tag-film') ) );  
    register_taxonomy( 'glossary', 'film', array( 'hierarchical' => FALSE, 'label' => 'Glossary', 'query_var' => true, 'rewrite' => array('slug' => 'films')   ));  
    
    register_post_type('scene', array(
      'label' => __('Scenes'),
      'singular_label' => __('Scene'),
      'public' => true,
      'show_ui' => true,
      'capability_type' => 'post',
      'hierarchical' => false,
	  'has_archive' => 'scenes',
	  'query_var' => true,
	  'show_in_nav_menus' => true,
     // 'rewrite' => array('slug' => '%movieslugtags%/scene'), 
      'rewrite' => array('slug' => 'asas/scene'), 
     // 'rewrite' => true,
	 'taxonomies' => array( 'movieslugtags' ),
      'supports' => array('title', 'editor', 'author', 'thumbnail','excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes')
    ));
    
    register_taxonomy( 'categorie', 'scene', array( 'hierarchical' => TRUE, 'label' => 'Type de mort', 'query_var' => true, 'rewrite' => array('slug' => 'categorie') ) );  
    register_taxonomy( 'scenetags', 'scene', array( 'hierarchical' => FALSE, 'label' => 'Tags', 'query_var' => true, 'rewrite' =>  array('slug' => 'tag-scene') ) );  
    //register_taxonomy( 'movieslugtags', 'scene', array( 'hierarchical' => FALSE, 'label' => 'Movie slug', 'query_var' => true, 'rewrite' => array('slug' => '%movieslugtags%','with_front' => true)  ) );  
    register_taxonomy( 'movieslugtags', 'scene', array( 'hierarchical' => FALSE, 'label' => 'Movie slug', 'query_var' => true, 'rewrite' => true  ) );  
	
    register_post_type('acteur', array(
      'label' => __('Acteurs'),
      'singular_label' => __('Acteur'),
      'public' => true,
      'show_ui' => true,
      'capability_type' => 'post',
      'hierarchical' => false,
	  'has_archive' => true,
	  'query_var' => true,
	  'rewrite' => array('slug' => 'acteur','with_front' => FALSE),  
      'supports' => array('title', 'editor', 'author', 'thumbnail','excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes')
    ));
	
	register_taxonomy( 'acteurtags', 'acteur', array( 'hierarchical' => FALSE, 'label' => 'Tags', 'query_var' => true, 'rewrite' => array('slug' => 'tag-acteur') ) ); 
	
	register_post_type('realisateur', array(
      'label' => __('Realisateurs'),
      'singular_label' => __('Realisateur'),
      'public' => true,
      'show_ui' => true,
      'capability_type' => 'post',
      'hierarchical' => false,
	  'has_archive' => true,
	  'query_var' => true,
	  'rewrite' => array('slug' => 'realisateur','with_front' => FALSE),  
      'supports' => array('title', 'editor', 'author', 'thumbnail','excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes')
    ));
	
	register_taxonomy( 'realtags', 'realisateur', array( 'hierarchical' => FALSE, 'label' => 'Tags', 'query_var' => true, 'rewrite' => array('slug' => 'tag-realisateur') ) );  

	
	add_rewrite_tag('%movieslugtags%','([^&]+)'); 
	add_rewrite_rule('([^/]+)/scene/([^/]+)/?$','index.php?scene=$matches[2]=&movieslugtags=$matches[1]','top');
	
	//add_rewrite_tag('%glossary%','([^&]+)'); 
	//add_rewrite_rule('films/([^/]+)/?$','index.php?glossary=$matches[1]','top');

	flush_rewrite_rules();
}

/***** REWRITE RULES *****/

add_filter('post_type_link', 'scene_permalink', 10, 3);
function scene_permalink($permalink, $post_id, $leavename) {	//on redefinit les permalink en fonction du taxonomy_slug pour la reecriture d'url
	if (  ! is_admin() && '' != $permalink && !in_array($post->post_status, array('draft', 'pending', 'auto-draft')) ) {
		
		if (strpos($permalink, 'asas') === FALSE) return $permalink;
		 
		// Get post
		$post = get_post($post_id);
		
	//sm($post); die();
		if (!$post) return $permalink;
	 
		$terms = wp_get_object_terms($post->ID, 'movieslugtags');   
		if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])){
			$taxonomy_slug = $terms[0]->slug;
		}else{
			$taxonomy_slug = 'inconnu';
		}
		//$taxonomy_slug = 'tutu';
		return str_replace('asas', $taxonomy_slug, $permalink);
	} 
}

/***** P2P RELATIONS DEFINITION *****/

add_action( 'p2p_init', 'my_connection_types' );
function my_connection_types() {
 
	$connection_args = ( array(
			'name' => 'film2scene',
			'from' => 'film',
			'to' => 'scene',
			'title' => array( 'from' => 'Sc&egrave;nes pr&eacute;sentes dans ce film :', 'to' => 'Film dont est issue la sc&egrave;ne :' ),
			'admin_box' => array(
				'show' => 'any',
				'context' => 'advanced'
			),
        ) 
    );

    $connection_args2 = ( array(
            'name' => 'scene2acteur',
            'from' => 'scene',
            'to' => 'acteur',
            'title' => array( 'from' => 'Acteur dans cette sc&egrave;ne :', 'to' => 'A jou&eacute; dans les sc&egrave;nes :' ),
            'admin_box' => array(
                'show' => 'any',
                'context' => 'advanced'
            ),
            'fields' => array(                
                'fonction' => array(
                    'title' => ('Fonction'),                    
                    'values' => array(
                        'tueur' => ('tueur'),
                        'victime' => ('victime')
                    )
                ),
                'role' => array(
					'title' => ('Role')
                )
            )
        ) 
    );
	
	$connection_args3 = ( array(
			'name' => 'film2realisateur',
			'from' => 'film',
			'to' => 'realisateur',
			'title' => array( 'from' => 'R&eacute;alisateur du film :', 'to' => 'A r&eacute;alis&eacute; :' ),
			'admin_box' => array(
				'show' => 'any',
				'context' => 'advanced'
			),
        ) 
    );
	
    p2p_register_connection_type($connection_args);
    p2p_register_connection_type($connection_args2);    
    p2p_register_connection_type($connection_args3);    
}

/***** FUNCTIONS *****/

function makeThumb($post_id, $titre="long", $taille = 2){	//genere les blocs videos

	echo '<div class="col-md-'.$taille.' col-sm-'.$taille.' col-xs-12">
				<div class="thumb">
					<a href="'.get_permalink().'" title="'.get_the_title().'" >';
						echo get_the_post_thumbnail($post_id, 'thumbnail',array('class' => 'img-responsive'));
						echo '<span class="overlay"></span>
					</a>';
					if(function_exists('the_views')) {
						echo '<span class="viewCounter">
									<i class="glyphicon glyphicon-eye-open"></i> '.the_views(false).'
								</span>';
					} 
				echo '</div><div class="thumbData">	
				<h3 class="thumbTitle"><a href="'.get_permalink().'" title="'.get_the_title().'" >';
			if($titre == 'global'){
				the_title();
			}else{
				getSceneTitle($post_id, $titre, true);
			}
			echo '</a></h3>';
			
	echo '</div></div>';
}


function getScenesByActor($actor){	//affiche la liste des scenes dans lesquelles a joue l'acteur (single-acteur.php)

	global $post;
	$connected = new WP_Query( array(
		'connected_type' => 'scene2acteur',
		'connected_items' => get_queried_object(),
		'nopaging' => true,
	) );

	if ( $connected->have_posts() ) {
		
		$filmArr = array();

		while ( $connected->have_posts() ) { 
								
			$connected->the_post(); 
			$sceneLink = get_permalink();
			$sceneID = $post->ID;
			$sceneCategories = get_the_term_list( $sceneID, 'categorie', '', ', ', '' );
			$sceneArr['sceneID'] = $sceneID ;
			$sceneArr['link'] = $sceneLink ;
			$sceneArr['categories'] = $sceneCategories ;
			$sceneArr['role'] = p2p_get_meta($post->p2p_id, 'role', true ) ;
			$sceneArr['fonction'] = p2p_get_meta($post->p2p_id, 'fonction', true ) ;
			p2p_type( 'film2scene' )->each_connected( $connected, array( ), 'film' );

			foreach ( $post->film as $post ) {							  
				setup_postdata( $post );
				$filmArr['<a href="'.get_permalink().'">'.$post->post_title.'</a>'][$sceneID] = $sceneArr;
			}
		}

		wp_reset_postdata();
	}

	echo '<table class="table table-condensed ">
				<thead>
					<tr>
						<th>Film</th>
						<th>Sc&egrave;ne</th>
						<th>R&ocirc;le</th>
						<th>Victime</th>
						<th>Type de crevaison</th>
					</tr>
				</thead>
				<tbody>';		

	foreach($filmArr as $key=>$value){

		$filmName = $key;
		
		$j = count($value);
				$i = 0;		
		foreach($value as $key=>$value){
		
			echo '<tr>';
			if($i == 0){
				echo '<td rowspan='.$j.' >'.$filmName. '</td>';
			}
			echo '<td>';
			echo '<a href="'.$value['link'].'">';
			getSceneTitle( $value['sceneID'], "court", true).'</a></td>';
			echo '<td>'.$value['role'].'</td>';
			echo $value['fonction'] == 'victime' ?  '<td ><span class="glyphicon glyphicon-ok"></td>' :  '<td></td>' ;
			echo '<td>'.$value['categories'].'</td>';
			echo '</tr>';
			
			$i++;
		}
	}
	
	echo  '</tbody></table>';
}

function getScenesCountByActor($actor){	//affiche le nombre de scenes dans lesquelles a joue l'acteur (page-pages-jaunes.php)

	global $post;
	
	$connected = new WP_Query( array(
		'connected_type' => 'scene2acteur',
		'connected_items' => $actor,
		'nopaging' => true,
	) );

	$count = 0;		
	
	if ( $connected->have_posts() ) {
		
		$filmArr = array();
		while ( $connected->have_posts() ) { 
			$connected->the_post(); 
			$count++;
		}
	
		wp_reset_postdata();
		echo $count;
	}
}

function getVictimesByFilm($film){	//affiche toutes les scenes d'un film

	global $post;
	
	$connected = new WP_Query( array(
        'connected_type' => 'film2scene',
        'connected_items' => $film,
        'nopaging' => true,
    ) );

    if ( $connected->have_posts() ) {
?>
		<table class="table table-condensed">
				<thead>
					<tr>
						<th>Sc&egrave;ne</th>		
						<th>Acteur</th>
						<th>R&ocirc;le</th>
						<th>Victime</th>
						<th>Type de crevaison</th>
					</tr>
				</thead>
				<tbody>	

		<?php while ( $connected->have_posts() ) { 

			$connected->the_post(); 

           // $sceneLink =  '<a href="'. get_permalink() .'">'. $post->post_name.'</a>';
            $sceneLink =  get_permalink();
			$sceneCategories = get_the_term_list( $post->ID, 'categorie', $before, ', ', $after );

            p2p_type( 'scene2acteur' )->each_connected( $connected, array(), 'scene' );
            p2p_type( 'film2scene' )->each_connected( $post->scene, array(), 'film' );

            $actorArr = array();
            
            $i = 0;
            foreach ( $post->scene as $post ) {
                setup_postdata( $post );							
				$actorArr[$i]['actor'] = '<a href="'. get_permalink(). '">'.$post->post_title.'</a>';	
                $actorArr[$i]['role'] = p2p_get_meta($post->p2p_id, 'role', true );	
				$actorArr[$i]['fonction']  = p2p_get_meta($post->p2p_id, 'fonction', true ) ;
				
				if($actorArr[$i]['fonction'] == 'victime'){$sceneName = $actorArr[$i]['role'];}
				
				$i++;        
            }
            
            $j = 0;
            foreach ($actorArr as $actor){
                
				if($j ==0){
					echo '<tr ><td rowspan='.$i.'><a href="'.$sceneLink.'" >'. $sceneName.'</a></td>';
				}	
					
					echo '<td>'.$actor['actor'].'</td><td>'.$actor['role'].'</td>';
					echo $actor['fonction'] == 'victime' ?  '<td ><span class="glyphicon glyphicon-ok"></td>' :  '<td></td>' ;
					
				if($j ==0){	
					echo '<td rowspan='.$i.'>'.$sceneCategories.'</td></tr>';
				}else{
					echo '</tr>';
				}
                $j++; 
            }
		}
		?>
		</tbody></table>
		<?php
        wp_reset_postdata();
	}
}

function getScenesTitleByFilm($film, $taille = "2"){	//affiche toutes les scenes d'un film

	global $post;
	
	$connected = new WP_Query( array(
        'connected_type' => 'film2scene',
        'connected_items' => $film,
        'nopaging' => true,
    ) );

    // Display connected pages
	
    if ( $connected->have_posts() ) {

		while ( $connected->have_posts() ) { 

			$connected->the_post(); 
            
			//makeThumb($post->ID,"court", $taille);
	
			echo '<a href="'.get_permalink().'" title="'.get_the_title().'" >';	
			echo get_the_post_thumbnail($post_id, 'thumbnail',array('class' => 'thumbnail'));			
		    echo '<h2 class="title">';getSceneTitle($post->ID, 'court', true); echo'</h2>';	
		    echo '<div class="date"><i class="fa fa-clock-o"></i> '; the_time('j M , Y') ; echo '&nbsp;';
		    echo '</div></a>';
			/*
			
								
            $sceneLink =  '<a href="'. get_permalink() .'">';
			$sceneCategories = get_the_term_list( $post->ID, 'categorie', $before, ', ', $after );

            p2p_type( 'scene2acteur' )->each_connected( $connected, array(), 'scene' );
            p2p_type( 'film2scene' )->each_connected( $post->scene, array(), 'film' );

            $actorArr = array();
            
            $i = 0;
            foreach ( $post->scene as $post ) {
			
			
                setup_postdata( $post );							
				//$actorArr[$i]['actor'] = '<a href="'. get_permalink(). '">'.$post->post_title.'</a>';	
                $actorArr[$i]['role'] = p2p_get_meta($post->p2p_id, 'role', true );	
				$actorArr[$i]['fonction']  = p2p_get_meta($post->p2p_id, 'fonction', true ) ;
				
				$i++;        
            }
            
           
            foreach ($actorArr as $actor){
                
				if( $actor['fonction'] == 'victime' ){
				
					//echo '<li><small>'.$sceneLink.$actor['role']. '</a> : '. $sceneCategories .'</small></li>';
					echo $thumbBlock;
				} 
            }*/
			
		}
		
        wp_reset_postdata();
	}
}

function getScenesCountByFilm($film){	//affiche toutes les scenes d'un film

	global $post;
	
	$connected = new WP_Query( array(
        'connected_type' => 'film2scene',
        'connected_items' => $film,
        'nopaging' => true,
    ) );

    // Display connected pages
	$count=0;
    if ( $connected->have_posts() ) {

		while ( $connected->have_posts() ) { 

			$connected->the_post();  
			$count++;
		}
		
        wp_reset_postdata();
		echo $count;
	}
}

function getActorsByFilm($film){	//affiche juste d'un film

	global $post;
	$connected = new WP_Query( array(
        'connected_type' => 'film2scene',
        'connected_items' => $film,
        'nopaging' => true,
    ) );

    if ( $connected->have_posts() ) {
	
		$actorArr = array();
	
		while ( $connected->have_posts() ) { 

			$connected->the_post(); 

			p2p_type( 'scene2acteur' )->each_connected( $connected, array(), 'scene' );
			p2p_type( 'film2scene' )->each_connected( $post->scene, array(), 'film' );

			foreach ( $post->scene as $post ) {
				setup_postdata( $post );					
				$actor = '<a href="'. get_permalink() . '">'.$post->post_title.'</a>';
				$actorArr[] = $actor;
			}
		}
		$actorArr = array_unique($actorArr);
		$listeActors = '';
		$i = 1;
		foreach($actorArr as $tmp)
		{
			$listeActors .= $tmp;
			if($i != count($actorArr)){
				$listeActors .= ', ';
			}
			$i++;
		}

		return $listeActors;
		
        wp_reset_postdata();
	}
}

function getRealisatorByFilm($film){	//affiche juste d'un film

	global $post;
	$connected = new WP_Query( array(
        'connected_type' => 'film2realisateur',
        'connected_items' => $film,
        'nopaging' => true,
    ) );
	
	while ( $connected->have_posts() ) { 
			
		$connected->the_post(); 
			
		$realLink =  '<a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a> ';
        wp_reset_postdata();
	}
	
	return $realLink;
}

function getSceneTitle($scene,$type = "long", $display=false){	//affiche les acteurs presents dans la scene
	
	global $post;
	$connected = new WP_Query( array(
	  'connected_type' => 'film2scene',
	  'connected_items' => $scene,
	  'nopaging' => true,
	) );
	
	$output = '';
	
	if ( $connected->have_posts() ) {
	?>
		<?php while ( $connected->have_posts() ) { 

			$connected->the_post(); 
		
			//echo '<a href="'. get_permalink() . '">'.$post->post_title.'</a>, ';
			if($type == "long" || $type == "moyen"){
				$output .= $post->post_title.', ';
			}

			wp_reset_postdata(); 
		} 
		// Prevent weirdness
		wp_reset_postdata();
	}
	
	////////// AFFICHAGE DES ACTEURS 
	$connectedActors = new WP_Query( array(
	  'connected_type' => 'scene2acteur',
	  'connected_items' => $scene,
	  'nopaging' => true,
	) );

	if ( $connectedActors->have_posts() ) {

		 while ( $connectedActors->have_posts() ) { 
			
					$connectedActors->the_post(); 
					if( p2p_get_meta($post->p2p_id, 'fonction', true ) == 'victime' ){  
					
						$output .= p2p_get_meta($post->p2p_id, 'role', true );
						if($type == "long"){	
							$output .= ' ('.$post->post_title.')';
						}
						//echo ' (<a href="'. get_permalink() . '">'.$post->post_title.'</a>) ';
					}
				 } 
		wp_reset_postdata();
	}
	
	if($display){
		echo $output;
	}else{
		return $output;
	}
	

}	

function makePostTitle($scene){	//affiche les acteurs presents dans la scene
	
	global $post;
	
	$connected = new WP_Query( array(
	  'connected_type' => 'film2scene',
	  'connected_items' => $scene,
	  'nopaging' => true,
	) );

	if ( $connected->have_posts() ) {
	
		while ( $connected->have_posts() ) { 
		
			$connected->the_post(); 

			$newTitle = $connected->post->post_title.', ';

			wp_reset_postdata(); 
		} 
		// Prevent weirdness
		wp_reset_postdata();
	}
	/*
	////////// AFFICHAGE DES ACTEURS 
	$connectedActors = new WP_Query( array(
	  'connected_type' => 'scene2acteur',
	  'connected_items' => $scene,
	  'nopaging' => true,
	) );

	if ( $connectedActors->have_posts() ) {

		 while ( $connectedActors->have_posts() ) { 
			
					$connectedActors->the_post(); 
					if( p2p_get_meta($post->p2p_id, 'fonction', true ) == 'victime' ){  
					
						//$newTitle .= p2p_get_meta($post->p2p_id, 'role', true ).' ('.$post->post_title.')';
						$newTitle .= p2p_get_meta($post->p2p_id, 'role', true );
				
					}
				 } 
		wp_reset_postdata();
	}*/
	return $newTitle;
}	

function getActorsByScene($scene){	//affiche les acteurs presents dans la scene
	
	global $post;
	
	////////// AFFICHAGE DES ACTEURS 
	$connectedActors = new WP_Query( array(
	  'connected_type' => 'scene2acteur',
	  'connected_items' => get_queried_object(),
	  'nopaging' => true,
	) );

	if ( $connectedActors->have_posts() ) {
	?>
		<table class="table table-condensed ">
			<thead>
				<tr>
					<th>Acteur</th>
					<th>R&ocirc;le</th>
					<th >Victime</th>
				</tr>
			</thead>
			<tbody>
				<?php while ( $connectedActors->have_posts() ) { 
					echo "<tr>";
					$connectedActors->the_post(); 
					echo '<td><a href="'. get_permalink() . '">'.$post->post_title.'</a></td><td>'. p2p_get_meta($post->p2p_id, 'role', true ).'</td>' ;
					
					echo p2p_get_meta($post->p2p_id, 'fonction', true ) == 'victime' ?  '<td ><span class="glyphicon glyphicon-ok"></span></td>' :  '<td></td>' ;
					
					echo "</tr>";
				 } ?>
			 </tbody>
		</table>
		<?php 
		wp_reset_postdata();
	}
}	

function getFilmsByReal($scene){	//liste les films qu'a fait le realisateur
	
	global $post;
	
	////////// AFFICHAGE DES ACTEURS 
	$connectedFilms = new WP_Query( array(
	  'connected_type' => 'film2realisateur',
	  'connected_items' => get_queried_object(),
	  'nopaging' => true,
	) );

	if ( $connectedFilms->have_posts() ) {
	?>
		<table class="table table-condensed ">
			<thead>
				<tr>
					<th>Films</th>
					<th>Ann&eacute;e</th>
					<th>Acteurs</th>
				</tr>
			</thead>
			<tbody>
				<?php while ( $connectedFilms->have_posts() ) { 
					echo "<tr>";
					$connectedFilms->the_post(); 
					echo '<td><a href="'. get_permalink() . '">'.$post->post_title.'</a></td>';
					echo '<td>'. get_field('annee', $post->ID).'</td>';
					echo '<td>'. getActorsByFilm($post->ID).'</td>';
					echo "</tr>";
				 }  ?>
			 </tbody>
		</table>

		<?php 

		wp_reset_postdata();
	}
}	

function getFilmByScene($scene){	//affiche le film dans lequel la scene apparait
	
	global $post;
	
	////////// AFFICHAGE DU FILM
	$connected = new WP_Query( array(
	  'connected_type' => 'film2scene',
	  'connected_items' => get_queried_object(),
	  'nopaging' => true,
	) );

	if ( $connected->have_posts() ) {
	?>
		<?php while ( $connected->have_posts() ) { 
		
			p2p_type( 'film2realisateur' )->each_connected( $connected, array(), 'realisateur' );
			
			$connected->the_post(); 
			$content = get_the_content(); 
			$thumb = get_the_post_thumbnail($connected->ID, 'large',array('class' => 'img-responsive')); 
			
			?>
			<!--<div class="row">
				<div class="col-md-4">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo $thumb; ?></a>
				</div>
			<div class="col-md-8">
					<p><small>
						<strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> 
				
					<?php 
				
						foreach ( $post->realisateur as $real ) {
							setup_postdata( $real );
							echo 'r&eacute;alis&eacute; par <a href="'.get_permalink($real->ID).'">'.$real->post_title.'</a> ';
						}

						echo '</strong><br/>'.$content;
				
					?>
					</small>
					</p>
				</div>
			</div>-->
			
			<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			
			<?php 
				
				foreach ( $post->realisateur as $real ) {
					setup_postdata( $real );
					echo '<p>R&eacute;alis&eacute; par <a href="'.get_permalink($real->ID).'">'.$real->post_title.'</a></p>';
				}

				echo '<hr>'.$content;
				
			?>
			
			
			
			<?php
			 
			wp_reset_postdata(); 
		} 
		// Prevent weirdness
		wp_reset_postdata();
	}
}

function getOtherScenesByScene($scene){	//affiche les scenes issues du meme film que la scene courante

	global $post;
	
	////////// AFFICHAGE DES AUTRES SCENES DU FILM
	$related = p2p_type( 'film2scene' )->get_related( get_queried_object() );
	
	if ( $related->have_posts() ) {
	?>
		
		<?php while ( $related->have_posts() ) {  $related->the_post(); 
		
			//makeThumb($post->ID,"court",3);

			echo '<a href="'.get_permalink().'" title="'.get_the_title().'" >';	
			echo get_the_post_thumbnail($post_id, 'thumbnail',array('class' => 'thumbnail'));			
		    echo '<h2 class="title">';getSceneTitle($post->ID, 'court', true); echo'</h2>';	
		    echo '<div class="date"><i class="fa fa-clock-o"></i>'; the_time('j M , Y') ; echo '&nbsp;';
		    echo '</div></a>';

		} ?>
		
		<?php
		// Prevent weirdness
		wp_reset_postdata();
	}
}

function getConnectedByScene($scene){
	
	global $post;
	
	////////// AFFICHAGE DES ACTEURS 
	$connectedActors = new WP_Query( array(
	  'connected_type' => 'scene2acteur',
	  'connected_items' => $scene,
	  'nopaging' => true,
	) );

	if ( $connectedActors->have_posts() ) {
	?>
		<h3>Les tarlouzes dedans la sc&egrave;ne :</h3>
		<table class="table table-condensed ">
			<thead>
				<tr>
					<th>Acteur</th>
					<th>R&ocirc;le</th>
				</tr>
			</thead>
			<tbody>
				<?php while ( $connectedActors->have_posts() ) { 
					echo "<tr>";
					$connectedActors->the_post(); 
					echo '<td><a href="'. $post->guid. '">'.$post->post_title.'</a></td><td>'. p2p_get_meta($post->p2p_id, 'role', true ).'</td>' ;
					echo "</tr>";
				 } ?>
			 </tbody>
		</table>
		<?php 
		wp_reset_postdata();
	}
	
	////////// AFFICHAGE DU FILM
	$connected = new WP_Query( array(
	  'connected_type' => 'film2scene',
	  'connected_items' => get_queried_object(),
	  'nopaging' => true,
	) );

	if ( $connected->have_posts() ) {
	?>
	
		<h3>Issue de le film :</h3>
	
		<?php while ( $connected->have_posts() ) { 
		
			$connected->the_post(); ?>
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> 
			<?php the_post_thumbnail('large');?>
			<?php the_content(); ?>

		<?php 
		} 
		// Prevent weirdness
		wp_reset_postdata();
	}
	
	////////// AFFICHAGE DES AUTRES SCENES DU FILM
	$related = p2p_type( 'film2scene' )->get_related( get_queried_object() );
	
	if ( $related->have_posts() ) {
	?>

		<h3>Autres sc&egrave;nes :</h3>
	
		
		<?php while ( $related->have_posts() ) { 
		
			$related->the_post(); ?>
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> 
			
		<?php } 
		// Prevent weirdness
		wp_reset_postdata();
	}
}

function pippin_related_posts($taxonomy = '') {	//affichage d'autres post en rapport en fonction de la taxo
 
global $post;
 
if($taxonomy == '') { $taxonomy = 'post_tag'; }
 
$tags = wp_get_post_terms($post->ID, $taxonomy);

	if ($tags) {
		$first_tag 	= $tags[0]->term_id;
		$second_tag = $tags[1]->term_id;
		$third_tag 	= $tags[2]->term_id;
		$args = array(
			'post_type' => get_post_type($post->ID),
			'posts_per_page' => 4,
			'post__not_in' => array($post->ID),
			'tax_query' => array(
				'relation' => 'OR',
				array(
					'taxonomy' => $taxonomy,
					'terms' => $second_tag,
					'field' => 'id',
					'operator' => 'IN',
				),
				array(
					'taxonomy' => $taxonomy,
					'terms' => $first_tag,
					'field' => 'id',
					'operator' => 'IN',
				),
				array(
					'taxonomy' => $taxonomy,
					'terms' => $third_tag,
					'field' => 'id',
					'operator' => 'IN',
				)
			)
		);
		$related = get_posts($args);
		if( $related ) {
			$temp_post = $post;
				
				$content .= '<div class="relatedItems pippin">';
				
				foreach($related as $post) {
				
					setup_postdata($post);

					echo '<div class="row ">';
					makeThumb($post->ID,"moyen",12);
					echo '</div>';;

				}
			$post = $temp_post;
		}
	}
}

function list_related($post_id, $title = ''){
    
    $query_args = array(
        'connected_type' => 'project_post',
        'connected_items' => intval($post_id),        
        'nopaging' => true
    );
    
    $query = new WP_Query($query_args);
    
    if($query->have_posts()){
    
    if(empty($title))
        $title = __('Related items', 'frl');
?>    
    <h3><?php echo $title; ?></h3>    
    <ul class='related-items'>
    
<?php while($query->have_posts()): $query->the_post(); ?>
    <li><a href="<?php the_permalink();?>"><?php the_title();?></a></li>
<?php endwhile;?>

    </ul>
<?php
   }
    wp_reset_postdata();
}

function list_related_by_type($post_id, $type, $title=''){
    global $post;
    
    $query_args = array(
        'connected_type' => 'project_post',
        'connected_items' => intval($post_id),        
        'nopaging' => true,
        'connected_meta' => array(
            array(
                'key' => 'type',
                'value' => $type,
            )
        )
    );
    
    $query = new WP_Query($query_args);
    
    if($query->have_posts()){
    
    if(empty($title))
        $title = __('Related items', 'frl');
?>    
    <h3><?php echo $title; ?></h3>    
    <ul class='related-items <?php echo esc_attr($type); ?>'>
    
<?php while($query->have_posts()){
		$query->the_post()?>
    <li>
        <a href="<?php the_permalink();?>"><?php the_title();?></a>
        <span class="comment-meta"><?php echo p2p_get_meta($post->p2p_id, 'comment', true );?></span>
    </li>
<?php  } ?>

    </ul>
<?php
    }

}


/***** HOOK FOR POSTS *****/

add_action( 'save_post', 'save_scene_meta_movie', 10, 2 );
function save_scene_meta_movie( $post_ID, $post ) {	//on sauvegarde automatiquement la taxo movieslugtags avec le slug du film connecté pour le gerer dans l'url

     if ( 'scene' != $post->post_type || wp_is_post_revision( $post_ID ) ){
        return;
	}
	
	// Check permissions
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
	
	$connected = new WP_Query( array(
	  'connected_type' => 'film2scene',
	  'connected_items' => $post_ID
	) );
     
	if ( $connected->have_posts() ) {

		while ( $connected->have_posts() ) { 
			
			$connected->the_post(); 
			$title = $connected->post->post_name;
			 wp_reset_postdata();
		} 
		 wp_reset_postdata();
		$taxonomy_slug = $title;
		
	}else{
		$taxonomy_slug = 'inconnu';
	}
	
	  wp_set_object_terms( $post_ID, $taxonomy_slug, 'movieslugtags' );
	
	foreach ( $_POST[p2p_meta] as $meta ) {							  
		if($meta['fonction'] == 'victime'){
			$postName = $meta['role'];			
		}
	}
	
	$filmName = makePostTitle($post_ID);
	
	if(isset($postName)){ //si on a une victime dans la scene, on reecrit le permalien avec son nom
	 
		global $wpdb;
		$where = array( 'ID' => $post_ID );
		$wpdb->update( $wpdb->posts, array( 'post_title' => ucfirst($filmName).sanitize_title($postName)), $where );
		$wpdb->update( $wpdb->posts, array( 'post_name' => sanitize_title($postName) ), $where );
	}
}

add_action( 'save_post', 'kia_save_first_letter' );
function kia_save_first_letter( $post_id ) {	//on sauvegarde automatiquement la premiere lettre du film dans la taxo "glossary" pour le referencer dans le glossaire
    
	// verify if this is an auto save routine. 
    // If it is our form has not been submitted, so we dont want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
        return;
	}
	
    //check location (only run for posts)
    $limitPostTypes = array('film');
    if (!in_array($_POST['post_type'], $limitPostTypes)) return;

    // Check permissions
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;

    $taxonomy = 'glossary';
    wp_set_post_terms( $post_id, strtolower(substr($_POST['post_title'], 0, 1)), $taxonomy );

    delete_transient( 'kia_archive_alphabet');
}


/***** NAVIGATION *****/

function movieGlossary(){	//la fonction qui affiche la liste des lettres du glossaire
	
    $taxonomy = 'glossary';
	 
	// save the terms that have posts in an array as a transient
	if ( false === ( $alphabet = get_transient( 'kia_archive_alphabet' ) ) ) {
		// It wasn't there, so regenerate the data and save the transient
		$terms = get_terms($taxonomy);
	 
		$alphabet = array();
		if($terms){
			foreach ($terms as $term){
				$alphabet[] = $term->slug;
			}
		}
		 set_transient( 'kia_archive_alphabet', $alphabet );
	}
 
	//echo '<div class="pagination pagination-small pagination-centered">';
	echo '<ul class="pagination pagination-sm">';
 
    foreach(range('a', 'z') as $i){            
 
        $current = ($i == get_query_var($taxonomy)) ? "active" : "";              
 
        if (in_array( $i, $alphabet )){ 
            printf( '<li class=" %s" ><a href="%s">%s</a></li>', $current, get_term_link( $i, $taxonomy ), strtoupper($i) );
        } else { 
            printf( '<li class="disabled %s " "><a href="">%s</a></li>', $current, strtoupper($i) );
        } 
 
    }
     
	echo '</ul>';
	//echo '</div>';

}

function mycustomhtml($key, $echo = FALSE) {
global $post;
$mycustomhtml = get_post_meta($post->ID, $key, true);
if($echo == FALSE) return $mycustomhtml;
echo $mycustomhtml;
}

/*
function archive_queryvars( $qvars ) {
    $qvars[] = 'showarchive';
    return $qvars;
}
add_filter('query_vars', 'archive_queryvars' );


function is_custom_archive() {
    global $wp_query;
    return isset( $wp_query->query_vars['showarchive'] );
}

function archive_search_where( $where ){
    global $wpdb;

    if( is_custom_archive() ) {
        $char = get_query_var('showarchive');
        if ( ! empty($char) ) {
            $where .= "AND {$wpdb->posts}.post_title LIKE '{$char}%'";
        }
    } 

  return $where;
}
add_filter('posts_where', 'archive_search_where' );
*/
/* 
 * add archive query arg to link
 */

/*
function get_custom_archive_link($char = '') {
    $params = array(
            'showarchive' => $char,
            );
    return add_query_arg( $params, home_url('/') );
}
*/



/*
function set_scene_title ($post_id) {
    if ( $post_id == null || empty($_POST) )
        return;

    if ( !isset( $_POST['post_type'] ) || $_POST['post_type']!='scene' )  
        return; 

    if ( wp_is_post_revision( $post_id ) )
        $post_id = wp_is_post_revision( $post_id );

    global $post;  
    if ( empty( $post ) )
        $post = get_post($post_id);

    if ($_POST['rating_date']!='') {
        global $wpdb;
        $date = date('l, d.m.Y', strtotime($_POST['scene_date']));
        $title = 'TV ratings for ' . $date;
        $where = array( 'ID' => $post_id );
        $wpdb->update( $wpdb->posts, array( 'post_title' => $title ), $where );
    }
}
add_action('save_post', 'set_scene_title', 12 );

*/
/*
class MyWalker extends Walker_Category {
 
	function start_el(&$output, $category, $depth, $args) {
		extract($args);
 
		$cat_name = esc_attr( $category->name );
		$cat_name = apply_filters( 'list_cats', $cat_name, $category );
		$link = '<a title="' . $cat_name . '" href="' . esc_attr( get_term_link($category) ) . '">';
		
		//$link .= 'rel="'.$category->slug.'"'; 
		
		$link .= ''; $link .= $cat_name . '</a>';
		if ( 'list' == $args['style'] ) {
			$output .= "\tterm_id";
			if ( !empty($current_category) ) {
				$_current_category = get_term( $current_category, $category->taxonomy );
				if ( $category->term_id == $current_category )
					$class .=  ' current-cat';
				elseif ( $category->term_id == $_current_category->parent )
					$class .=  ' current-cat-parent';
			}
			$output .=  ' class="' . $class . '"';
			$output .= ">$link\n";
		} else {
			$output .= "\t$link
\n";
		}
	}
}*/

/*
function remove_page_from_query_string($query_string)
{ 
    if ($query_string['name'] == 'gloubiboulga' && isset($query_string['page'])) {
        unset($query_string['name']);
        // 'page' in the query_string looks like '/2', so split it out
        list($delim, $page_index) = split('/', $query_string['page']);
        $query_string['paged'] = $page_index;
    }      
    return $query_string;
}

add_filter('request', 'remove_page_from_query_string');
*/



//add_filter('pre_get_posts','mySearchFilter');

/***** FILTRAGE DES POSTS SUR LA HOME *****/
/*
function my_get_posts( $query ) {
    if ( is_home() ){
        $query->set( 'post_type', array( 'scene' ,'') );
    }
    return $query;
}
add_filter( 'pre_get_posts', 'my_get_posts' );
*/
?>