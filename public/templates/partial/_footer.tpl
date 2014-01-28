		<footer class="main-footer" role="contentinfo">
      <div class="wrapper">
        <div class="row clearfix">
          {changeLog max=4}
        </div>
      </div>
      
      <div class="wrapper">
        <p class="badges"><a href="http://www.w3.org/html/logo/"><img 
            src="http://www.w3.org/html/logo/badge/html5-badge-h-css3-semantics.png" 
            width="165" height="64" alt="HTML5 Powered with CSS3 / Styling, and Semantics" 
            title="HTML5 Powered with CSS3 / Styling, and Semantics"></a></p>
        <p class="copyright">
          © Untitled. All rights reserved.
        </p>
      </div>
    </footer>
<!--
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="{$synPublicPath}/js/vendor/jquery-2.0.0.min.js"><\/script>')</script>
-->
    <script src="{$synPublicPath}/js/vendor/jquery-2.0.0.min.js"></script>
    <script src="{$synPublicPath}/js/vendor/jquery.colorbox-min.js"></script>
    <script src="{$synPublicPath}/js/jquery.validate.min.js"></script>
    <script src="{$synPublicPath}/js/plugins.js"></script>
    <script src="{$synPublicPath}/js/main.js"></script>
{if $pageScript neq ''}
    <script type="text/javascript">
    {$pageScript}
    </script>
{/if}
  </body>
</html>