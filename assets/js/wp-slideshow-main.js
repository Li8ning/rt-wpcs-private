var swiper = new Swiper('#wp-swiper-slider.swiper', {
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
  containerModifierClass: 'wp-swiper-',

  // Navigation arrows
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  }
});