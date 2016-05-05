<!DOCTYPE html>
<html lang="{$synLangInitial}">
{include file="partial/_head.tpl"}
<body>
  {include file="partial/_header.tpl"}

  <!-- Header Carousel -->
  <header id="myCarousel" class="carousel slide">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      <div class="item active">
        <div class="fill" style="background-image: radial-gradient(ellipse farthest-corner at right top, #FFFFFF 0%, #00A3EF 100%);"></div>
        <div class="carousel-caption">
          <h2>Caption 1</h2>
        </div>
      </div>
      <div class="item">
        <div class="fill" style="background-image: radial-gradient(ellipse farthest-corner at center bottom, #EDC0D8 0%, #694894 100%);"></div>
        <div class="carousel-caption">
          <h2>Caption 2</h2>
        </div>
      </div>
      <div class="item">
        <div class="fill" style="background-image: radial-gradient(ellipse farthest-side at left bottom, #CFEDC0 0%, #229485 100%);"></div>
        <div class="carousel-caption">
          <h2>Caption 3</h2>
        </div>
      </div>
    </div>
    <!-- Controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="icon-prev"></span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="icon-next"></span>
    </a>
  </header>

  <!-- Page Content -->
  <div class="section">
    <div class="container">
      <!-- Marketing Icons Section -->
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Welcome to Syntax Desktop</h1>
        </div>
        <div class="col-md-4 text-center">
          <div class="panel panel-info">
            <div class="panel-heading">
              <h4>
                <i class="fa fa-fw fa-check fa-3x"></i><br>
                Bootstrap v3.2.0
              </h4>
            </div>
            <div class="panel-body">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque, optio corporis quae nulla aspernatur in alias at numquam rerum ea excepturi expedita tenetur assumenda voluptatibus eveniet incidunt dicta nostrum quod?</p>
              <a href="#" class="btn btn-default btn-block">Learn More</a>
            </div>
          </div>
        </div>
        <div class="col-md-4 text-center">
          <div class="panel panel-warning">
            <div class="panel-heading">
              <h4>
                <i class="fa fa-fw fa-gift fa-3x"></i><br>
                Free &amp; Open Source
              </h4>
            </div>
            <div class="panel-body">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque, optio corporis quae nulla aspernatur in alias at numquam rerum ea excepturi expedita tenetur assumenda voluptatibus eveniet incidunt dicta nostrum quod?</p>
              <a href="#" class="btn btn-default btn-block">Learn More</a>
            </div>
          </div>
        </div>
        <div class="col-md-4 text-center">
          <div class="panel panel-success">
            <div class="panel-heading">
              <h4>
                <i class="fa fa-fw fa-compass fa-3x"></i><br>
                Easy to Use
              </h4>
            </div>
            <div class="panel-body">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque, optio corporis quae nulla aspernatur in alias at numquam rerum ea excepturi expedita tenetur assumenda voluptatibus eveniet incidunt dicta nostrum quod?</p>
              <a href="#" class="btn btn-default btn-block">Learn More</a>
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->

      <!-- Features Section -->
      <div class="row">
        <div class="col-lg-12">
          <h2 class="page-header">Syntax Desktop Features</h2>
        </div>
        <div class="col-md-12">
          <div class="rich-text">
          {$synPageText}
          </div>
        </div>
      </div>
      <!-- /.row -->
    </div>
  </div>
  <!-- /.container -->

  {include file="partial/_cta.tpl"}
  {include file="partial/_footer.tpl"}
  {include file="partial/_scripts.tpl"}
</body>
</html>
