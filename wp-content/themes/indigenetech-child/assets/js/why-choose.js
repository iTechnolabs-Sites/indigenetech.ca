/**
 * Slider for the [why_choose_section] shortcode (functions.php).
 * Enqueued only on pages where the shortcode is rendered.
 *
 * The old "load more" handler (.load-more-btn / .load-item) was removed: no
 * page has that markup. The popup code lives in itech-site.js.
 */
var currentIndex = 0;

function getItemsToShow() {
  if (window.innerWidth <= 600) return 1;
  if (window.innerWidth <= 900) return 2;
  return 4;
}

function updateButtons() {
  var items = document.querySelectorAll('#sliderTrack .feature-box');
  var leftBtn = document.querySelector('.why-choose .arrow.left');
  var rightBtn = document.querySelector('.why-choose .arrow.right');
  if (!leftBtn || !rightBtn) return;
  leftBtn.disabled = currentIndex === 0;
  rightBtn.disabled = currentIndex >= items.length - getItemsToShow();
}

// Called from the shortcode's onclick="moveSlide(-1|1)" buttons.
function moveSlide(step) {
  var track = document.getElementById('sliderTrack');
  var items = track ? track.querySelectorAll('.feature-box') : [];
  if (!items.length) return;
  var slideWidth = items[0].offsetWidth + parseInt(window.getComputedStyle(items[0]).marginRight, 10);
  currentIndex = Math.max(0, Math.min(items.length - getItemsToShow(), currentIndex + step));
  track.style.transform = 'translateX(-' + currentIndex * slideWidth + 'px)';
  updateButtons();
}

document.addEventListener('DOMContentLoaded', updateButtons);
