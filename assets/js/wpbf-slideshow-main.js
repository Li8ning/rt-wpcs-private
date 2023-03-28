var swiper = new Swiper('#wpbf-swiper-slider.swiper', {
  // Optional parameters
  direction: 'horizontal',
  loop: true,

  // If we need pagination
  pagination: {
    el: '.swiper-pagination',
  },

  // Center Slides
  centeredSlides: true,

  // Modify default container swiper class
  containerModifierClass: 'wpbf-swiper-',

  // Navigation arrows
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  }
});