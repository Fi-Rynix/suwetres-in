/* Questionnaire Wizard Controller */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('questionnaire-form');
    if (!form) return;

    const progressBar = document.getElementById('survey-progress');
    const progressText = document.getElementById('progress-text');
    const errorBox = document.getElementById('validation-error-box');
    const topHeader = document.getElementById('survey-top-header');
    const progressHeader = document.getElementById('step-progress-header');

    let currentStep = 0; // Starts at Intro Screen (Step 0)

    // Field names mapped to each step for validation
    const stepFields = {
        1: ['jam_tidur', 'screen_time'],
        2: ['kualitas_tidur', 'mood_rendah', 'kepuasan_hidup', 'regulasi_emosi'],
        3: ['kecemasan', 'kewalahan', 'sulit_rileks', 'gejala_fisik_stres'],
        4: ['kelelahan_mental', 'gangguan_konsentrasi', 'overthinking', 'kehilangan_motivasi', 'beban_mental'],
        5: ['dampak_screen_time', 'dampak_emosi']
    };

    // Helper to get checked radio value
    function getRadioValue(fieldName) {
        const radios = document.getElementsByName(fieldName);
        for (let i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                return radios[i].value;
            }
        }
        return '-';
    }

    // Validate if current step's questions are completely answered
    function validateCurrentStep() {
        if (currentStep === 0 || currentStep === 6) return true; // Intro and summary need no validation
        
        const fields = stepFields[currentStep];
        if (!fields) return true;

        let allValid = true;

        fields.forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                // Number input
                if (input.value.trim() === '') {
                    allValid = false;
                }
            } else {
                // Radio buttons
                const value = getRadioValue(field);
                if (value === '-') {
                    allValid = false;
                }
            }
        });

        return allValid;
    }

    // Update Step progress indicator and percentage bar
    function updateProgress() {
        if (currentStep < 1 || currentStep > 5) return;

        // Step titles mapping
        const stepTitles = {
            1: "Bagian 1 dari 5: AKTIVITAS HARIAN",
            2: "Bagian 2 dari 5: MOOD & KESEJAHTERAAN EMOSIONAL",
            3: "Bagian 3 dari 5: KECEMASAN & TINGKAT STRES",
            4: "Bagian 4 dari 5: BURNOUT & KELELAHAN MENTAL",
            5: "Bagian 5 dari 5: DAMPAK AKTIVITAS DIGITAL"
        };

        const activeTitleEl = document.getElementById('active-step-title');
        if (activeTitleEl) {
            activeTitleEl.textContent = stepTitles[currentStep];
        }

        // Calculate and set percentage
        const percentage = currentStep * 20;
        if (progressBar) progressBar.style.width = percentage + '%';
        if (progressText) progressText.textContent = percentage + '% Selesai';

        // Adjust bar colors dynamically based on percentage
        if (progressBar) {
            if (percentage < 40) {
                progressBar.style.backgroundColor = 'var(--primary)'; // Pink
            } else if (percentage < 80) {
                progressBar.style.backgroundColor = 'var(--secondary)'; // Cyan
            } else {
                progressBar.style.backgroundColor = 'var(--green)'; // Green
            }
        }

        // Highlight step indicator tabs
        const stepItems = document.querySelectorAll('.step-progress-item');
        stepItems.forEach(item => {
            const stepNum = parseInt(item.getAttribute('data-progress-step'), 10);
            item.classList.remove('active', 'completed');
            if (stepNum === currentStep) {
                item.classList.add('active');
            } else if (stepNum < currentStep) {
                item.classList.add('completed');
            }
        });
    }

    // Populate mini summary grid on completion screen (Step 6)
    function populateSummary() {
        const jamTidur = document.getElementById('jam_tidur')?.value || '-';
        const screenTime = document.getElementById('screen_time')?.value || '-';
        const moodRendah = getRadioValue('mood_rendah');
        const kecemasan = getRadioValue('kecemasan');
        const overthinking = getRadioValue('overthinking');

        const summaryJamTidur = document.getElementById('summary-jam-tidur');
        if (summaryJamTidur) summaryJamTidur.textContent = jamTidur + " Jam";

        const summaryScreenTime = document.getElementById('summary-screen-time');
        if (summaryScreenTime) summaryScreenTime.textContent = screenTime + " Jam";

        const summaryMoodRendah = document.getElementById('summary-mood-rendah');
        if (summaryMoodRendah) summaryMoodRendah.textContent = moodRendah + "/10";

        const summaryKecemasan = document.getElementById('summary-kecemasan');
        if (summaryKecemasan) summaryKecemasan.textContent = kecemasan + "/10";

        const summaryOverthinking = document.getElementById('summary-overthinking');
        if (summaryOverthinking) summaryOverthinking.textContent = overthinking + "/10";
    }

    // Transition steps
    function showStep(step, direction) {
        // Hide all steps and remove animations
        const steps = document.querySelectorAll('.wizard-step');
        steps.forEach(s => {
            s.classList.remove('active', 'fade-slide-next', 'fade-slide-prev');
        });

        // Hide validation warning box
        if (errorBox) errorBox.style.display = 'none';

        // Update step state
        currentStep = step;

        // Toggle layout visibility for headers
        if (currentStep >= 1 && currentStep <= 5) {
            if (topHeader) topHeader.style.display = 'flex';
            if (progressHeader) progressHeader.style.display = 'block';
            updateProgress();
        } else {
            if (topHeader) topHeader.style.display = 'none';
            if (progressHeader) progressHeader.style.display = 'none';
        }

        // Show active step container
        const targetStep = document.querySelector(`.wizard-step[data-step="${currentStep}"]`);
        if (targetStep) {
            targetStep.classList.add('active');
            if (direction === 'next') {
                targetStep.classList.add('fade-slide-next');
            } else if (direction === 'prev') {
                targetStep.classList.add('fade-slide-prev');
            }
        }

        // Auto Scroll to Top
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

        // Populate summary elements if entering Completion Screen (Step 6)
        if (currentStep === 6) {
            populateSummary();
        }

        // Reinitialize Lucide Icons for dynamic content
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    function nextStep() {
        if (validateCurrentStep()) {
            showStep(currentStep + 1, 'next');
        } else {
            // Show alert box & animate shake
            if (errorBox) {
                errorBox.style.display = 'flex';
                errorBox.style.animation = 'none';
                errorBox.offsetHeight; // force reflow
                errorBox.style.animation = null;
                errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }

    function prevStep() {
        if (currentStep > 0) {
            showStep(currentStep - 1, 'prev');
        }
    }

    // Event listeners for Navigation buttons
    form.addEventListener('click', function(e) {
        const target = e.target;
        
        // Next button click
        if (target.classList.contains('btn-next') || target.closest('.btn-next')) {
            e.preventDefault();
            nextStep();
        }
        
        // Previous button click
        if (target.classList.contains('btn-prev') || target.closest('.btn-prev')) {
            e.preventDefault();
            prevStep();
        }
    });

    // Start button on Intro screen (Step 0)
    const btnStart = document.getElementById('btn-start');
    if (btnStart) {
        btnStart.addEventListener('click', function(e) {
            e.preventDefault();
            showStep(1, 'next');
        });
    }

    // Prevent submitting by hitting enter in input fields
    form.addEventListener('submit', function(e) {
        if (currentStep !== 6) {
            e.preventDefault();
            nextStep();
        }
    });

    // Initial load: setup icons
    if (window.lucide) {
        window.lucide.createIcons();
    }
});
