<?php


/**
 * @param $maxNumEmployees int Maximum number of employees to return
 * @param $maxCharLength int The maximum length of the content, in characters
 * @return array All the employee data you need. Keys are 'content', 'title', 'url', 'imgURL', 'imgWidth', and 'imgHeight'
 */
if( !function_exists('ciGetAllStaff') ) {
    function ciGetAllStaff( $maxNumEmployees = 100, $maxCharLength = -1 ) {
        $query = new WP_Query('showposts=' . $maxNumEmployees . '&post_type=' . CI_STAFF_TYPE);

        $employeeArray = array();
        while( $query->have_posts() ) {
            $query->next_post();

            $attachment = wp_get_attachment_image_src( get_post_thumbnail_id($query->post->ID), CI_STAFF_IMG );

            $maybeExcerpt = has_excerpt($query->post->ID) ? $query->post->post_excerpt : $query->post->post_content;

            $employeeArray[] = array(
                'id' => $query->post->ID,
                'content' => ciFilterToMaxCharLength($maybeExcerpt, $maxCharLength),
                'fullContent' => $query->post->post_content,
                'title' => $query->post->post_title,
                'imgURL' => ($attachment ? $attachment[0] : ''),
                'imgWidth' => ($attachment ? $attachment[1] : -1),
                'imgHeight' => ($attachment ? $attachment[2] : -1),
                'url' => get_permalink($query->post->ID),
                'socialURLs' => (function_exists('getStaffSocialURLs') ? getStaffSocialURLs($query->post->ID) : array())
            );
        }

        wp_reset_postdata();
        return $employeeArray;
    }
}


/**
 * Returns the HTML to display an image+text slider
 * @param $employeesPerRow int Number of employees to print per row (in columns)
 * @param $numEmployees int The max number of employees to display.
 * @param $headingLevel int The "level" of heading to apply to the employee's name. E.g., 2 gives H2, 3 gives H3, etc.
 * @param $maxCharLength int The maximum length for each employee's content. If -1, there will be no limit.
 * @param $listOnly boolean True if we should return a list of names only; false if we should show images + excerpt
 * @return string The HTML to be output
 */
if( !function_exists('ciGetEmployeesHTML') ) {
    function ciGetEmployeesHTML( $employeesPerRow = 1, $numEmployees = 100, $headingLevel = 3, $maxCharLength = -1, $listOnly = false ) {
        function getEmployeeInnerHTML( $employee, $headingLevel, $floatImg="right", $listOnly) {
            $imgClass = "employee-img";
            if( $floatImg == "right" ) {
                $imgClass .= " alignright ml20";
            } else if( $floatImg == "left" ) {
                $imgClass .= " alignleft mr20";
            }

            $out = "";
            if( strlen ($employee['imgURL']) > 0 ) {
                $out  .= "    <a href=\"{$employee['url']}\"><img alt=\"{$employee['title']}\" src=\"{$employee['imgURL']}\" width=\"{$employee['imgWidth']}\" height=\"{$employee['imgHeight']}\" class=\"{$imgClass}\" itemprop=\"image\"></a>\n";
            }

            $a = "<a href=\"{$employee['url']}\" itemprop=\"name\">{$employee['title']}</a>";
            if( $listOnly ) {
                return $a;
            }

            $out .= "    <h{$headingLevel}>{$a}</h{$headingLevel}>\n";
            $out .= "    {$employee['content']}\n";
            $out .= "";
            return $out;
        }


        $employees = ciGetAllStaff( $numEmployees, $maxCharLength );

        if( count($employees) == 0 ) {
            return "";
        }

        $divClass = "employees";
        $liClass = "employee";
        if( $employeesPerRow > 1 ) {
            $divClass .= " row";
            $colWidth = 12 / $employeesPerRow;
            $liClass .= " col-sm-{$colWidth}";
        }


        $out = "<div class=\"{$divClass}\">";
        if( count($employees) > 1 ) {
            $out .= "<ul>\n";
            for( $i = 0; $i < count($employees); $i++ ) {
                $out .= "<li class=\"{$liClass}\" itemscope itemtype=\"http://schema.org/Person\">\n";
                $out .= getEmployeeInnerHTML($employees[$i], $headingLevel, "none", $listOnly);
                $out .= "</li>\n";
            }
            $out .= "</ul>\n";
        } else {
            $out .= getEmployeeInnerHTML($employees[0], $headingLevel, "right", $listOnly);
        }
        $out .= "</div>";
        return $out;
    }
}


/**
 * Wrapper for the getSliderHTML() function, to be used by the Wordpress Shortcode API
 * @param $atts array containing optional 'category' field.
 * @return string The HTML that will display a slider on page
 */
if( !function_exists('ciEmployeeHTMLShortcode') ) {
    function ciEmployeeHTMLShortcode($atts) {
        $columns = 1; // Defined for the sake of the IDE's error-checking
        $length = 250;
        $list = false;
        $number = 100;
        extract(
            shortcode_atts(
                array(
                    'columns' => 1,
                    'length'  => 250,
                    'number'  => $number,
                    'list'    => $list
                ), ciNormalizeShortcodeAtts($atts) ), EXTR_OVERWRITE /* overwrite existing vars */ );

        return ciGetEmployeesHTML(intval($columns), $number, 3, intval($length), $list);
    }
}

if( !function_exists('ciRegisterEmployeeShortcode') ) {
    function ciRegisterEmployeeShortcode() {
        add_shortcode('staff', 'ciEmployeeHTMLShortcode');
    }
}

add_action( 'init', 'ciRegisterEmployeeShortcode');




 