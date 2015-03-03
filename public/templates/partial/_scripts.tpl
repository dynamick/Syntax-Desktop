<!-- JS libraries -->
<script src="{$synPublicPath}/js/monolith.php"></script>
<script>
$('.carousel').carousel({
    interval: 5000 //changes the speed
})
</script>
{if $synPageScript neq ''}{foreach $synPageScript as $script}
  {$script}
{/foreach}{/if}