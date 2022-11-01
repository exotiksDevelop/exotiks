<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $download ?>" data-toggle="tooltip" title="<?php echo $button_download ?>" class="btn btn-primary"><i class="fa fa-download"></i></a>
        <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $clear; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_clear; ?>" class="btn btn-danger"><i class="fa fa-eraser"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-exclamation-triangle"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-cod" class="form-horizontal">
          <div class="form-group">
            <div class="col-sm-4">
                <img src="view/image/cdek/cdeklogo.png">
            </div>
            <div class="col-sm-8">
                <div class="alert alert-<?php echo $license_alert; ?>">
                <?php echo $license_status; ?>
                </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Имя пользователя:</label>
            <div class="col-sm-10">
              <input type="text" name="cdekLicense_user" value="" placeholder="" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Пароль:</label>
            <div class="col-sm-10">
              <input type="text" name="cdekLicense_password" value="" placeholder="" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Отправить</button>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <p>
                  Если Вы раннее не регистрировались в компании СДЭК как пользователь модуля, пожалуйста, перейдите на <a target="_blank" href="http://cdek-souz.ru/users/registrate/">страницу регистрации</a>. После прохождения процедуры идентификации, с Вами свяжется персональный менеджер, который будет сопровождать Ваш бизнес и помогать взаимодействовать с любым подразделением СДЭК.
                </p>
                <p>
                    Для заключении нового договора, Вы также можете заполнить <a target="_blank" href="http://cdek-souz.ru/files/kartochka_dlya_dogovora.xlsx">прилагаемую анкету</a> с реквизитами и направить её в отдел по работе с ключевыми клиентами СДЭК на e-mail: <b>integrator@cdek.im</b>. По любым вопросам, связанным с заключением договора и/или взаиморасчётам можно обращаться на бесплатный номер: <b>8-800-350-04-05</b>.
                </p>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>