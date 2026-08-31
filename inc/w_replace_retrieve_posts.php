<?php

class w_replace_retrieve_posts {
    
    //private const POST_STATUS = 'publish';
    private string $post_type = '';
    private array $posts = [];
    
    public function pages_dropdown(string $post_type): array {
        $this->post_type = $post_type;
        
        $args = [
            'post_type' => $post_type,
            //'post_status' => self::POST_STATUS,
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ];
        
        // دریافت پست‌ها
        $pages = $post_type === 'page' 
            ? get_pages($args) 
            : get_posts($args);
        
        // تبدیل با arrow function (PHP 7.4+)
        $this->posts = array_map(
            fn($page) => [
                'ID' => $page->ID,
                'title' => $page->post_title,
            ],
            $pages
        );
        
        return $this->posts;
    }
}
