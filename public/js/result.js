/**
 * Suwetres.in - Results Page Analytics & Animations
 */

document.addEventListener('DOMContentLoaded', () => {
    "use strict";

    // 1. Animate SVG Gauge
    const gaugeFill = document.querySelector('.gauge-fill');
    if (gaugeFill) {
        const finalScore = parseFloat(gaugeFill.getAttribute('data-score')) || 0;
        
        // stroke-dashoffset formula for semicircle gauge:
        // offset = circumference - (score / 100 * circumference)
        const maxVal = 377; // Semicircle circumference for r=120
        const offset = maxVal - ((finalScore / 100) * maxVal);
        
        setTimeout(() => {
            gaugeFill.style.strokeDashoffset = offset;
        }, 300);
    }

    // 2. Animate Breakdown Bars
    const bars = document.querySelectorAll('.breakdown-bar-inner');
    bars.forEach(bar => {
        const targetWidth = bar.getAttribute('data-width');
        if (targetWidth !== null) {
            setTimeout(() => {
                bar.style.width = targetWidth + '%';
            }, 400);
        }
    });
});
