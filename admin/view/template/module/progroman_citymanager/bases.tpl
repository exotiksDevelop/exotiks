<h3><?= $text_databases; ?></h3>
<table class="table table-striped table-bordered">
  <tbody>
    <tr>
      <td><b><?= $base_ip->getName() ?></b></td>
      <td><?= $base_ip->getStatus() ?></td>
      <td>
        <?php /** @var \progroman\CityManager\DatabaseFileAction\DatabaseFileAction $action */ ?>
        <?php foreach ($base_ip->getActions() as $action) { ?>
          <a class="btn <?= $action->getCssClass() ?> base-action" title="<?= $action->getName() ?>" data-action="<?= $action->getActionId() ?>"
            data-text="<?= $action->getLoadingText() ?>..." data-step="upload">
            <i class="fa fa-<?= $action->getIcon() ?>"></i>
          </a>
        <?php } ?>
      </td>
    </tr>
  </tbody>
</table>

<h4><?= $text_database_cities; ?></h4>
<table class="table table-striped table-bordered">
  <tbody>
  <?php /** @var \progroman\CityManager\DatabaseFile\DatabaseFile $download_file */ ?>
  <?php foreach ($download_files as $download_file) { ?>
    <tr>
      <td><b><?= $download_file->getName() ?></b></td>
      <td><?= $download_file->getStatus() ?></td>
      <td>
        <?php /** @var \progroman\CityManager\DatabaseFileAction\DatabaseFileAction $action */ ?>
        <?php foreach ($download_file->getActions() as $action) { ?>
          <a class="btn <?= $action->getCssClass() ?> base-action" title="<?= $action->getName() ?>" data-action="<?= $action->getActionId() ?>"
             data-text="<?= $action->getLoadingText() ?>..." data-step="upload">
            <i class="fa fa-<?= $action->getIcon() ?>"></i>
          </a>
        <?php } ?>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>