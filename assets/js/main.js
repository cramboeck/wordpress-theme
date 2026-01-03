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

// Initialize
document.addEventListener('DOMContentLoaded', () => {
	initMobileMenu();
	initStickyHeader();
	initFaqAccordion();
	initSmoothScroll();
});
