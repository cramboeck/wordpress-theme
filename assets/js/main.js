/**
 * Main JavaScript Entry Point
 *
 * @package Ramboeck
 */

// Mobile Menu Toggle
const initMobileMenu = () => {
	const toggle = document.querySelector('.menu-toggle');
	const nav = document.querySelector('.site-header__nav');

	if (!toggle || !nav) {
		return;
	}

	toggle.addEventListener('click', () => {
		toggle.classList.toggle('is-active');
		nav.classList.toggle('is-open');
		document.body.classList.toggle('menu-open');
	});
};

// Sticky Header
const initStickyHeader = () => {
	const header = document.querySelector('.site-header');

	if (!header) {
		return;
	}

	const observer = new IntersectionObserver(
		([entry]) => {
			header.classList.toggle('is-scrolled', !entry.isIntersecting);
		},
		{ threshold: 0, rootMargin: '-1px 0px 0px 0px' }
	);

	observer.observe(document.body);
};

// FAQ Accordion
const initFaqAccordion = () => {
	const items = document.querySelectorAll('.faq__item');

	items.forEach((item) => {
		const question = item.querySelector('.faq__question');

		if (!question) {
			return;
		}

		question.addEventListener('click', () => {
			const isOpen = item.classList.contains('is-open');

			// Close all
			items.forEach((i) => i.classList.remove('is-open'));

			// Toggle current
			if (!isOpen) {
				item.classList.add('is-open');
			}
		});
	});
};

// Smooth Scroll
const initSmoothScroll = () => {
	document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
		anchor.addEventListener('click', (e) => {
			const href = anchor.getAttribute('href');

			if (href === '#') {
				return;
			}

			const target = document.querySelector(href);

			if (target) {
				e.preventDefault();
				target.scrollIntoView({ behavior: 'smooth' });
			}
		});
	});
};

// Scroll Reveal Animations
const initScrollReveal = () => {
	const elements = document.querySelectorAll('[data-animate], .animate-on-scroll, .service-card, .feature-item, .testimonial-card, .faq__item, .wp-block-column');

	if (!elements.length || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		// Show all elements immediately if reduced motion is preferred
		elements.forEach(el => el.classList.add('is-visible'));
		return;
	}

	const observer = new IntersectionObserver(
		(entries) => {
			entries.forEach((entry, index) => {
				if (entry.isIntersecting) {
					// Stagger animation for multiple elements
					const delay = entry.target.dataset.delay || (index * 100);
					setTimeout(() => {
						entry.target.classList.add('is-visible');
					}, Math.min(delay, 400));
					observer.unobserve(entry.target);
				}
			});
		},
		{
			threshold: 0.1,
			rootMargin: '0px 0px -50px 0px'
		}
	);

	elements.forEach((el) => {
		el.classList.add('will-animate');
		observer.observe(el);
	});
};

// Parallax Effect
const initParallax = () => {
	const parallaxElements = document.querySelectorAll('[data-parallax], .hero--has-background');

	if (!parallaxElements.length || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	let ticking = false;

	const updateParallax = () => {
		const scrolled = window.pageYOffset;

		parallaxElements.forEach((el) => {
			const speed = parseFloat(el.dataset.parallaxSpeed) || 0.5;
			const rect = el.getBoundingClientRect();
			const inView = rect.bottom > 0 && rect.top < window.innerHeight;

			if (inView) {
				const yPos = (scrolled - el.offsetTop) * speed;
				el.style.setProperty('--parallax-offset', `${yPos}px`);
			}
		});

		ticking = false;
	};

	window.addEventListener('scroll', () => {
		if (!ticking) {
			requestAnimationFrame(updateParallax);
			ticking = true;
		}
	}, { passive: true });

	updateParallax();
};

// Counter Animation
const initCounters = () => {
	const counters = document.querySelectorAll('[data-counter], .stat-number');

	if (!counters.length) {
		return;
	}

	const animateCounter = (el) => {
		const target = parseInt(el.dataset.counter || el.textContent.replace(/\D/g, ''), 10);
		const suffix = el.dataset.suffix || el.textContent.replace(/[\d.,]/g, '') || '';
		const duration = parseInt(el.dataset.duration, 10) || 2000;
		const start = 0;
		const startTime = performance.now();

		const easeOutQuart = (t) => 1 - Math.pow(1 - t, 4);

		const updateCounter = (currentTime) => {
			const elapsed = currentTime - startTime;
			const progress = Math.min(elapsed / duration, 1);
			const easedProgress = easeOutQuart(progress);
			const current = Math.floor(start + (target - start) * easedProgress);

			el.textContent = current.toLocaleString('de-DE') + suffix;

			if (progress < 1) {
				requestAnimationFrame(updateCounter);
			}
		};

		requestAnimationFrame(updateCounter);
	};

	const observer = new IntersectionObserver(
		(entries) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					animateCounter(entry.target);
					observer.unobserve(entry.target);
				}
			});
		},
		{ threshold: 0.5 }
	);

	counters.forEach((counter) => observer.observe(counter));
};

