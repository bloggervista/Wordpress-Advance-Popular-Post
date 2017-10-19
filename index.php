<?php
/*
Plugin Name: ADVANCED POPULAR POST BY SHIRSHAK.
Description: A GOOD WAY TO MAKE Popular Post.
Author: Shirshak Bajgain
Version: 1.0
Text Domain: shirshak
License: 
All rights reserved.



Redistribution and use in source and binary forms, with or without

modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright

      notice, this list of conditions and the following disclaimer.

    * Redistributions in binary form must reproduce the above copyright

      notice, this list of conditions and the following disclaimer in the

      documentation and/or other materials provided with the distribution.

    * Neither the name of the Studio 42 Ltd. nor the

      names of its contributors may be used to endorse or promote products

      derived from this software without specific prior written permission.



THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND

ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED

WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE

DISCLAIMED. IN NO EVENT SHALL "STUDIO 42" BE LIABLE FOR ANY DIRECT, INDIRECT,

INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT

LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR

PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF

LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE

OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF

ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
class My_Popular_Posts extends WP_Widget
{
	private $display_thumb;
    function My_Popular_Posts()
    {
        $settings = array(
            'name' => 'Popular Post / News / Notes',
            'description' => 'Display posts with the most page views.'
        );

        parent::__construct('my_popula_posts', '', $settings);
    }

    public function form($instance)
    {
    	
        // set defaults
        $defaults = array( 'title' => 'Popular Posts', 'max' => '5');
        $instance = wp_parse_args( (array) $instance, $defaults );

        // $instance = the <input> values of the <form>
        extract( $instance );

        // Default settings

        ?>
        <!-- Max number of posts to display -->
        <p>
            <label for="<?php echo $this->get_field_id('max'); ?>">Max number of posts to display:</label>
            <input 
                id="<?php echo $this->get_field_id('max'); ?>"
                name="<?php echo $this->get_field_name('max'); ?>"
                value="<?php echo $max; ?>"
                type="number" min="1" max="20"
            />
        </p>

        <!-- Display post thumbnail? -->
        <p>
            <label for="<?php echo $this->get_field_id('display_thumb'); ?>">Display post thumbnail:</label>
            <input 
                id="<?php echo $this->get_field_id('display_thumb'); ?>"
                name="<?php echo $this->get_field_name('display_thumb'); ?>"
                value="1" 
                <?php if ( $this->display_thumb == 1 ) echo 'checked="checked"'; ?>
                type="checkbox"
            />
        </p>


        <?php if ($this->display_thumb == 1) : ?>
        <!-- Post thumbnail size -->
        <p>
            <label for="<?php echo $this->get_field_id('thumb_width'); ?>">Post thumbnail width:</label>
            <input 
                id="<?php echo $this->get_field_id('thumb_width'); ?>"
                name="<?php echo $this->get_field_name('thumb_width'); ?>"
                value="<?php echo $thumb_width; ?>"
                type="number"
            />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('thumb_height'); ?>">Post thumbnail height:</label>
            <input 
                id="<?php echo $this->get_field_id('thumb_height'); ?>"
                name="<?php echo $this->get_field_name('thumb_height'); ?>"
                value="<?php echo $thumb_height; ?>"
                type="number"
            />
        </p>
         <p>
            <label for="<?php echo $this->get_field_id('thumb_height'); ?>">Post thumbnail height:</label>
            <input 
                id="<?php echo $this->get_field_id('thumb_height'); ?>"
                name="<?php echo $this->get_field_name('thumb_height'); ?>"
                value="<?php echo $thumb_height; ?>"
                type="number"
            />
        </p>
        <?php
        endif; // ($this->display_thumb == 1)
    }

    public function widget($args, $instance)
    {
        extract($args);
        extract($instance);
        global $wp_query;
        if (!empty($wp_query->query_vars['class']) OR !empty($wp_query->query_vars['show_note_class_and_subject']) OR !empty($wp_query->query_vars['class-11']) Or !empty($wp_query->query_vars['class-12'])) {
            $title="Popular Notes";
            $posttype=["class-11","class-12"];
        }else{
            $title="Popular News";
            $posttype="post";
        }

        $popularpost = new WP_Query(array(
            'posts_per_page'    => $max,
            'orderby'           => 'comment_count',
            'order'             => 'DESC',
            'post_type'         => $posttype
        ) );
        // html output  
        echo $before_widget;
            // title
            if (!empty($title)) echo $before_title . $title . $after_title;

            echo '<ul>';
            // content
            if ($popularpost->have_posts()) : while ($popularpost->have_posts()) : $popularpost->the_post();
            ?>
                <li>
                    <?php
                    if ( $this->display_thumb == 1 && has_post_thumbnail() ) {
                        if ( !empty($thumb_width) && !empty($thumb_height) ) {
                            add_image_size( 'widget_thumb', $thumb_width, $thumb_height, true );
                            ?>
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'widget_thumb' );?></a>
                            <?php
                        } else {
                            ?>
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail();?></a>
                            <?php
                        }
                    }
                    ?>

                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </li>
            <?php
            endwhile; endif; wp_reset_query(); // $popularpost loop
            echo '</ul>';

        echo $after_widget;
    }
}

add_action('widgets_init' , 'my_popular_posts');
function my_popular_posts()
{
    register_widget('My_Popular_Posts');
}
?>