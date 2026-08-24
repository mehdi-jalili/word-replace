<?php

class w_replace_models {

    private const TABLE_PREFIX = 'w_replace_rules';
    private const CACHE_KEY = 'w_replace_rules_all';
    private const CACHE_GROUP = 'w_replace';
    private const CACHE_TTL = HOUR_IN_SECONDS;

    private string $table_name;
    private wpdb $wpdb;


    public function __construct() {
        global $wpdb;

        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . self::TABLE_PREFIX;
    }


    /**
     * Check whether the database table exists.
     *
     * @return bool True if the table exists or was successfully created.
     */
    public function check_table(): bool {
        if ($this->table_exists()) {
            return true;
        }

        return $this->create_table();
    }


    /**
     * Check whether the plugin database table exists.
     *
     * SHOW TABLES is a direct database query by nature.
     * The result is not cached because this check is only performed
     * when the rules cache is unavailable or table creation is requested.
     *
     * @return bool True if the table exists.
     */
    private function table_exists(): bool {
        global $wpdb;

        $table_exists = $wpdb->get_var(
            $wpdb->prepare("SHOW TABLES LIKE %s", $wpdb->esc_like($this->table_name))
        ) === $this->table_name;
        
        if (!$table_exists) {
            $this->create_table();
        }table_name;
    }


    /**
     * Create the plugin database table.
     *
     * @return bool True if the table was successfully created/updated.
     */
    public function create_table(): bool {
        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            target_word varchar(255) NOT NULL,
            word_replace varchar(255) NOT NULL,
            where_to_replace varchar(5) NOT NULL,
            page_id int(6) DEFAULT NULL,
            page_name varchar(255) DEFAULT NULL,
            post_id int(6) DEFAULT NULL,
            post_name varchar(255) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY idx_page_id (page_id),
            KEY idx_post_id (post_id),
            KEY idx_where_to_replace (where_to_replace)
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        try {
            dbDelta($sql);

            // The table now exists, so invalidate any stale table-existence cache
            // if one is added in the future.
            $this->clear_cache();

            return $this->table_exists();
        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * Add a new replacement rule.
     *
     * @param array $new_rule_args Rule data.
     * @return bool True on success.
     */
    public function set_new_rule(array $new_rule_args): bool {
        if (
            empty($new_rule_args['target_word']) ||
            empty($new_rule_args['word_replace_with'])
        ) {
            return false;
        }

        $data = [
            'target_word'      => sanitize_text_field($new_rule_args['target_word']),
            'word_replace'     => sanitize_text_field($new_rule_args['word_replace_with']),
            'where_to_replace' => sanitize_text_field(
                $new_rule_args['where_to_replace_rule'] ?? ''
            ),
            'page_id'          => !empty($new_rule_args['page_id'])
                ? (int) $new_rule_args['page_id']
                : null,
            'page_name'        => !empty($new_rule_args['page_name'])
                ? sanitize_text_field($new_rule_args['page_name'])
                : null,
            'post_id'          => !empty($new_rule_args['post_id'])
                ? (int) $new_rule_args['post_id']
                : null,
            'post_name'        => !empty($new_rule_args['post_name'])
                ? sanitize_text_field($new_rule_args['post_name'])
                : null,
        ];

        $format = [
            '%s',
            '%s',
            '%s',
            '%d',
            '%s',
            '%d',
            '%s',
        ];

        $result = $this->wpdb->insert(
            $this->table_name,
            $data,
            $format
        );

        if ($result === false) {
            return false;
        }

        // Clear cache only after a successful database operation.
        $this->clear_cache();

        return true;
    }


    /**
     * Delete a replacement rule.
     *
     * @param int $row_id Rule ID.
     * @return bool True if a rule was deleted.
     */
    public static function delete_rule(int $row_id): bool {
        global $wpdb;

        $table_name = $wpdb->prefix . self::TABLE_PREFIX;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $result = $wpdb->delete(
            $table_name,
            ['id' => $row_id],
            ['%d']
        );

        if ($result === false) {
            return false;
        }

        if ($result > 0) {
            wp_cache_delete(
                self::CACHE_KEY,
                self::CACHE_GROUP
            );

            return true;
        }

        return false;
    }


    /**
     * Get all replacement rules.
     *
     * Uses WordPress object cache to avoid querying the database
     * on every request.
     *
     * @return array List of replacement rules.
     */
    public static function get_rules(): array {
        $rules = wp_cache_get(
            self::CACHE_KEY,
            self::CACHE_GROUP
        );

        if ($rules !== false) {
            return is_array($rules) ? $rules : [];
        }

        $rules = self::fetch_rules_from_db();

        if ($rules !== null) {
            wp_cache_set(
                self::CACHE_KEY,
                $rules,
                self::CACHE_GROUP,
                self::CACHE_TTL
            );

            return $rules;
        }

        return [];
    }


    /**
     * Fetch replacement rules directly from the database.
     *
     * The result is cached by get_rules(), so this method itself
     * does not need a second cache layer.
     *
     * @return array|null Rules or null on database error.
     */
    private static function fetch_rules_from_db(): ?array {
        global $wpdb;

        $table_name = $wpdb->prefix . self::TABLE_PREFIX;

        $table_exists = $wpdb->get_var(
            $wpdb->prepare(
                'SHOW TABLES LIKE %s',
                $wpdb->esc_like($table_name)
            )
        ) === $table_name;

        if (!$table_exists) {
            $instance = new self();

            if (!$instance->create_table()) {
                return null;
            }

            return [];
        }

        // The result of this query is cached by get_rules().
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $rules = $wpdb->get_results(
            $wpdb->prepare(
                'SELECT * FROM %i ORDER BY id DESC',
                $table_name
            )
        );

        if ($wpdb->last_error) {
            return null;
        }

        return $rules ?? [];
    }


    /**
     * Clear cached replacement rules.
     *
     * @return void
     */
    private function clear_cache(): void {
        wp_cache_delete(
            self::CACHE_KEY,
            self::CACHE_GROUP
        );
    }


    /**
     * Flush this plugin's cache group.
     *
     * @return void
     */
    public static function flush_cache(): void {
        wp_cache_delete(
            self::CACHE_KEY,
            self::CACHE_GROUP
        );

        wp_cache_flush_group(self::CACHE_GROUP);
    }
}
