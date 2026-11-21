/**
 * Sundarban Pure Honey Landing Page - Vanilla JavaScript
 * Handles gallery switcher, smooth scrolling, interactive UI toggles,
 * and smart YouTube video loader.
 * (Cart and checkout logic deferred to Laravel Blade / backend)
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // Delivery Area Selection UI Toggle
  const deliveryCards = document.querySelectorAll('.delivery-radio-card');
  deliveryCards.forEach(dCard => {
    dCard.addEventListener('click', function () {
      deliveryCards.forEach(c => c.classList.remove('active'));
      this.classList.add('active');
    });
  });

  // Quantity Counter UI
  const btnQtyMinus = document.getElementById('btnQtyMinus');
  const btnQtyPlus = document.getElementById('btnQtyPlus');
  const cartQtyDisplay = document.getElementById('cartProductQty');

  if (btnQtyMinus && cartQtyDisplay) {
    btnQtyMinus.addEventListener('click', function (e) {
      e.stopPropagation();
      let currentQty = parseInt(cartQtyDisplay.textContent, 10) || 1;
      if (currentQty > 1) {
        cartQtyDisplay.textContent = currentQty - 1;
      }
    });
  }

  if (btnQtyPlus && cartQtyDisplay) {
    btnQtyPlus.addEventListener('click', function (e) {
      e.stopPropagation();
      let currentQty = parseInt(cartQtyDisplay.textContent, 10) || 1;
      cartQtyDisplay.textContent = currentQty + 1;
    });
  }

  // Gallery Interactive Thumbnail Switcher
  const galleryMainImg = document.getElementById('galleryMainImage');
  const galleryThumbs = document.querySelectorAll('.gallery-thumb-item');

  galleryThumbs.forEach(thumb => {
    thumb.addEventListener('click', function () {
      galleryThumbs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');

      const targetSrc = this.getAttribute('data-full-img');
      if (galleryMainImg && targetSrc) {
        galleryMainImg.style.opacity = '0.5';
        setTimeout(() => {
          galleryMainImg.src = targetSrc;
          galleryMainImg.style.opacity = '1';
        }, 150);
      }
    });
  });

  // Smooth Scroll for Internal Anchors
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      if (targetId && targetId !== '#') {
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          e.preventDefault();
          targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }
    });
  });

  // ==========================================================================
  // Smart YouTube Video Player Loader
  // Handles local file:// security boundaries gracefully (preventing Error 153)
  // while mounting the live interactive YouTube iframe on HTTP/HTTPS/localhost
  // ==========================================================================
  const videoContainer = document.getElementById('videoContainer');
  const ytIframe = document.getElementById('youtubeIframe');
  const videoFacade = document.getElementById('videoFacade');
  const fileBadge = document.getElementById('fileProtocolBadge');
  const isLocalFile = window.location.protocol === 'file:';

  if (isLocalFile) {
    // Under local file:// protocol, YouTube blocks embedded iframes with Error 153 due to missing origin/referrer.
    // Display the clean video facade with click-to-watch trigger:
    if (fileBadge) fileBadge.classList.remove('d-none');
    if (videoFacade) {
      videoFacade.addEventListener('click', function () {
        const videoId = videoContainer ? videoContainer.getAttribute('data-video-id') || 'M7lc1UVf-VE' : 'M7lc1UVf-VE';
        window.open(`https://www.youtube.com/watch?v=${videoId}`, '_blank');
      });
    }
  } else {
    // On HTTP or HTTPS (e.g., Laravel, localhost server, production domain), YouTube player runs natively!
    if (ytIframe) {
      const dataSrc = ytIframe.getAttribute('data-src') || 'https://www.youtube.com/embed/M7lc1UVf-VE?rel=0';
      ytIframe.src = dataSrc;
      ytIframe.classList.remove('d-none');
    }
    if (videoFacade) {
      videoFacade.classList.add('d-none');
    }
  }
});
