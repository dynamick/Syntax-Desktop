  <h4>Site search</h4>
  <form action="{createPath page=$smarty.const.PAGE_SEARCH}" method="get">
    <div class="input-group">
      <input type="text" placeholder="Search" class="form-control" name="q" value="">
      <span class="input-group-btn">
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-search"></i>
        </button>
      </span>
    </div>
  </form>