// Modern Loading Screen Handler
document.addEventListener('DOMContentLoaded', function() {
    // Create loading screen if it doesn't exist
    if (!document.querySelector('.loading-screen')) {
        const loadingScreen = document.createElement('div');
        loadingScreen.className = 'loading-screen';
        loadingScreen.innerHTML = `
            <div class="loader"></div>
            <div class="loading-text">Loading...</div>
        `;
        document.body.insertBefore(loadingScreen, document.body.firstChild);
    }

    // Hide loading screen after page loads
    window.addEventListener('load', function() {
        setTimeout(function() {
            const loadingScreen = document.querySelector('.loading-screen');
            if (loadingScreen) {
                loadingScreen.classList.add('hidden');
                setTimeout(function() {
                    loadingScreen.remove();
                }, 500);
            }
        }, 500);
    });
});

// Show loading on form submit
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (!form.hasAttribute('data-no-loading')) {
        showLoading('Processing...');
    }
});

// Show loading on navigation
document.addEventListener('click', function(e) {
    const link = e.target.closest('a');
    if (link && !link.hasAttribute('data-no-loading') && link.href && !link.href.startsWith('#')) {
        showLoading('Loading...');
    }
});

function showLoading(text = 'Loading...') {
    let loadingScreen = document.querySelector('.loading-screen');
    if (!loadingScreen) {
        loadingScreen = document.createElement('div');
        loadingScreen.className = 'loading-screen';
        loadingScreen.innerHTML = `
            <div class="loader"></div>
            <div class="loading-text">${text}</div>
        `;
        document.body.appendChild(loadingScreen);
    } else {
        loadingScreen.classList.remove('hidden');
        loadingScreen.querySelector('.loading-text').textContent = text;
    }
}

function hideLoading() {
    const loadingScreen = document.querySelector('.loading-screen');
    if (loadingScreen) {
        loadingScreen.classList.add('hidden');
    }
}

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add fade-in animation to elements
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.querySelectorAll('.modern-card, .stats-card').forEach(el => {
    observer.observe(el);
});
