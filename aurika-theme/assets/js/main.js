/**
 * Aurika Theme — High Performance main.js
 * Optimized for 0ms lag, fast cart responsiveness & mobile devices.
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  /* ============================================================
     1. STICKY HEADER (Passive scroll listener)
  ============================================================ */
  const header = document.querySelector('.site-header');
  if (header) {
    let ticking = false;
    window.addEventListener('scroll', function () {
      if (!ticking) {
        window.requestAnimationFrame(function () {
          if (window.scrollY > 60) {
            header.classList.add('scrolled');
          } else {
            header.classList.remove('scrolled');
          }
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  }

  /* ============================================================
     2. MOBILE MENU & SEARCH (Instant touch response)
  ============================================================ */
  const menuToggle   = document.querySelector('.mobile-menu-toggle');
  const mobileMenu   = document.querySelector('.mobile-menu-overlay');
  const mobileClose  = document.querySelector('.mobile-menu-close');
  const body         = document.body;

  function openMobileMenu() {
    if (mobileMenu) mobileMenu.classList.add('is-open');
    body.classList.add('menu-open');
    if (menuToggle) menuToggle.setAttribute('aria-expanded', 'true');
  }

  function closeMobileMenu() {
    if (mobileMenu) mobileMenu.classList.remove('is-open');
    body.classList.remove('menu-open');
    if (menuToggle) menuToggle.setAttribute('aria-expanded', 'false');
  }

  if (menuToggle) menuToggle.addEventListener('click', openMobileMenu);
  if (mobileClose) mobileClose.addEventListener('click', closeMobileMenu);
  if (mobileMenu) {
    mobileMenu.addEventListener('click', function (e) {
      if (e.target === mobileMenu) closeMobileMenu();
    });
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeMobileMenu();
  });

  // Search Toggle
  const searchBtn     = document.querySelector('.header-search-btn');
  const searchOverlay = document.querySelector('.search-overlay');
  const searchClose   = document.querySelector('.search-overlay-close');
  const searchInput   = document.querySelector('.search-overlay input[type="search"]');

  if (searchBtn && searchOverlay) {
    searchBtn.addEventListener('click', function (e) {
      e.preventDefault();
      searchOverlay.classList.toggle('is-open');
      if (searchOverlay.classList.contains('is-open') && searchInput) {
        setTimeout(function() { searchInput.focus(); }, 80);
      }
    });
  }

  if (searchClose && searchOverlay) {
    searchClose.addEventListener('click', function () {
      searchOverlay.classList.remove('is-open');
    });
  }

  /* ============================================================
     3. SMOOTH SCROLL FOR ANCHOR LINKS
  ============================================================ */
  document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (!href || href === '#') return;
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        const headerH = header ? header.offsetHeight : 70;
        const top = target.getBoundingClientRect().top + window.pageYOffset - headerH;
        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });

  /* ============================================================
     4. HIGH PERFORMANCE SCROLL ANIMATIONS
  ============================================================ */
  const animatedEls = document.querySelectorAll('.animate-on-scroll');
  if ('IntersectionObserver' in window && animatedEls.length) {
    const observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.08, rootMargin: '0px 0px -20px 0px' });

    animatedEls.forEach(function (el) { observer.observe(el); });
  } else {
    animatedEls.forEach(function (el) { el.classList.add('is-visible'); });
  }

  /* ============================================================
     5. INSTANT AJAX ADD-TO-CART & LIVE CART BADGE (0ms DELAY)
  ============================================================ */
  function showCartToast(title) {
    let toast = document.querySelector('.aurika-cart-toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.className = 'aurika-cart-toast';
      document.body.appendChild(toast);
    }
    toast.innerHTML = '<span style="font-size:16px;">🕯️</span> <span><strong>Added to Cart!</strong> ' + (title ? escHtml(title) : '') + '</span>';
    toast.classList.add('is-visible');
    setTimeout(function () {
      toast.classList.remove('is-visible');
    }, 2800);
  }

  function escHtml(str) {
    const p = document.createElement('p');
    p.textContent = str;
    return p.innerHTML;
  }

  // Instant optimistic update on any Add to Cart click
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.ajax_add_to_cart, .add_to_cart_button, .single_add_to_cart_button');
    if (!btn) return;

    // Pulse cart badge and optimistically increment
    const badges = document.querySelectorAll('.cart-count');
    badges.forEach(function (badge) {
      const current = parseInt(badge.textContent.trim()) || 0;
      badge.textContent = current + 1;
      badge.style.display = 'flex';
      badge.classList.add('pulse-badge');
      setTimeout(function () { badge.classList.remove('pulse-badge'); }, 500);
    });

    const card = btn.closest('.product, .product-card, .aurika-product-detail');
    const productTitle = card ? (card.querySelector('.woocommerce-loop-product__title, .product_title, h3, h1')?.textContent || '') : '';
    showCartToast(productTitle);
  });

  // Sync with WooCommerce native AJAX events
  if (window.jQuery) {
    window.jQuery(document.body).on('added_to_cart', function (e, fragments) {
      if (fragments && fragments['span.cart-count']) {
        const temp = document.createElement('div');
        temp.innerHTML = fragments['span.cart-count'];
        const newCount = temp.textContent.trim();
        document.querySelectorAll('.cart-count').forEach(function (badge) {
          badge.textContent = newCount;
          badge.style.display = parseInt(newCount) > 0 ? 'flex' : 'none';
        });
      }
    });
  }

  /* ============================================================
     6. PRODUCT QUANTITY BUTTONS (+/-)
  ============================================================ */
  function initQtyButtons() {
    document.querySelectorAll('.quantity').forEach(function (wrap) {
      if (wrap.querySelector('.qty-btn')) return;
      const input = wrap.querySelector('input.qty');
      if (!input) return;

      const minus = document.createElement('button');
      minus.type = 'button';
      minus.className = 'qty-btn qty-minus';
      minus.textContent = '−';
      minus.setAttribute('aria-label', 'Decrease quantity');

      const plus = document.createElement('button');
      plus.type = 'button';
      plus.className = 'qty-btn qty-plus';
      plus.textContent = '+';
      plus.setAttribute('aria-label', 'Increase quantity');

      wrap.insertBefore(minus, input);
      wrap.appendChild(plus);

      minus.addEventListener('click', function () {
        const val = parseInt(input.value) || 1;
        const min = parseInt(input.getAttribute('min')) || 1;
        if (val > min) {
          input.value = val - 1;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });

      plus.addEventListener('click', function () {
        const val = parseInt(input.value) || 1;
        const max = parseInt(input.getAttribute('max')) || 999;
        if (val < max) {
          input.value = val + 1;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
    });
  }

  initQtyButtons();
  if (window.jQuery) {
    window.jQuery(document.body).on('found_variation show_variation reset_data updated_cart_totals', function(event, variation) {
      initQtyButtons();
      var buyNow = document.querySelector('.btn-buy-now');
      if (buyNow && variation && variation.variation_id) {
        var href = buyNow.getAttribute('href') || '';
        href = href.replace(/add-to-cart=\d+/, 'add-to-cart=' + variation.variation_id);
        buyNow.setAttribute('href', href);
      }
    });
  }

  // Sync Buy It Now when quantity changes
  document.addEventListener('change', function (e) {
    if (e.target && e.target.matches('input.qty')) {
      var buyNow = document.querySelector('.btn-buy-now');
      if (buyNow) {
        var href = buyNow.getAttribute('href') || '';
        href = href.replace(/quantity=\d+/, 'quantity=' + (parseInt(e.target.value) || 1));
        buyNow.setAttribute('href', href);
      }
    }
  });

  // Ensure variation is selected when clicking Buy It Now
  document.addEventListener('click', function (e) {
    var buyNow = e.target.closest('.btn-buy-now');
    if (buyNow) {
      var varForm = document.querySelector('.variations_form');
      if (varForm) {
        var varId = varForm.querySelector('input[name="variation_id"]');
        if (varId && (!varId.value || varId.value === '0')) {
          e.preventDefault();
          var swatches = document.querySelector('.cfvsw-swatches-container, table.variations');
          if (swatches) {
            swatches.scrollIntoView({ behavior: 'smooth', block: 'center' });
            swatches.style.outline = '2px solid #b8860b';
            setTimeout(function() { swatches.style.outline = 'none'; }, 1500);
          }
        }
      }
    }
  });

  /* ============================================================
     7. PACK SELECTOR (1, 2, 3 PACK AUTO-UPDATE)
  ============================================================ */
  const packSelector = document.querySelector('.aurika-pack-selector-wrap');
  if (packSelector) {
    const basePrice = parseFloat(packSelector.getAttribute('data-base-price')) || 0;
    const cards = packSelector.querySelectorAll('.pack-card');
    const qtyInput = document.querySelector('input.input-text.qty, input.qty');
    const priceDisplay = document.querySelector('.product-detail-price');
    const buyNowBtn = document.querySelector('.btn-buy-now');

    cards.forEach(function(card) {
      card.addEventListener('click', function() {
        cards.forEach(function(c) { c.classList.remove('is-selected'); });
        card.classList.add('is-selected');

        const qty = parseInt(card.getAttribute('data-pack-qty')) || 1;
        const packPrice = parseFloat(card.getAttribute('data-pack-price')) || basePrice;

        if (qtyInput) {
          qtyInput.value = qty;
          qtyInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        if (priceDisplay) {
          if (qty === 1) {
            priceDisplay.innerHTML = '<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">Rs.</span> ' + Math.round(basePrice).toLocaleString() + '</bdi></span>';
          } else {
            const regularTotal = Math.round(basePrice * qty);
            priceDisplay.innerHTML = '<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">Rs.</span> ' + Math.round(packPrice).toLocaleString() + '</bdi></span> ' +
              '<del style="opacity:0.5;font-size:0.75em;margin-left:8px;"><bdi><span class="woocommerce-Price-currencySymbol">Rs.</span> ' + regularTotal.toLocaleString() + '</bdi></del>';
          }
        }

        if (buyNowBtn) {
          let href = buyNowBtn.getAttribute('href') || '';
          href = href.replace(/quantity=\d+/, 'quantity=' + qty);
          buyNowBtn.setAttribute('href', href);
        }
      });
    });
  }

  /* ============================================================
     8. CHECKOUT BUTTON & COUPON CLEANUP (ONE-TIME & EVENT-BASED)
  ============================================================ */
  function optimizeCheckout() {
    // Hide any coupon inputs on checkout
    document.querySelectorAll('.woocommerce-form-coupon-toggle, .woocommerce-form-coupon, .checkout_coupon, .wcf-coupon-wrap, .wcf-coupon-section, .wcf-show-coupon, .wcf-coupon-col').forEach(function(el) {
      el.style.display = 'none';
    });

    // Ensure Place Order button has high-contrast text
    document.querySelectorAll('#place_order, .wcf-btn-place-order, .wcf-place-order-btn, button#place_order').forEach(function(btn) {
      if (!btn.innerText || btn.innerText.trim() === '') {
        btn.innerText = 'PLACE ORDER — CASH ON DELIVERY';
      }
      btn.style.color = '#ffffff';
      btn.style.background = '#111111';
      btn.style.fontWeight = '700';
      btn.style.letterSpacing = '2px';
      btn.style.textTransform = 'uppercase';
      btn.style.fontSize = '14px';
    });
  }

  optimizeCheckout();
  if (window.jQuery) {
    window.jQuery(document.body).on('updated_checkout', optimizeCheckout);
  }

});
