{include file="_header.tpl"}
{include file="_left.tpl"}		
{include file="_right.tpl"}		
		
		<div id="content">
        <div class="main shadow">
{slideshow|indent:10 nav=true gal=true}
          <div id="caption"></div>
        </div>

        <script type="text/javascript">
        //<![CDATA[ {literal}
        $(document).ready(function(){
          $('.slideshow').cycle({
            timeout: 0,
            pager:'#slide-nav',
            pagerAnchorBuilder: function(idx, slide) {return '#slide-nav li:eq('+idx+') a';},
            before: onBefore,
            after: onAfter
          });

          if (($(".gallery-in li").length)>8) { // se ci sono più di 3 foto
            // appendo i comandi per lo scroll
            $('#gallery-out').prepend("<a class='arrow back disabled' href='javascript:void(0)'>&laquo;</a>");
            $('#gallery-out').append("<a class='arrow forward' href='javascript:void(0)'>&raquo;</a>");
            // lancio la gallery
            $('.gallery-in').jCarouselLite({
              btnNext:'.forward', btnPrev:'.back', mouseWheel:true, circular:false, visible:8, scroll:1
            });
          }
        });

        function onBefore() { 
          $('#caption').fadeOut('slow'); 
        } 

        function onAfter() { 
          $('#caption').html('<h3>'+this.alt+'</h3>').fadeIn('slow'); 
        }        
        //]]> {/literal}
        </script>

		</div>

{include file='_footer.tpl'}