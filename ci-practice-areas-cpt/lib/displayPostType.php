<?php




add_shortcode('practiceareas', 'ciHandlePracticeAreaShortcode');
/**
 * Handles the [practiceareas /] shortcode. Usage:
 *
 * - [practiceareas /]
 * - [practiceareas list /]
 * - [practiceareas max=5 list /]
 * - [practiceareas length=250 columns=4 more /] (Max of 250 chars, in four columns, with a "more" link)
 *
 * @param array $atts
 * @param string $content
 */
function ciHandlePracticeAreaShortcode( $atts, $content="" ) {
    $list = false;
    $max = 1000;
    $length = 250;
    $columns = 4;
    $more = false;
    $mb = 30;
    extract(
        shortcode_atts(
            array(
                'list'    => $list,
                'max'     => $max,
                'length'  => $length,
                'columns' => $columns,
                'more'    => $more,
                'mb' => $mb
            ), ciNormalizeShortcodeAtts($atts), 'practiceareas' ) );

    if( $list ) {
        return ciGetPracticeAreasTitlesList($max);
    } else {
        return ciGetPracticeAreasHTML($max, 3, $length, true, $columns, $more, $mb);
    }
}


function ciGetPracticeAreasTitlesList( $maxAreas ) {
    $practiceAreas = ciGetPostsOfType( MLF_PRACTICE_AREA_TYPE, 1, array(), $maxAreas );

    $out = "<ul class=\"practice-areas\">";
    foreach( $practiceAreas as $practiceArea ) {
        $out .= "<li><a href=\"{$practiceArea['url']}\">{$practiceArea['title']}</a></li>";
    }
    $out .= "</ul>";
    return $out;
}




/**
 * Returns the HTML to display the practice areas
 * @param $numPracticeAreas int The max number of practice areas to display.
 * @param $headingLevel int The "level" of heading to apply to the practice area's title.
 *                          E.g., 2 gives H2, 3 gives H3, etc.
 *                          Use 0 to simply bold the heading.
 * @param $maxCharLength int The maximum length for each practice area's content. If -1, there will be no limit.
 * @param $useImages boolean For posts that have a featured image, should we display that image?
 * @param $columns int The number of columns to format the practice areas into
 * @param $more boolean True if we should use a "Read more." link; false if we should simply link the titles
 * @param $mb int Bottom margin, in px
 * @return string The HTML to be output
 */
if( !function_exists('ciGetPracticeAreasHTML') ) {
    function ciGetPracticeAreasHTML( $numPracticeAreas = 100,
                                      $headingLevel = 3,
                                      $maxCharLength = -1,
                                      $useImages=true,
                                      $columns=1,
                                      $more=false,
                                      $mb = 30 ) {
        if( !function_exists('ciGetPracticeAreaInnerHTML') ) {
            function ciGetPracticeAreaInnerHTML( $practiceArea, $headingLevel, $floatImg="right", $useImages, $more ) {
                $imgClass = "practice-area-img";
                if( $floatImg == "right" ) {
                    $imgClass .= " alignright ml20";
                } else if( $floatImg == "left" ) {
                    $imgClass .= " alignleft mr20";
                }

                $out = "";
                $aHREF = "<a href=\"{$practiceArea['url']}\">";
                if( $useImages && strlen($practiceArea['imgURL']) > 0 ) {
                    $out  .= "    $aHREF<img alt=\"{$practiceArea['title']}\" src=\"{$practiceArea['imgURL']}\" width=\"{$practiceArea['imgWidth']}\" height=\"{$practiceArea['imgHeight']}\" class=\"{$imgClass}\"></a>\n";
                }


                $title = $practiceArea['title'];
                if( !$more ) {
                    $title = "{$aHREF}$title</a>";
                }

                if( $headingLevel > 0 ) {
                    $out .= "    <h{$headingLevel}>$title</h{$headingLevel}>\n";
                } else {
                    $out .= "    <strong>$title</strong>\n";
                }

                $out .= "    {$practiceArea['content']}\n";

                if( $more ) {
                    $out .= "{$aHREF}Learn more...</a>";
                }

                return $out;
            }
        }


        $practiceAreas = ciGetPostsOfType( CI_PRACTICE_AREA_TYPE, $maxCharLength, array(1000,1000), $numPracticeAreas );

        if( count($practiceAreas) == 0 ) {
            return "";
        }

        $divClass = "practice-areas" . ($columns > 1 ? " row mb" . strval(intval($mb)) : "");
        $liClass = "practice-area " . ($columns > 1 ? ciGetColumnClass($columns) : "") ;

        $out = "\n<!-- Practice areas -->\n";
        $out .= "<div class=\"{$divClass}\">";
        if( count($practiceAreas) > 1 ) {
            $out .= "<ul class=\"no-bullet\">\n";
            for( $i = 0; $i < count($practiceAreas); $i++ ) {
                $out .= "<li class=\"{$liClass}\">\n";
                $out .= ciGetPracticeAreaInnerHTML($practiceAreas[$i], $headingLevel, "none", $useImages, $more);
                $out .= "</li>\n";
            }
            $out .= "</ul>\n";
        } else {
            $out .= ciGetPracticeAreaInnerHTML($practiceAreas[0], $headingLevel, "right", $useImages, $more);
        }
        $out .= "</div>";
        return $out;
    }
}


