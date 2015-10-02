<?php
function ciPracticeAreasRegisterWidget() {
    register_widget( 'CIPracticeAreasWidget' );
}
add_action( 'widgets_init', 'ciPracticeAreasRegisterWidget' );



/**
 * Practice Areas Widget
 */
if( !class_exists('CIPracticeAreasWidget') ) {
    class CIPracticeAreasWidget extends WP_Widget {
        private $fields = array(
            'title'            => 'Title (optional):',
            'list'             => 'Display as list of links only?',
            'maxPracticeAreas' => 'Max number of practice areas to display:',
            'maxCharLength'    => 'Max length of practice area descriptions:'
        );
        private $className = 'CIPracticeAreasWidget';


        function __construct() {
            $widget_ops = array( 'classname' => $this->className, 'description' => __( 'Displays a list of your practice areas', CI_TEXT_DOMAIN ) );

            parent::__construct( $this->className, __( 'Practice Areas', CI_TEXT_DOMAIN ), $widget_ops );
            $this->alt_option_name = $this->className;

            add_action( 'save_post', array( &$this, 'flush_widget_cache' ) );
            add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
            add_action( 'switch_theme', array( &$this, 'flush_widget_cache' ) );
        }

        function widget( $args, $instance ) {
            $cache = wp_cache_get( $this->className, 'widget' );

            if( !is_array( $cache ) ) {
                $cache = array();
            }

            if( !isset($args['widget_id']) ) {
                $args['widget_id'] = null;
            }

            if( isset($cache[$args['widget_id']]) ) {
                echo $cache[$args['widget_id']];

                return;
            }

            ob_start();
            extract( $args, EXTR_SKIP );

            $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

            echo $before_widget;

            if( $title ) {
                echo $before_title, $title, $after_title;
            }
            if( isset($instance['list']) ) {
                echo ciGetPracticeAreasTitlesList($instance['maxPracticeAreas']);
            } else {
                echo ciGetPracticeAreasHTML($instance['maxPracticeAreas'], 0, $instance['maxCharLength']);
            }
            echo $after_widget;

            $cache[$args['widget_id']] = ob_get_flush();
            wp_cache_set( $this->className, $cache, 'widget' );
        }

        function update( $new_instance, $old_instance ) {
            $instance = array_map( 'strip_tags', $new_instance );

            $this->flush_widget_cache();

            $alloptions = wp_cache_get( 'alloptions', 'options' );

            if( isset($alloptions[$this->className]) ) {
                delete_option( $this->className );
            }

            return $instance;
        }

        function flush_widget_cache() {
            wp_cache_delete( $this->className, 'widget' );
        }

        function form( $instance ) {
            foreach( $this->fields as $name => $label ) {
                ${$name} = isset($instance[$name]) ? esc_attr( $instance[$name] ) : '';

                $typeSpecificStuff = 'value="' . ${$name} . '" ';
                if( $name == 'list' ) {
                    $typeSpecificStuff .= 'type="checkbox"';
                    if( isset($instance[$name]) ) {
                        $typeSpecificStuff .= 'checked';
                    }
                } elseif( $name == 'maxPracticeAreas' || $name == 'maxCharLength' ) {
                    $typeSpecificStuff .= 'type="number" min="1" max="1000" step="1"';
                } else {
                    $typeSpecificStuff .= "type=\"text\"";
                } ?>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>"><?php _e( "{$label}", CI_TEXT_DOMAIN ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( $name ) ); ?>"
                           value="<?php echo ${$name}; ?>" <?php echo $typeSpecificStuff; ?>>
                </p> <?php
            }
        }
    }
}


