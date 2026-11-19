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
// Popup open/close code moved to itech-site.js, which loads on every page.


