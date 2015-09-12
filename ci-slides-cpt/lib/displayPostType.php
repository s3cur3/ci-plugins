<?php





/**
 * @param $category string Name of the category to filter. If empty, we'll return slides from all categories.
 * @param $maxNumSlides int Maximum number of slides to return
 * @param $size string One of two constants: SIZE_LG or SIZE_MD (use MD if the image will be in a container div)
 * @return array All the slide data you need. Keys are 'content', 'title', 'imgURL', 'imgWidth', and 'imgHeight'
 */
function ciGetAllSlides($category, $maxNumSlides, $size) {
    if( $size == CI_SIZE_INPAGE ) {
        $size = CI_SIZE_MD;
    }

    $query = null;
    if( $category ) {
        $query = new WP_Query(array('category_name' => $category, 'post_type' => CI_SLIDE_TYPE, 'showposts' => $maxNumSlides));
    } else {
        $query = new WP_Query('showposts=' . $maxNumSlides . '&post_type=' . CI_SLIDE_TYPE);
    }

    $slidesArray = array();
    while( $query->have_posts() ) {
        $query->next_post();

        $attachment = wp_get_attachment_image_src( get_post_thumbnail_id($query->post->ID), $size );

        if($attachment) {
            $slidesArray[] = array(
                'id' => $query->post->ID,
                'content' => $query->post->post_content,
                'title' => $query->post->post_title,
                'imgURL' => $attachment[0],
                'imgWidth' => $attachment[1],
                'imgHeight' => $attachment[2],
                'contentPosition' => ciGetNormalizedMeta('caption_position', 'center', $query->post->ID),
                'bg' => ciGetNormalizedMeta('caption_bg', '', $query->post->ID),
                'link' => ciGetNormalizedMeta('link', '', $query->post->ID),
                'darken' => ciGetNormalizedMeta('darken_slide', true, $query->post->ID)
            );
        }
    }

    wp_reset_postdata();
    return $slidesArray;
}


/**
 * Returns the HTML to display an image+text slider
 * @param $category string The category to pull slides from. (If empty, we'll get all known slides.)
 * @param $numSlides int The max number of slides to display.
 * @param $darken Boolean True if images should be darkened when displayed, false to display them just as they are.
 * @param $size string The image size (which must be made known to WP in advance) to output
 * @param $align string Align the slides 'left' or 'right' (or neither)
 * @param $intervalSecs number The amount of time to delay before cycling between one slide and the next (in seconds)
 * @return string The HTML to be output
 */
function ciGetSliderHTML($category = '', $numSlides = 3, $darken = true, $size = CI_SIZE_MD, $align='', $intervalSecs=5) {

    $slides = ciGetAllSlides($category, $numSlides, $size);

    if( count($slides) == 0 ) {
        return "";
    }

    $widthClass = "full-page";
    if( $size == CI_SIZE_LG ) $widthClass = "full-page";
    if( $align ) $widthClass = "inpage " . $align;

    $id = "pageCarousel-$category-$numSlides";
    $intervalMillisecs = $intervalSecs * 1000;
    $out = "<div id=\"{$id}\" class=\"carousel slide {$widthClass}\" data-ride=\"carousel\" data-interval=\"{$intervalMillisecs}\">";
    if( count($slides) > 1 ) {
        $out .= '<!-- Indicators -->';
        $out .= '<ol class="carousel-indicators">';
        for( $i = 0; $i < count($slides); $i++ ) {
            $activeClass = ($i==0 ? 'active' : '');
            $out .= "<li data-target=\"#{$id}\" data-slide-to=\"{$i}\" class=\"{$activeClass}\"></li>";
        }
        $out .= "</ol>";
    }
    $out .= '<div class="carousel-inner">';
    for( $i = 0; $i < count($slides); $i++ ) {
        $slide = $slides[$i];

        $active = ($i == 0 ? "active" : '');
        $darkenClass = ($darken && $slide['darken'] ? "darken" : "");

        $out .= "<div class=\"item {$active}\">";
        $out .= "    <img alt=\"{$slide['title']}\" src=\"{$slide['imgURL']}\" width=\"{$slide['imgWidth']}\" height=\"{$slide['imgHeight']}\" class=\"{$darkenClass}\">";
        $out .= '    <div class="container">';

        $linkPre = $linkPost = '';
        if( $slide['link'] ) {
            $linkPre = "<a href=\"{$slide['link']}\">";
            $linkPost = "</a>";
        }

        if( $slide['contentPosition'] != 'none' ) {
            $colorStyle = "";
            if( $slide['bg'] ) {
                if( $slide['bg'][0] !== "#" ) $slide['bg'] = "#" . $slide['bg'];
                $colorStyle .= " style=\"background:{$slide['bg']}\"";
            }
            $out .= "        <div class=\"carousel-caption {$slide['contentPosition']}\"{$colorStyle}>";
            $out .= "            <h2>{$linkPre}{$slide['title']}{$linkPost}</h2>";
            $out .= "            {$linkPre}{$slide['content']}{$linkPost}";
            $out .= '        </div>';
        }
        $out .= '    </div>';
        $out .= '</div>';
    }
    $out .= '</div><!-- .carousel-inner -->';
    if( count($slides) > 1 ) {
        $out .= "<a class=\"left carousel-control\" href=\"#{$id}\" data-slide=\"prev\"><span class=\"glyphicon glyphicon-chevron-left\"></span></a>";
        $out .= "<a class=\"right carousel-control\" href=\"#{$id}\" data-slide=\"next\"><span class=\"glyphicon glyphicon-chevron-right\"></span></a>";
    }
    $out .= '</div><!-- .carousel -->';
    return $out;
}

/**
 * Wrapper for the getSliderHTML() function, to be used by the Wordpress Shortcode API
 * @param $atts array containing optional 'category' and 'align' field.
 * @return string The HTML that will display a slider on page
 */
function ciSliderHTMLShortcode($atts) {
    $category = ""; // Defined for the sake of the IDE's error-checking
    $align = '';
    extract( shortcode_atts( array(
                                 'category' => '',
                                 'align' => ''
                             ), $atts ), EXTR_OVERWRITE /* overwrite existing vars */ );

    $size = CI_SIZE_MD;
    if(get_option('full_width_container') || (function_exists('of_get_option') && of_get_option('mlf_demo_site')) ) {
        $size = CI_SIZE_LG;
    }

    return ciGetSliderHTML($category, 10, true, $size, $align);
}

function ciRegisterSliderShortcode() {
    add_shortcode('slider', 'ciSliderHTMLShortcode');
}

add_action( 'init', 'ciRegisterSliderShortcode');



