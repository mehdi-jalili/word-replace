jQuery(document).ready(function ($) {
    $('#addNewRuleBtn').on('click', async function(event) {
        event.preventDefault();
        var $btn = $(this);
        $btn.html('<span class="spinner is-active"></span>');
        let target_word = $('#target_word').val();
        let word_replace_with = $('#word_replace_with').val();
        let where_to_replace_rule = $('#where_to_replace_rule').val();
        let page_id = $('#page_replace_rule_dropdown').val();
        let page_name = $('#page_replace_rule_dropdown option:selected').text();
        let post_id = $('#post_replace_rule_dropdown').val();
        let post_name = $('#post_replace_rule_dropdown option:selected').text();
        let nonce = w_replace_ajax.nonce_add_new;
    
        if (!target_word || !word_replace_with) {
            $('#notice-container').html('<div class="notice notice-error is-dismissible"><p>Please Fill All the Fields</p></div>');
            $btn.html('Add New Rule');
            return;
        }
    
        try {
            const response = await $.ajax({
                url: w_replace_ajax.ajax_url,
                type: 'post',
                data: {
                    action: 'add_new_rule',
                    target_word,
                    word_replace_with,
                    where_to_replace_rule,
                    page_id,
                    page_name,
                    post_id,
                    post_name,
                    w_replace_add_new_rule_nonce: nonce
                }
            });
    
            if (response.success) {
                $('#notice-container').html('<div class="notice notice-success is-dismissible"><p>Rule Has Been Added</p></div>');
            } else {
                if (response.data === 'repeat') {
                    $('#notice-container').html('<div class="notice notice-error is-dismissible"><p>Repeated word detected</p></div>');
                } else {
                    $('#notice-container').html('<div class="notice notice-error is-dismissible"><p>ERROR: failed to get response</p></div>');
                }
            }
            $btn.html('Add New Rule');
        } catch (error) {
            $('#notice-container').html('<div class="notice notice-error is-dismissible"><p>Error occurred</p></div>');
            $btn.html('Add New Rule');
        }
    });    

    $('#deleteRuleBtn').on('click', async function(event) {
        event.preventDefault();
    
        var $btn = $(this);
        $btn.html('<span class="spinner is-active"></span>');
    
        const rowId = $btn.val();
        let nonce = w_replace_ajax.nonce_delete;
    
        try {
            const response = await $.ajax({
                url: w_replace_ajax.ajax_url,
                type: 'post',
                data: {
                    action: 'delete_rule',
                    rowId,
                    w_replace_delete_rule_nonce: nonce
                }
            });
    
            if (response.success) {
                $btn.html('Delete');
                replaceModalDisplayNone();
                $('#notice-container').html('<div class="notice notice-success is-dismissible"><p>Rule has been deleted</p></div>');

                // Remove the deleted row directly from the DOM instead of
                // re-fetching the page. Re-fetching location.href via
                // $.load() issues a cacheable GET request to the exact
                // same admin URL used for normal navigation, so browsers
                // can serve a stale cached response on the next load
                // (only a hard refresh would bypass it). Updating the DOM
                // directly avoids the extra request and the caching issue
                // entirely.
                var $row = $('#the-list').find('button.delete-rule-word[id="' + rowId + '"]').closest('tr');
                $row.fadeOut(200, function () {
                    $row.remove();

                    if ($('#the-list tr').length === 0) {
                        $('#the-list').html(
                            '<tr><td colspan="6" style="text-align:center;"><b>No records found</b></td></tr>'
                        );
                    }
                });
            } else {
                $btn.html('Delete');
                replaceModalDisplayNone();
                $('#notice-container').html('<div class="notice notice-error is-dismissible"><p>Error: Delete was not successful</p></div>');
            }
        } catch (error) {
            console.error(error); // Log the error for debugging
            $btn.html('Delete');
            replaceModalDisplayNone();
            $('#notice-container').html('<div class="notice notice-error is-dismissible"><p>Error occurred</p></div>');
        } finally {
            $btn.html('Delete');
            replaceModalDisplayNone();
        }
    });
});

