document.addEventListener('DOMContentLoaded', function () {
    const sliders = document.querySelectorAll('.mood-slider');

    sliders.forEach(slider => {
        const container = slider.closest('.mood-container');
        if (!container) return;

        const moodText = container.querySelector('.mood-text');
        const moodDescription = container.querySelector('.mood-description');
        const moodCTA = container.querySelector('.cta-btn');
        const emojiBubble = container.querySelector('.mood-emoji-bubble');

        // Parse data from container
        let config = { steps: [] };
        try {
            const rawConfig = container.dataset.config;
            if (rawConfig) {
                config = JSON.parse(rawConfig);
            }
        } catch (e) {
            console.error('Mood Slider: Failed to parse config', e);
        }

        let steps = config.steps;

        // Handle case where steps might be empty
        if (!steps || steps.length === 0) {
            console.warn("Mood Slider: No steps configuration found. Using temporary defaults.");
            // Fallback to prevent broken UI
            steps = [
                { emoji: '😐', text: 'No Data', description: 'Please configure settings.', cta_text: '', cta_link: '#' }
            ];
        }

        function updateMood(value) {
            // Calculate which step corresponds to the current value
            // Evenly divide 0-100 by the number of steps
            const stepCount = steps.length;
            const stepSize = 100 / stepCount;

            // Ensure index is within bounds [0, length-1]
            // We use Math.min to clamp the max index
            let index = Math.floor(value / stepSize);
            if (index >= stepCount) index = stepCount - 1;

            const step = steps[index];
            if (!step) return;

            if (moodText) moodText.innerText = step.text || '';
            if (moodDescription) moodDescription.innerText = step.description || '';

            if (moodCTA) {
                // Check if cta_text exists and is not empty
                const ctaText = step.cta_text ? String(step.cta_text).trim() : '';

                if (ctaText && ctaText !== 'undefined') {
                    moodCTA.innerText = ctaText;
                    moodCTA.style.display = 'inline-block';
                    moodCTA.href = step.cta_link || '#';
                } else {
                    moodCTA.style.display = 'none';
                }
            }
            if (emojiBubble) {
                // Ensure no undefined emoji
                emojiBubble.innerText = step.emoji || '😐';
            }
        }

        slider.addEventListener('input', (e) => {
            const val = parseInt(e.target.value);
            updateMood(val);

            // Sync bubble position
            if (emojiBubble) {
                const val = e.target.value;
                const min = e.target.min ? e.target.min : 0;
                const max = e.target.max ? e.target.max : 100;
                const newVal = Number(((val - min) * 100) / (max - min));
                // Magic numbers to align with native thumb center
                emojiBubble.style.left = `calc(${newVal}% + (${8 - newVal * 0.15}px) - 20px)`;
            }
        });

        // Initialize state
        updateMood(parseInt(slider.value));

        // Trigger input event to set initial bubble position correctly
        slider.dispatchEvent(new Event('input'));
    });
});
