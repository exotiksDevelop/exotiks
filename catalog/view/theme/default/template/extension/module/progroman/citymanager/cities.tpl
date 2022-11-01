<div class="modal fade prmn-cmngr-cities" id="prmn-cmngr-cities" tabindex="-1" role="dialog" data-show="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="prmn-cmngr-cities__close" data-dismiss="modal">
          <span>&times;</span>
        </button>
        <h4 class="prmn-cmngr-cities__title"><?= $text_your_city ?>: <?= $city ?></h4>

        <div class="prmn-cmngr-cities__search-block">
          <div class="form-group">
            <input class="prmn-cmngr-cities__search form-control" type="text" placeholder="<?= $text_search; ?>">
          </div>
        </div>
        <div class="row clearfix">
        <?php foreach ($columns as $column) { ?>
          <div class="col-xs-4">
          <?php foreach ($column as $city) { ?>
            <div class="prmn-cmngr-cities__city">
              <a class="prmn-cmngr-cities__city-name" data-id="<?= $city['fias_id']; ?>"
                <?= !empty($city['url']) ? 'href="' . $city['url'] . '"' : ''?>>
                  <?= $city['name']; ?>
              </a>
            </div>
          <?php } ?>
          </div>
        <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
