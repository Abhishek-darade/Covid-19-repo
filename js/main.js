// COVID-19 Info Portal - Main JavaScript
// Software Testing & QA Mini Project

document.addEventListener('DOMContentLoaded', function () {

    // 1. Navbar scroll effect
    const navbar = document.getElementById('mainNav');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.style.boxShadow = window.scrollY > 50
                ? '0 4px 30px rgba(0,0,0,0.4)' : '0 2px 20px rgba(0,0,0,0.3)';
        });
    }

    // 2. Counter animation for stat numbers
    function animateCounter(el) {
        const text = el.textContent.trim();
        const isB = text.endsWith('B'), isM = text.endsWith('M'), isK = text.endsWith('K');
        let target = parseFloat(text);
        if (isNaN(target)) return;
        let current = 0;
        const step = target / (1500 / 16);
        const timer = setInterval(() => {
            current += step;
            if (current >= target) { current = target; clearInterval(timer); }
            let d = current.toFixed(isB||isM||isK ? 1 : 0);
            if (isB) d += 'B'; else if (isM) d += 'M'; else if (isK) d += 'K';
            el.textContent = d;
        }, 16);
    }

    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) { animateCounter(e.target); obs.unobserve(e.target); } });
        }, { threshold: 0.5 });
        document.querySelectorAll('.stat-number').forEach(el => obs.observe(el));
    }

    // 3. Fade-in on scroll
    if ('IntersectionObserver' in window) {
        const fadeObs = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.style.opacity = '1'; e.target.style.transform = 'translateY(0)'; fadeObs.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.card').forEach(el => {
            el.style.cssText = 'opacity:0; transform:translateY(20px); transition:opacity .5s ease, transform .5s ease;';
            fadeObs.observe(el);
        });
    }

    // 4. Back to top button
    const btn = document.createElement('button');
    btn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    btn.title = 'Back to Top';
    Object.assign(btn.style, {
        position:'fixed', bottom:'30px', right:'30px', width:'44px', height:'44px',
        borderRadius:'50%', background:'#e63946', color:'#fff', border:'none',
        fontSize:'1rem', cursor:'pointer', display:'none', alignItems:'center',
        justifyContent:'center', boxShadow:'0 4px 15px rgba(0,0,0,0.2)', zIndex:'999',
        transition:'opacity 0.3s'
    });
    document.body.appendChild(btn);
    window.addEventListener('scroll', () => { btn.style.display = window.scrollY > 400 ? 'flex' : 'none'; });
    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    // 5. Tooltip init
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    console.log('%c COVID-19 Portal Loaded', 'color:#198754;font-size:14px;font-weight:bold;');
});
