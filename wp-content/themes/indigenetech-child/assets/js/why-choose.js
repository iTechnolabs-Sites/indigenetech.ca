let currentIndex = 0;
const track = document.getElementById('sliderTrack');
const items = document.querySelectorAll('.feature-box');
const totalItems = items.length;

function getItemsToShow() {
  if (window.innerWidth <= 600) return 1;
  if (window.innerWidth <= 900) return 2;
  return 4;
}

function updateButtons() {
  const leftBtn = document.querySelector('.arrow.left');
  const rightBtn = document.querySelector('.arrow.right');
  const itemsToShow = getItemsToShow();

  leftBtn.disabled = currentIndex === 0;
  rightBtn.disabled = currentIndex >= totalItems - itemsToShow;
}

function moveSlide(step) {
  const itemsToShow = getItemsToShow();
  const slideWidth = items[0].offsetWidth + parseInt(window.getComputedStyle(items[0]).marginRight);
  currentIndex = Math.max(0, Math.min(totalItems - itemsToShow, currentIndex + step));
  track.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
  updateButtons();
}

jQuery(document).ready(function($){
    let lastItem = $('.load-item'); 
    let loadBtn = $('.load-more-btn'); 
    let seeLessBtn = $('.see-less-btn'); 
    seeLessBtn.hide();
    loadBtn.on('click', function(){
        lastItem.addClass('visible'); 
        loadBtn.hide();
        seeLessBtn.show(); 
    });
    seeLessBtn.on('click', function(){
        lastItem.removeClass('visible'); 
        loadBtn.show();
        seeLessBtn.hide();
    });
});
document.addEventListener("DOMContentLoaded", function () {

  const popupOverlay = document.getElementById("itechPopupOverlay");
  const closeBtn = document.getElementById("itechClosePopup");

  // Safety check
  if (!popupOverlay) return;

  function openPopup() {
    popupOverlay.classList.add("show");
    document.body.classList.add("itech-popup-open");
  }

  function closePopup() {
    popupOverlay.classList.remove("show");
    document.body.classList.remove("itech-popup-open");
  }

  // Open popup (desktop + mobile header buttons)
  document.querySelectorAll(".itechOpenPopup").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      openPopup();
    });
  });

  // Close button
  if (closeBtn) {
    closeBtn.addEventListener("click", closePopup);
  }

  // Click outside popup to close
  popupOverlay.addEventListener("click", function (e) {
    if (e.target === popupOverlay) {
      closePopup();
    }
  });

});


