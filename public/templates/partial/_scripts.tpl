<!-- JS libraries -->
<script type="text/javascript" src="{$synPublicPath}/js/monolith.php"></script>
<script type="text/javascript">
  $('.carousel').carousel({
    interval: 5000
  })
  {trad varname=l label='cookie_disclaimer|read_more'}
  $(document).ready(function() {
    new $.cookieDisclaimer({
      description: '{$l.cookie_disclaimer}',
      readMore: '{$l.read_more}',
      btnBgColor: '#337ab7',
      btnBgHoverColor: '#286090'
    });
  });
</script>
{if $synPageScript neq ''}{foreach $synPageScript as $script}
  {$script}
{/foreach}{/if}