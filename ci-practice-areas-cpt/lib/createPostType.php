<?php

// Create the Attorneys custom post type
add_action('init', 'ciCreatePracticeAreas');
function ciCreatePracticeAreas() {
    ciCreateCPT("Practice Area", "Practice Areas", CI_PRACTICE_AREA_TYPE, 'list-view');
}


add_filter('post_updated_messages', 'ciPracticeAreaTypeUpdatedMessages');
function ciPracticeAreaTypeUpdatedMessages($messages) {
    return ciCPTUpdatedMessages($messages, "Practice Area", CI_PRACTICE_AREA_TYPE);
}




