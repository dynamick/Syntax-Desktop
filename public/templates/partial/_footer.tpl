<!-- Footer -->
<footer class="section">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
          <p class="text-muted">Copyright &copy; {$synWebsiteTitle}, 2015</p>
      </div>
      <div class="col-md-6 text-right">
        {socialLinks}
        <ul class="list-inline">
        {foreach $social_links as $sl}
          <li>
            <a href="{$sl.url}" target="_blank">
              <i class="fa fa-{$sl.icon}-square fa-2x"></i>
            </a>
          </li>
        {/foreach}
        </ul>
      </div>
    </div>
  </div>
</footer>
<!-- end Footer -->
