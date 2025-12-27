jQuery(document).ready(function ($) {
    // Emoji Picker Logic
    $(document).on('click', '.ms-emoji-input', function (e) {
        e.stopPropagation();
        $('.ms-emoji-grid').hide(); // Close others
        $(this).siblings('.ms-emoji-grid').toggle();
    });

    $(document).on('click', '.ms-emoji-option', function () {
        var emoji = $(this).text();
        var input = $(this).closest('.ms-emoji-picker-wrapper').find('.ms-emoji-input');
        input.val(emoji);
        $(this).closest('.ms-emoji-grid').hide();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.ms-emoji-picker-wrapper').length) {
            $('.ms-emoji-grid').hide();
        }
    });

    // Add Step
    $('#ms-add-step').on('click', function () {
        var count = $('.ms-step-item').length;
        var template = `
            <div class="ms-step-item">
                <div class="ms-step-header">
                    <span class="ms-step-count">Step ${count + 1}</span>
                    <button type="button" class="button ms-remove-step">Remove</button>
                </div>
                <div class="ms-step-body">
                    <div class="ms-emoji-picker-wrapper">
                        <label>Emoji</label>
                        <input type="text" name="ms_mood_steps[${count}][emoji]" value="🙂" class="ms-emoji-input" readonly />
                        <div class="ms-emoji-grid" style="display:none;">
                            ${getEmojiOptions()}
                        </div>
                    </div>
                    <div class="ms-flex-grow">
                        <label>Mood Title</label>
                        <input type="text" name="ms_mood_steps[${count}][text]" value="" class="widefat" />
                    </div>
                    <div class="ms-full-width">
                        <label>Description</label>
                        <textarea name="ms_mood_steps[${count}][description]" class="widefat" rows="2"></textarea>
                    </div>
                    <div class="ms-half-width">
                        <label>CTA Text</label>
                        <input type="text" name="ms_mood_steps[${count}][cta_text]" value="" class="widefat" />
                    </div>
                    <div class="ms-half-width">
                        <label>CTA Link</label>
                        <input type="text" name="ms_mood_steps[${count}][cta_link]" value="" class="widefat" />
                    </div>
                </div>
            </div>
        `;
        $('#ms-steps-wrapper').append(template);
    });

    // Remove Step
    $(document).on('click', '.ms-remove-step', function () {
        $(this).closest('.ms-step-item').remove();
        // Recalculate IDs could be nice here but not strictly required for saving if using PHP array append logic, but WP settings API relies on index.
        // For simple usage, we just remove. A page refresh helps re-index visual count.
    });

    function getEmojiOptions() {
        var emojis = ['😔', '😫', '😐', '🙂', '😊', '🥰', '😎', '🤔', '😴', '😭', '😡', '🥳', '😇', '🤢', '🤮', '🤧', '🤒', '🤕', '🤑', '🤠'];
        var html = '';
        emojis.forEach(function (e) {
            html += '<span class="ms-emoji-option">' + e + '</span>';
        });
        return html;
    }
});
