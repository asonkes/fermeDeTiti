import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

// Carroussel fait avec Swiper JS
    const swiper = new Swiper('.swiper', {

        // If we need pagination
        pagination: {
          el: '.swiper-pagination',
        },
      
        // Navigation arrows
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        }
      });