          var mySwiper = $('.swiper-container1').swiper({
          autoplay:5000,
          loop:true,
          keyboardControl:true,
          mousewheelControl:true,
          mousewheelControlForceToAxis:true,
          paginationClickable: true,
          });
          var tabsSwiper2 = new Swiper('.swiper-container2',{
          speed:500,
          initialSlide:1,
          onSlideChangeStart: function(){
          $(".swiper-container2 .tabs .active").removeClass('active')
          $(".swiper-container2 .tabs a").eq(tabsSwiper2.activeIndex).addClass('active')
          }
          })
          $(".swiper-container2 .tabs a").on('touchstart mousedown',function(e){
          e.preventDefault()
          $(".swiper-container2 .tabs .active").removeClass('active')
          $(this).addClass('active')
          tabsSwiper2.swipeTo( $(this).index() )
          })
          $(".swiper-container2 .tabs a").click(function(e){
          e.preventDefault()
          })
          var tabsSwiper3 = new Swiper('.swiper-container3',{
          speed:500,
          initialSlide:1,
          onSlideChangeStart: function(){
          $(".swiper-container3 .tabs .active").removeClass('active')
          $(".swiper-container3 .tabs a").eq(tabsSwiper3.activeIndex).addClass('active')
          }
          })
          $(".swiper-container3 .tabs a").on('touchstart mousedown',function(e){
          e.preventDefault()
          $(".swiper-container3 .tabs .active").removeClass('active')
          $(this).addClass('active')
          tabsSwiper3.swipeTo( $(this).index() )
          })
          $(".swiper-container3 .tabs a").click(function(e){
          e.preventDefault()
          })

          var tabsSwiper0 = new Swiper('.swiper-container0',{
              speed:500,
              initialSlide:0,
              onSlideChangeStart: function(){
                  $(".swiper-container0 .tabs .active").removeClass('active')
                  $(".swiper-container0 .tabs a").eq(tabsSwiper0.activeIndex).addClass('active')
              }
          })
          $(".swiper-container0 .tabs a").on('touchstart mousedown',function(e){
              e.preventDefault()
              $(".swiper-container0 .tabs .active").removeClass('active')
              $(this).addClass('active')
              tabsSwiper0.swipeTo( $(this).index() )
          })
          $(".swiper-container0 .tabs a").click(function(e){
              e.preventDefault()
          })

          var tabsSwiper01 = new Swiper('.swiper-container-1',{
              speed:500,
              initialSlide:0,
              onSlideChangeStart: function(){
                  $(".swiper-container-1 .tabs .active").removeClass('active')
                  $(".swiper-container-1 .tabs a").eq(tabsSwiper01.activeIndex).addClass('active')
              }
          })
          $(".swiper-container-1 .tabs a").on('touchstart mousedown',function(e){
              e.preventDefault()
              $(".swiper-container-1 .tabs .active").removeClass('active')
              $(this).addClass('active')
              tabsSwiper01.swipeTo( $(this).index() )
          })
          $(".swiper-container-1 .tabs a").click(function(e){
              e.preventDefault()
          })


          var tabsSwiper4 = new Swiper('.swiper-container4',{
          speed:500,
          initialSlide:1,
          calculateHeight:true,
          onSlideChangeStart: function(){
          $(".swiper-container4 .tabs .active").removeClass('active')
          $(".swiper-container4 .tabs a").eq(tabsSwiper4.activeIndex).addClass('active')
          }
          })
          $(".swiper-container4 .tabs a").on('touchstart mousedown',function(e){
          e.preventDefault()
          $(".swiper-container4 .tabs .active").removeClass('active')
          $(this).addClass('active')
          tabsSwiper4.swipeTo( $(this).index() )
          })
          $(".swiper-container4 .tabs a").click(function(e){
          e.preventDefault()
          })
          
          $("a.go_to_details").click(function(e){
          e.preventDefault();
          $(".swiper-container4 .tabs .active").removeClass('active')
          $(this).addClass('active')
          tabsSwiper4.swipeTo( $(".tabs a:nth-child(2)").index() )
          })

          var tabsSwiper5 = new Swiper('.swiper-container5',{
          speed:500,
          initialSlide:2,
          onSlideChangeStart: function(){
          $(".swiper-container5 .tabs .active").removeClass('active')
          $(".swiper-container5 .tabs a").eq(tabsSwiper5.activeIndex).addClass('active')
          }
          })
          $(".swiper-container5 .tabs a").on('touchstart mousedown',function(e){
          e.preventDefault()
          $(".swiper-container5 .tabs .active").removeClass('active')
          $(this).addClass('active')
          tabsSwiper5.swipeTo( $(this).index() )
          })
          $(".swiper-container5 .tabs a").click(function(e){
          e.preventDefault()
          })