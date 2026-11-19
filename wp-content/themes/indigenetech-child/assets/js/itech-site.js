/**
 * IndigeneTech site script: "Get started" popup + HubSpot form loader.
 *
 * Loaded on every page (see itech_enqueue_site_script in functions.php).
 *
 * Popup: any element with class "itechOpenPopup" (Elementor buttons included)
 * opens #itechPopupOverlay. Uses event delegation, so it works for buttons
 * rendered anywhere on the page and binds only once.
 *
 * HubSpot: forms are output as <div class="itech-hs-form" data-...>
 * placeholders by the [hubspot_vertical_form] / [hubspot_horizontal_form]
 * shortcodes and itech_hubspot_form(). The HubSpot embed script (v2.js) is
 * loaded once per page, then each placeholder is turned into a form.
 * Placeholders marked data-lazy="1" (the popup form) are only created when
 * the popup is first opened.
 */
(function () {
  'use strict';

  if (window.itechSiteLoaded) {
    return;
  }
  window.itechSiteLoaded = true;

  var HS_SRC = 'https://js.hsforms.net/forms/embed/v2.js';
  var hsQueue = [];
  var hsLoading = false;

  function hsReady(callback) {
    if (window.hbspt && window.hbspt.forms) {
      callback();
      return;
    }
    hsQueue.push(callback);
    if (hsLoading) {
      return;
    }
    hsLoading = true;
    var script = document.createElement('script');
    script.src = HS_SRC;
    script.charset = 'utf-8';
    script.async = true;
    script.onload = function () {
      var queued = hsQueue.splice(0);
      for (var i = 0; i < queued.length; i++) {
        queued[i]();
      }
    };
    document.head.appendChild(script);
  }

  function createForm(el) {
    if (!el || el.getAttribute('data-hs-created') === '1') {
      return;
    }
    el.setAttribute('data-hs-created', '1');
    hsReady(function () {
      window.hbspt.forms.create({
        portalId: el.getAttribute('data-portal-id'),
        formId: el.getAttribute('data-form-id'),
        region: el.getAttribute('data-region') || 'na1',
        target: '#' + el.id
      });
    });
  }

  function createVisibleForms() {
    var forms = document.querySelectorAll('.itech-hs-form:not([data-lazy="1"])');
    for (var i = 0; i < forms.length; i++) {
      createForm(forms[i]);
    }
  }

  function initPopup() {
    var overlay = document.getElementById('itechPopupOverlay');
    if (!overlay) {
      return;
    }

    function openPopup() {
      var lazyForms = overlay.querySelectorAll('.itech-hs-form[data-lazy="1"]');
      for (var i = 0; i < lazyForms.length; i++) {
        createForm(lazyForms[i]);
      }
      overlay.classList.add('show');
      document.body.classList.add('itech-popup-open');
    }

    function closePopup() {
      overlay.classList.remove('show');
      document.body.classList.remove('itech-popup-open');
    }

    document.addEventListener('click', function (e) {
      var trigger = e.target.closest ? e.target.closest('.itechOpenPopup') : null;
      if (trigger) {
        e.preventDefault();
        openPopup();
        return;
      }
      if (e.target.closest && e.target.closest('#itechClosePopup')) {
        closePopup();
        return;
      }
      if (e.target === overlay) {
        closePopup();
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && overlay.classList.contains('show')) {
        closePopup();
      }
    });
  }

  function init() {
    initPopup();
    createVisibleForms();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
