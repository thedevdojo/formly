// Scroll-triggered reveals: add data-reveal to any element (optionally with
// style="--reveal-delay: 120ms") and it fades up when it enters the viewport.
const revealObserver = new IntersectionObserver(
    (entries) => {
        for (const entry of entries) {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                revealObserver.unobserve(entry.target);
            }
        }
    },
    { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
);

function observeReveals() {
    document.querySelectorAll('[data-reveal]:not(.in-view)').forEach((el) => revealObserver.observe(el));
}

document.addEventListener('DOMContentLoaded', observeReveals);
document.addEventListener('livewire:navigated', observeReveals);
