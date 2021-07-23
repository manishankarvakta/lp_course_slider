<?php
/**
 * Plugin Name:       Learn Press CourseSlider
 * Plugin URI:        https://aurortec.net/
 * Description:       Course slider by caregory
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Manishankar Vakta
 * Author URI:        https://aurortec.net/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       lp-slider
 * Domain Path:       /languages
 */

 // Plugin Assest
 function my_plugin_assets() {

    wp_register_style( 'slider_styleCss', plugins_url( '/css/splide.min.css' , __FILE__ ), array(), '1.0', 'all' );
    wp_enqueue_style( 'slider_styleCss' );
    // custome style
    wp_register_style( 'styleCss', plugins_url( '/css/style.css' , __FILE__ ), array(), '1.0', 'all' );
    wp_enqueue_style( 'styleCss' );    

    wp_register_script( 'slider_script', plugins_url( '/js/splide.min.js' , __FILE__ ), array(), '1.0', false );
    wp_enqueue_script( 'slider_script' );
    // customescript
    wp_register_script( 'script', plugins_url( '/js/script.js' , __FILE__ ), array(), '1.0', false );
    wp_enqueue_script( 'script' );
}
add_action( 'wp_footer', 'my_plugin_assets' );


 // create shortCode
function slider_shotCode( $atts ){
    $a = shortcode_atts( array(
		'cat' => '0',
		'filter' => 'true',
	), $atts );
    getCourseSlider($a['cat'], $a['filter']);
}
add_shortcode( 'lp_course_slider', 'slider_shotCode' );



// getCourseSlider
function getCourseSlider($cat = NULL, $filter = NULL){

    // courseSliderArray
    $course = getCourseByTerms($cat);

    // var_dump($cat.'-'.$filter);
    // var_dump($course);

    // getCategories
    $cat = getCategory();
    
    if($filter == 'true'){
        // create cat view
        $cat_view = '';
        $cat_view .= '<div class="category_wrapper">';
        $cat_view .= '<ul class="cat_list">';
        $cat_view .= '<li class="cat-item active" id="all">';
        $cat_view .= '<b>All</b>';
        $cat_view .= '</li>';
        foreach($cat as $c){
            $cat_view .= '<li class="cat-item"  id="'.$c->term_id.'">';
            $cat_view .=  $c->name;
            $cat_view .= '</li>';
        }
        $cat_view .= '</ul>';
        $cat_view .= '</div>';
    }


    // create one array with all course

    // create slider view
    $slider = "";

        $slider .= '<div class="splide"> <div class="splide__track"> <ul class="splide__list">';
            foreach($course as $co){
                $slider .= '<li class="splide__slide all '.$co->term_id.'" id="'.$co->ID.'">';
                $slider .='<article class="post-course-slider" style="width: 100%; display: inline-block;">';
                $slider .='    <div class="course-box-slider">';
                $slider .='        <div class="course-entry-thumbnail course-thumbnail-box-slider">';
                $slider .='            <a href="'.site_url("courses/".$co->post_name).'" tabindex="0">';
                $slider .='                <img width="1920" height="969" src="'.getCourseThumbnail($co->ID).'" class="attachment-post-thumbnail size-post-thumbnail wp-post-image lazyautosizes ls-is-cached lazyloaded" alt="" ><noscript>';
                $slider .='                <img width="1920" height="969" src="'.getCourseThumbnail($co->ID).'" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt=""  sizes="(max-width: 1920px) 100vw, 1920px" data-eio="l" /></noscript>								';
                $slider .='            </a>';
                $slider .='            <div>';
                // $slider .='                <span class="ti-arrow-right"></span>';
                $slider .='                <div class="curl"></div>';
                $slider .='                <a href="'.site_url("courses/".$co->post_name).'" class="link-for-jostcourse" tabindex="0"></a>';
                $slider .='            </div>';
                $slider .='        </div>';
                $slider .='        <div class="course-entry-header entry-wrapper course-detail-box-slider left">';
                $slider .='                <div class="data-course">';
                $slider .='                    <span class="single_course_meta_data_content">';
                $slider .='                        <span class="icon-data-course icon-Icon_Book"></span>';
                $slider .='                        '.getLesson($co->ID).' lesson											';
                $slider .='                    </span>';
                $slider .='                    <span class="single_course_meta_data_content">';
                $slider .='                        <span class="icon-data-course icon-Icon_Student_male"></span>';
                $slider .='                        '.getEnrolledStudent($co->ID).' students											';
                $slider .='                    </span>';
                $slider .='                </div>            ';
                $slider .='            <span class="course-entry-title">';
                $slider .='                <a href="'.site_url("courses/".$co->post_name).'" style="font-size: 24px !important; font-weight: 500 !important; line-height: 30px !important;" tabindex="0">'.$co->post_title.'</a>';
                $slider .='            </span>            ';
                $slider .='            <div class="course-header-info">';
                $slider .='                <div class="course-read-more">';
                $slider .='                    <a href="'.site_url("courses/".$co->post_name).'" class="read-more" tabindex="0">';
                $slider .='                        See details											</a>';
                $slider .='                    <span class="arrow-readmore icon-Icon_Arrow-Right"></span>';
                $slider .='                </div>';
                $slider .='                <div class="price">';
                $slider .='                    <span class="course-price">Free</span>										';
                $slider .='                </div>';
                $slider .='            </div>';
                $slider .='        </div>';
                $slider .='    </div>';
                $slider .='</article>';
                // $slider .=  '<img src="'.getCourseThumbnail($co->ID).'" height="150px">';
                // $slider .= '<p>'.getCourseThumbnail($co->ID).'</p>';
                $slider .= '</li>';
            }  
        $slider .=  '</ul> </div> </div>';
    
    // print cat view
    echo $cat_view;
    // print view
    echo $slider;  

}


