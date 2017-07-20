$(document).ready(function() {
  if($('.top-headers .item').length>1){
    $('.top-headers').owlCarousel({
      items: 1,
      //nav: true,
      loop: true
    });
  }

  if($('.img-products .item').length>1){
    $('.img-products').owlCarousel({
      items: 1,
      //nav: true,
      loop: true
    });
  }

});