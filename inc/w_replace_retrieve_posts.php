<?php

class w_replace_retrieve_posts {

    public $post_type;

    public function pages_dropdown($post_type) {

        $this->$post_type = $post_type;

        if ($post_type === 'page') {
            $pages = get_pages(array(
            'post_type' => $post_type,
            'post_status' => 'publish'
            ));
            } else {
            $pages = get_posts(array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'numberposts' => -1
            ));
        }

        foreach ($pages as $page) {
            $this->post_type[] = [
                'ID' => $page->ID,
                'title' => $page->post_title
            ];
        }
        return $this->post_type;
    }
}