// get course by categories
function getCourseByTerms($cat = NULL ){
    global $wpdb;
    
    $posts_array = array();

    if($cat == '0'){
        $categories = getCategory();
        foreach($categories as $category){
            // get_post_by_terms
            $posts = get_terms_post($category->term_id);  
                
                foreach($posts as $post){
                    $post->term_id = $category->term_id;
                    // $id = ['term_id'=>;
                    // $post = array_push($post, $id);
                    if($post->post_type == 'lp_course'){
                        array_push($posts_array, $post);
                    }
                }
            
        }
    }else{
        $posts = get_terms_post($cat);
            // var_dump ($posts);
        foreach($posts as $post){
            $post->term_id = $category->term_id;
            // $id = ['term_id'=>;
            // $post = array_push($post, $id);
            if($post->post_type == 'lp_course'){
                array_push($posts_array, $post);
            }
        }
    }
    return $posts_array; 
}



function get_terms_post($category=NULL){
    $posts = get_posts(
            array(
                'posts_per_page' => -1,
                'post_type' => 'lp_course',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'course_category',
                        'field' => 'term_id',
                        'terms' => $category,//$category,
                    )
                )
            )
        );
        
    return $posts;
}



// get thumbnail
function getCourseThumbnail($post_id=NULL){
    global $wpdb;
    
    $sql = "SELECT wp.ID, wpm2.meta_value
        FROM wp_posts wp 
        INNER JOIN wp_postmeta wpm
            ON (wp.ID = wpm.post_id  AND wpm.meta_key = '_thumbnail_id')
        INNER JOIN wp_postmeta wpm2
            ON (wpm.meta_value = wpm2.post_id AND wpm2.meta_key = '_wp_attached_file')";
    // $sql = "SELECT * FROM `wp_postmeta` WHERE `post_id` = AND `post_status` = 'publish'";
    $thumbnail = $wpdb->get_results($sql);
    // echo '<pre>';
    // echo $sql;
    // var_dump($post_id);
    // echo '</pre>';

    foreach($thumbnail as $t){
        
        if($post_id == $t->ID){
            if($t->meta_value == ""){
                $img = site_url("wp-content/plugins/elementor/assets/images/placeholder.png");                
            }else{
                $img = site_url("wp-content/uploads/".$t->meta_value);
            }

        }
    }
    return $img;
}


// get enrolled users
function getEnrolledStudent($id = NULL){
    global $wpdb;
    $sql = "SELECT * FROM `wp_postmeta` WHERE `post_id` = {$id} AND `meta_key` = '_lp_students' OR `post_id` =  {$id} AND `meta_key` = 'count_enrolled_users'";

    $student = $wpdb->get_row($sql);
    if($student->meta_value != ''){
            $st = $student->meta_value;
        }else{
            $st = '0';
        }
    return $st;
}


function getLesson($id = NULL){
    global $wpdb;
    $sql = "SELECT * FROM `wp_postmeta` WHERE `post_id` = {$id} AND `meta_key` = 'count_items'";

    $student = $wpdb->get_row($sql);
    if($student->meta_value != ''){
            $st = $student->meta_value;
        }else{
            $st = '0';
        }
    return $st;

}

// get Category
function getCategory(){
    $taxonomies = array( 
        'course_category',
    );
    
    $args = array(
        'orderby'           => 'name', 
        'order'             => 'ASC',
        'hide_empty'        => true, 
        'exclude'           => array(), 
        'exclude_tree'      => array(), 
        'include'           => array(),
        'number'            => '', 
        'fields'            => 'all', 
        'slug'              => '', 
        'parent'            => '0',
        'hierarchical'      => true, 
        'child_of'          => 0, 
        'get'               => '', 
        'name__like'        => '',
        'description__like' => '',
        'pad_counts'        => false, 
        'offset'            => '', 
        'search'            => '', 
        'cache_domain'      => 'core'
    ); 
    
    $terms = get_terms( $taxonomies, $args );
    
    return $terms;
}
