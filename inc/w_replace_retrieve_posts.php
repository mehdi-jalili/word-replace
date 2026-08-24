<<<<<<< HEAD
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
=======
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
>>>>>>> a17dcb73cc217c0ca88508842ad816bfa013fb83
