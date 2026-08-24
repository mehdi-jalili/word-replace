<<<<<<< HEAD
<?php

class w_replace_replacement_logic {
    private $rules;
    private $entireWebsiteRules = [];
    private $postRules = [];
    private $pageRules = [];
    private static $replacements = [];
    private static $buffer_started = false;
    private static $is_processing = false;
    private static $should_apply_cache = null; // کش برای بهبود عملکرد
    
    public function __construct() {
        $this->rules = w_replace_models::get_rules();
        $this->re_order_rules_logic();
        $this->w_replace_init();
    }
    
    public function re_order_rules_logic() {
        if ($this->rules !== null && is_array($this->rules)) {
            foreach ($this->rules as $rule) {
                if (!empty($rule->page_id) && $rule->page_id > 0) {
                    $this->pageRules[] = $rule;
                } elseif (!empty($rule->post_id) && $rule->post_id > 0) {
                    $this->postRules[] = $rule;
                } else {
                    $this->entireWebsiteRules[] = $rule;
                }
            }
        }
    }
    
    public function apply_Logic() {
        if (self::$is_processing) {
            return;
        }
        self::$is_processing = true;
        
        try {
            if ($this->should_apply_replacements()) {
                $this->apply_rules($this->entireWebsiteRules, 'entire website');
                $this->apply_rules($this->pageRules, 'page');
                $this->apply_rules($this->postRules, 'post');
            }
        } finally {
            self::$is_processing = false;
        }
    }

    private function should_apply_replacements(): bool {
        // کش کردن نتیجه برای جلوگیری از محاسبات تکراری
        if (self::$should_apply_cache !== null) {
            return self::$should_apply_cache;
        }
        
        $isWooPage = function_exists('is_woocommerce') && is_woocommerce();
        $isProduct = function_exists('is_product') && is_product();
        
        return self::$should_apply_cache = match (true) {
            !empty($this->entireWebsiteRules) => true,
            !empty($this->pageRules) && (is_page() || $isWooPage) => true,
            !empty($this->postRules) && (is_single() || $isProduct) => true,
            default => false,
        };
    }

    private function apply_rules($rules, $scope) {
        if (empty($rules)) return;

        foreach ($rules as $rule) {
            $should_apply = match ($scope) {
                'entire website' => true,
                'page' => $this->should_apply_page_rule($rule),
                'post' => $this->should_apply_post_rule($rule),
                default => false,
            };
            
            if ($should_apply) {
                $this->replace_content($rule->target_word, $rule->word_replace);
            }
        }
    }

    private function should_apply_page_rule($rule): bool {
        return $this->get_current_page_id() == $rule->page_id;
    }

    private function should_apply_post_rule($rule): bool {
        if (is_single() || (function_exists('is_product') && is_product())) {
            return get_the_ID() == $rule->post_id;
        }
        return false;
    }

    private function get_current_page_id(): int {
        static $cache = null; // کش کردن ID صفحه
        
        if ($cache !== null) {
            return $cache;
        }
        
        $current_id = get_queried_object_id();
        
        $wooPages = [
            'is_shop' => 'shop',
            'is_cart' => 'cart',
            'is_checkout' => 'checkout',
            'is_account_page' => 'myaccount'
        ];
        
        foreach ($wooPages as $func => $page) {
            if (function_exists($func) && $func()) {
                return $cache = wc_get_page_id($page);
            }
        }
        
        return $cache = $current_id;
    }

    private function replace_content($oldContent, $newContent) {
        // جلوگیری از اضافه شدن جایگزینی تکراری
        $key = md5($oldContent . $newContent);
        if (!isset(self::$replacements[$key])) {
            self::$replacements[$key] = [
                'old' => $oldContent,
                'new' => $newContent
            ];
        }
        
        if (!self::$buffer_started) {
            $this->start_buffer();
        }
    }
    
    private function start_buffer() {
        static $started = false;
        if ($started) {
            return;
        }
        $started = true;
        
        add_action('wp_loaded', function() {
            if (!self::$buffer_started && !empty(self::$replacements)) {
                ob_start([$this, 'apply_all_replacements']);
                self::$buffer_started = true;
            }
        }, 1);
        
        add_action('wp_head', function() {
            if (!self::$buffer_started && !empty(self::$replacements)) {
                ob_start([$this, 'apply_all_replacements']);
                self::$buffer_started = true;
            }
        }, 999);
    }
    
    private function apply_all_replacements($buffer) {
        if (empty(self::$replacements)) {
            return $buffer;
        }
        
        // ✅ بهینه: استفاده از str_replace با آرایه
        $search = array_column(self::$replacements, 'old');
        $replace = array_column(self::$replacements, 'new');
        
        return str_replace($search, $replace, $buffer);
    }

    public function w_replace_init() {
        add_action('template_redirect', [$this, 'apply_Logic'], 1);
        
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_init', [$this, 'apply_Logic'], 1);
            add_action('woocommerce_before_main_content', [$this, 'apply_Logic'], 1);
        }
        
        add_action('shutdown', function() {
            if (self::$buffer_started && ob_get_level() > 0) {
                @ob_end_flush();
            }
        });
    }
}
=======
<?php

class w_replace_replacement_logic {
    private $rules;
    private $entireWebsiteRules = [];
    private $postRules = [];
    private $pageRules = [];
    
    public function __construct() {

        // $rule = new w_replace_models();
        // $rule->check_table();
        $this->rules = w_replace_models::get_rules();
        $this->re_order_rules_logic();
    }
    
    public function re_order_rules_logic() {
        if($this->rules !== null){
            foreach ($this->rules as $rule) {
                if ($rule->page_id !== null) {
                    $this->pageRules[] = $rule;
                } elseif ($rule->post_id !== null) {
                    $this->postRules[] = $rule;
                } else {
                    $this->entireWebsiteRules[] = $rule;
                }
            }
        }
    }
    

    public function apply_Logic() {
        $this->apply_rules($this->entireWebsiteRules, 'entire website');
        $this->apply_rules($this->pageRules, 'page');
        $this->apply_rules($this->postRules, 'post');
    }

    private function apply_rules($rules, $scope) {
        foreach ($rules as $rule) {
            if ($scope === 'entire website' || ($scope === 'page' && is_page($rule->page_id)) || ($scope === 'post' && is_single($rule->post_id))) {
                $this->replace_content($rule->target_word, $rule->word_replace);
            }
        }
    }

    private function replace_content($oldContent, $newContent) {
        ob_start(function ($buffer) use ($oldContent, $newContent) {
            $replacedContent = str_replace($oldContent, $newContent, $buffer);
            return $replacedContent;
        });
    }

    public function init() {
        add_action('template_redirect', [$this, 'apply_logic']);
    }
}
>>>>>>> a17dcb73cc217c0ca88508842ad816bfa013fb83
