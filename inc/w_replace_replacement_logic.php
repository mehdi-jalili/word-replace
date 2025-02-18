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
