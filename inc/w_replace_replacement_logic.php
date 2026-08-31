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
                // از ستون where_to_replace (که هنگام ذخیره قانون توسط کاربر
                // انتخاب می‌شود) به‌عنوان منبع معتبر برای دسته‌بندی استفاده
                // می‌کنیم، نه از خالی/پر بودن page_id یا post_id. وقتی کاربر
                // گزینه‌ی «All» (همه‌ی پست‌ها/همه‌ی صفحات) را انتخاب می‌کند،
                // page_id/post_id در دیتابیس NULL ذخیره می‌شود؛ اگر دسته‌بندی
                // بر اساس همین NULL بودن انجام شود، قانون به‌اشتباه در دسته‌ی
                // «کل سایت» قرار می‌گیرد و روی پست‌ها و صفحات هر دو اعمال
                // می‌شود. با تکیه بر where_to_replace این مشکل رفع می‌شود.
                $scope = $rule->where_to_replace ?? '';

                if ($scope === 'page') {
                    $this->pageRules[] = $rule;
                } elseif ($scope === 'post') {
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
        // page_id خالی یعنی کاربر گزینه‌ی «All» (همه‌ی صفحات) را انتخاب کرده.
        // در این حالت باید روی همه‌ی صفحات اعمال شود، نه فقط یک صفحه‌ی خاص.
        if (empty($rule->page_id)) {
            $isWooPage = function_exists('is_woocommerce') && is_woocommerce();
            return is_page() || $isWooPage;
        }
        return $this->get_current_page_id() == $rule->page_id;
    }

    private function should_apply_post_rule($rule): bool {
        $isProduct = function_exists('is_product') && is_product();
        if (!is_single() && !$isProduct) {
            return false;
        }

        // post_id خالی یعنی کاربر گزینه‌ی «All» (همه‌ی پست‌ها) را انتخاب کرده.
        if (empty($rule->post_id)) {
            return true;
        }

        return get_the_ID() == $rule->post_id;
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
        // template_redirect runs after the main query has been parsed, so
        // conditional tags (is_page, is_single, is_shop, is_product, ...)
        // are reliable here for both regular WordPress pages and
        // WooCommerce pages. Hooking earlier (e.g. woocommerce_init, which
        // fires on 'init' before the query is resolved) would cause
        // should_apply_replacements() to cache a false negative that then
        // sticks for the rest of the request.
        add_action('template_redirect', [$this, 'apply_Logic'], 1);
        
        add_action('shutdown', function() {
            if (self::$buffer_started && ob_get_level() > 0) {
                @ob_end_flush();
            }
        });
    }
}