// Typing Effect for Hero
const initTypingEffect = () => {
	const elements = document.querySelectorAll('[data-typing]');

	if (!elements.length || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	elements.forEach((el) => {
		const text = el.dataset.typing || el.textContent;
		const speed = parseInt(el.dataset.typingSpeed, 10) || 50;
		el.textContent = '';
		el.style.visibility = 'visible';

		let i = 0;
		const typeWriter = () => {
			if (i < text.length) {
				el.textContent += text.charAt(i);
				i++;
				setTimeout(typeWriter, speed);
			}
		};

		// Start when in view
		const observer = new IntersectionObserver(
			(entries) => {
				if (entries[0].isIntersecting) {
					typeWriter();
					observer.disconnect();
				}
			},
			{ threshold: 0.5 }
		);

		observer.observe(el);
	});
};

// Tilt Effect on Cards
const initTiltEffect = () => {
	const cards = document.querySelectorAll('[data-tilt], .service-card');

	if (!cards.length || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	cards.forEach((card) => {
		card.addEventListener('mousemove', (e) => {
			const rect = card.getBoundingClientRect();
			const x = e.clientX - rect.left;
			const y = e.clientY - rect.top;
			const centerX = rect.width / 2;
			const centerY = rect.height / 2;
			const rotateX = (y - centerY) / 20;
			const rotateY = (centerX - x) / 20;

			card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
		});

		card.addEventListener('mouseleave', () => {
			card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
		});
	});
};

// Magnetic Button Effect
const initMagneticButtons = () => {
	const buttons = document.querySelectorAll('.wp-block-button__link, .button--magnetic');

	if (!buttons.length || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	buttons.forEach((btn) => {
		btn.addEventListener('mousemove', (e) => {
			const rect = btn.getBoundingClientRect();
			const x = e.clientX - rect.left - rect.width / 2;
			const y = e.clientY - rect.top - rect.height / 2;

			btn.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px)`;
		});

		btn.addEventListener('mouseleave', () => {
			btn.style.transform = 'translate(0, 0)';
		});
	});
};

// Back to Top Button
const initBackToTop = () => {
	const btn = document.createElement('button');
	btn.className = 'back-to-top';
	btn.innerHTML = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18,15 12,9 6,15"></polyline></svg>`;
	btn.setAttribute('aria-label', 'Nach oben scrollen');
	document.body.appendChild(btn);

	let isVisible = false;

	window.addEventListener('scroll', () => {
		const shouldShow = window.pageYOffset > 500;

		if (shouldShow !== isVisible) {
			isVisible = shouldShow;
			btn.classList.toggle('is-visible', isVisible);
		}
	}, { passive: true });

	btn.addEventListener('click', () => {
		window.scrollTo({ top: 0, behavior: 'smooth' });
	});
};

// Image Reveal on Scroll
const initImageReveal = () => {
	const images = document.querySelectorAll('.wp-block-image img, [data-reveal]');

	if (!images.length || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	const observer = new IntersectionObserver(
		(entries) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-revealed');
					observer.unobserve(entry.target);
				}
			});
		},
		{ threshold: 0.2 }
	);

	images.forEach((img) => {
		img.classList.add('will-reveal');
		observer.observe(img);
	});
};

// Progress Bar on Scroll
const initScrollProgress = () => {
	const progressBar = document.createElement('div');
	progressBar.className = 'scroll-progress';
	document.body.appendChild(progressBar);

	window.addEventListener('scroll', () => {
		const scrollTop = window.pageYOffset;
		const docHeight = document.documentElement.scrollHeight - window.innerHeight;
		const scrollPercent = (scrollTop / docHeight) * 100;
		progressBar.style.width = `${scrollPercent}%`;
	}, { passive: true });
};

// Initialize all
document.addEventListener('DOMContentLoaded', () => {
	initMobileMenu();
	initStickyHeader();
	initFaqAccordion();
	initSmoothScroll();
	initScrollReveal();
	initParallax();
	initCounters();
	initTypingEffect();
	initTiltEffect();
	initMagneticButtons();
	initBackToTop();
	initImageReveal();
	initScrollProgress();
});
