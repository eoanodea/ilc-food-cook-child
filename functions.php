<?php

use \WpfpInterface\Wrapper;

/**
 * Get the latest post for the food tv category
 * and find a youtube video in it's content
 * 
 * @return {string} a youtube URL
 */
function get_latest_post_video_url()
{

    //Get the latest post from the food-tv category
    $recent_posts = wp_get_recent_posts(array(
        'numberposts' => 1, // Number of recent posts
        'post_status' => 'publish', // Get only the published posts
        'category' => 143 // Post must have the food tv category
    ));

    //Get the content from the first post
    $content = $recent_posts[0]['post_content'];

    //Search for a youtube url within the content
    preg_match('~(?:https?://)?(?:www.)?(?:youtube.com|youtu.be)/(?:watch\?v=)?([^\s]+)~', $content, $match);

    //Contcat into a URL and return
    return 'https://youtube.com/watch?v=' . $match[1];
}

/**
 * Gets the title of the current recipe type category
 * If the URL includes a subcat parameter, it will display that category as a title
 * If not it will display "Recipes"
 * 
 * @return {string} Recipe Title
 */
function get_recipe_type_title()
{
    //Check the URL for a subcategory
    $subcat = $_GET['subcat'];

    if ($subcat) { //If a subcategory exists, get the category by it's slug
        $category = get_term_by('slug', $subcat, 'recipe_type');

        //Return a concatenated string with the category name
        return 'RECIPES - ' . $category->name;
    }

    //No subcategory exists, just return Recipes
    return 'RECIPES';
}

/**
 * Displays a list of all recipe type categories, in a grid format
 * along with their associated images
 * 
 * @return {string} HTML recipe categories in grid format
 */
function display_recipe_types()
{
    //Check the URL for a subcategory
    $subcat = $_GET['subcat'];

    //Declare the parent ID to be 0
    $parentId = 0;

    //If a subcategory exists, get the category by it's slug
    if ($subcat) {

        $category = get_term_by('slug', $subcat, 'recipe_type');

        //Update the parentID to match the category ID
        $parentId = $category->term_id;
    }

    //Get a list of recipe type categories
    $args = array(
        'taxonomy' => 'recipe_type',
        'orderby' => 'name',
        'order'   => 'ASC',
        'parent' => $parentId
    );

    //Get the categories from the query arguements
    $cats = get_categories($args);

    //declare variable and add div opening wrapper
    $result = '<div class="recipe-type-wrapper">';

    if (count($cats) === 0) return '<h2 class="recipe-not-found">No recipes were found</h2>';

    //Loop through each
    foreach ($cats as $cat) {
        //Open the recipe item div
        $gridItem = '<div class="recipe-type-item">';

        //Declare a url cariable with the current website title
        $itemURL = get_site_url();

        //If the parentId is 0, use the subcategory query
        if ($parentId === 0) $itemURL .= '/recipes/?subcat=' . $cat->slug;
        else $itemURL .= '/recipe-type/' . $cat->slug; //If not, direct to a recipe-type page

        //Open a link tag linking to the current category
        $gridItem .= '<a href="' . $itemURL . '">';

        //Concat the image
        $gridItem .= get_category_image($cat->term_taxonomy_id, $cat->category_parent);

        //Concat the title
        $gridItem .= '<h2>' . $cat->cat_name . '</h2>';

        //Close the link
        $gridItem .= '</a>';

        //Close the div
        $gridItem .= '</div>';

        //Add to the outer result variable
        $result .= $gridItem;
    }
    //Close div
    $result .= '</div>';

    //Return HTML
    return $result;
}

/**
 * Displays a list of 6 recipe type categories, in a grid format
 * along with their associated images
 * 
 * @return {string} HTML recipe categories in grid format
 */
function display_recipe_types_home()
{
    //Get a list of recipe type categories
    $args = array(
        'taxonomy' => 'recipe_type',
        'orderby' => 'name',
        'order'   => 'ASC',
        'parent' => '0',
    );

    //Get the categories from the query arguements
    $cats = get_categories($args);

    //declare variable and add div opening wrapper
    $result = '<div class="recipe-type-wrapper">';

    if (count($cats) === 0) return '<h2 class="recipe-not-found">No recipes were found</h2>';

    $count = 0;
    //Loop through each
    foreach ($cats as $cat) {
        //Displays only 6 items
        if($count > 5) break;
        $count++;
        //Open the recipe item div
        $gridItem = '<div class="recipe-type-item">';

        //Declare a url cariable with the current website title
        $itemURL = get_site_url();

        //If the parentId is 0, use the subcategory query
        if ($parentId === 0) $itemURL .= '/recipes/?subcat=' . $cat->slug;
        else $itemURL .= '/recipe-type/' . $cat->slug; //If not, direct to a recipe-type page

        //Open a link tag linking to the current category
        $gridItem .= '<a href="' . $itemURL . '">';

        //Concat the image
        $gridItem .= get_category_image($cat->term_taxonomy_id, $cat->category_parent, 'large');

        //Concat the title
        $gridItem .= '<h2 class="recipe-type-title">' . $cat->cat_name . '</h2>';
        $gridItem .= '<p class="recipe-type-count">' . $cat->count . ' Recipes </p>';

        //Close the link
        $gridItem .= '</a>';

        //Close the div
        $gridItem .= '</div>';

        //Add to the outer result variable
        $result .= $gridItem;
    }
    //Close div
    $result .= '</div>';

    //Return HTML
    return $result;
}

// chefs-specials

/**
 * Displays a list of 6 chefs, in a grid format
 * along with their associated images
 * 
 * @return {string} HTML recipe categories in grid format
 */
function display_recipe_chefs_home()
{

    $category = get_term_by('slug', 'chefs-specials', 'recipe_type');

    //Update the parentID to match the category ID
    $parentId = $category->term_id;

    //Get a list of recipe type categories
    $args = array(
        'taxonomy' => 'recipe_type',
        'orderby' => 'name',
        'order'   => 'ASC',
        'parent' => $parentId,
    );

    //Get the categories from the query arguements
    $cats = get_categories($args);

    //declare variable and add div opening wrapper
    $result = '<div class="recipe-chefs-wrapper">';

    if (count($cats) === 0) return '<h2 class="recipe-not-found">No recipes were found</h2>';

    $count = 0;
    //Loop through each
    foreach ($cats as $cat) {
        //Displays only 6 items
        if($count > 5) break;
        $count++;
        //Open the recipe item div
        $gridItem = '<div class="recipe-chefs-item">';

        //Declare a url cariable with the current website title
        $itemURL = get_site_url();

        //If the parentId is 0, use the subcategory query
        if ($parentId === 0) $itemURL .= '/recipes/?subcat=' . $cat->slug;
        else $itemURL .= '/recipe-type/' . $cat->slug; //If not, direct to a recipe-type page

        //Open a link tag linking to the current category
        $gridItem .= '<a href="' . $itemURL . '">';

        //Concat the image
        $gridItem .= get_category_image($cat->term_taxonomy_id, $cat->category_parent, 'large');

        //Concat the title
        $gridItem .= '<p class="recipe-chefs-title">' . $cat->cat_name . '</p>';
        // $gridItem .= '<p class="recipe-type-count">' . $cat->count . ' Recipes </p>';

        //Close the link
        $gridItem .= '</a>';

        //Close the div
        $gridItem .= '</div>';

        //Add to the outer result variable
        $result .= $gridItem;
    }
    //Close div
    $result .= '</div>';

    //Return HTML
    return $result;
}

/**
 * Gets the image associated with the specified term ID
 * 
 * @return {HTML} a built up HTML of an image and it's src
 */
function get_category_image($term_id, $parentId, $size = 'thumbnail')
{
    //Reference the Taxonomy image plugin
    $taxonomy_images = get_option('taxonomy_image_plugin');

    //Get the image ID
    $attachment_id = $taxonomy_images[$term_id];


    if (!$attachment_id) { //If the taxonomy doesn't have an image, fallback to the parent
        //Get the parent category by the term_id
        $category = get_term_by('term_id', $parentId, 'recipe_type');

        //Get the image ID of the parent
        $attachment_id = $taxonomy_images[$category->term_taxonomy_id];
    }

    //Get the image from it's ID
    $image = wp_get_attachment_image($attachment_id, $size); // Image

    //Return the image
    return $image;
}

function ajax_check_user_logged_in() {
    echo is_user_logged_in()?'yes':'no';
    die();
}

/**
 * Load css stylesheet
 */
function load_styles()
{
	wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css');
	wp_enqueue_script('script', get_stylesheet_directory_uri() . '/script.js');
}


function load_favourite_posts() {
    $userFavs= new Wrapper();
    $posts= $userFavs->all_posts();

    if($posts) {
        $args = array(
            'post__in' => $posts
        );
        $query = new \WP_Query( $args );
        // foreach($posts as $post) {
            echo "<pre>";
            // var_dump($query);
            echo "</pre>";
        // }
    } else echo "No recipes found";
}

function get_favourite_posts_query($query) {
    $userFavs= new Wrapper();
    $posts= $userFavs->all_posts();

    if($posts) {
        $args = array(
            'taxonomy' => 'recipe_type',
            'orderby' => 'name',
            'order'   => 'ASC',
            'post__in' => $posts
        );
        $newQuery = new \WP_Query( $args );

        $query->set($newQuery);
    }  else echo "No recipes found";

}

/**
 * ACTIONS
 */
add_action('wp_enqueue_scripts', 'load_styles');

// Check if the user is logged in
add_action('wp_ajax_is_user_logged_in', 'ajax_check_user_logged_in');
add_action('wp_ajax_nopriv_is_user_logged_in', 'ajax_check_user_logged_in');

/**
 * SHORTCODES
 */
add_shortcode('food-tv-latest', 'get_latest_post_video_url');
add_shortcode('display-recipe-type-title', 'get_recipe_type_title');
add_shortcode('display-recipe-types', 'display_recipe_types');
add_shortcode('display-popular-collections', 'display_recipe_types_home');
add_shortcode('display-chefs-recipes', 'display_recipe_chefs_home');
add_shortcode('wp-ilc-favourite-posts', 'load_favourite_posts');

add_action( 'elementor/query/my_cookbook_query', 'get_favourite_posts_query');
