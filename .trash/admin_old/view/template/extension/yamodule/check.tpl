<?php echo $header; ?><?php echo $column_left;
$is_good = true;
?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h3>Проверка настроек</h3>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="col-sm-10 col-sm-offset-0">
                    <div class="form-group">
                        Проверяется готовность модуля для приема платежей через Яндекс.Кассу.
                    </div>
            </div>
        </div>
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
            <tbody>
        <?php foreach ($listTests as $clsTest){ ?>
            		<tr>
            			<td><?php echo $$clsTest->getTitle();?></td>
                        <td>
                            <?php if($$clsTest->done){ ?> <div class="col-sm-12"><?php echo $$clsTest->getResult(); ?></div><?php } ?>
                            <div class="col-sm-12"><?php echo $$clsTest->getWarnHtml(); ?></div>
                        </td>
            		</tr>
        <?php if ($$clsTest->done === false) $is_good = false; ?>
        <?php } ?>
            <tr class="<?php echo (($is_good)?"success":"warning"); ?>">
                <td>Результат</td>
                <td><?php if ($is_good){ ?>
                        С настройками всё хорошо. Чтобы закончить проверку, сделайте тестовый платеж по инструкции от менеджера Яндекс.Кассы.
                    <?php }else{ ?>
                        <p>Есть ошибки. Поправьте их по рекомендациям выше и повторите проверку.</p>
                        <p>Если не получается исправить ошибки или у вас есть вопросы, напишите службе поддержки.</p>
                    <?php } ?>
                </td>
            </tr>
            </tbody>
            </table>
        </div>
        <?php if (!$is_good){ ?>
        <div class="row-fluid">
            <!-- -->
            <?php if (isset($error_sendmail)){ ?>
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $error_sendmail; ?>
                </div>
            <?php } ?>
            <?php if ($can_push == false || isset($error_expire)){?>
                <form action="" method="POST" class="form-horizontal" role="form">
                    <legend>Помощь с настройкой</legend>
                    <p>Здесь вы можете задать вопрос про настройку приема платежей через Яндекс.Кассу. Если ваша проблема не связана с настройкой, напишите о ней своему менеджеру на
                        <a href="mailto:merchants@money.yandex.ru">merchants@money.yandex.ru</a>.</p>
                    <div class="form-group form-inline">
                        <label class="col-sm-3" for="">Почта, с которой вы отправляете письмо</label>
                        <input type="email" class="form-control" name="from" id="" value="<?php echo $from_email;?>" disabled>
                    </div>

                    <div class="form-group form-inline <?php if (isset($error_email)) echo "has-error"; ?>">
                        <label class="col-sm-3" for="">Почта, на которую удобно получить ответ
                        <?php if (isset($error_email)){ ?><span class="help-block"><?php echo $error_email; ?></span><?php } ?></label>
                        <input type="email" class="form-control" name="email" value="<?php echo $email;?>">
                    </div>
                    <div class="form-group form-inline <?php if (isset($error_text)) echo "has-error"; ?>">
                        <label class="col-sm-3" for="">Текст письма
                        <?php if (isset($error_text)){ ?><span class="help-block"><?php echo $error_text; ?></span><?php } ?></label>
                        <textarea type="text" class="form-control" rows="5" cols="100" name="text" placeholder="Опишите проблему или задайте вопрос"><?php echo $text; ?></textarea>
                    </div>
                    <div about="form-group form-inline">
                        <p>Нажимая кнопку, вы соглашаетесь передать специалистам Яндекс.Кассы настройки магазина, результаты их проверки, данные о версии CMS Opencart, PHP, OpenSSL, cURL.</p>
                        <button type="submit" class="btn btn-primary">Отправить запрос</button>
                    </div>
                </form>
            <?php }else{ ?>
                <div class="alert alert-info">
                    <strong>Письмо отправлено.</strong> Форма обратной связи снова будет доступна <?php echo date ("d-m-Y в H:i:s", $can_push+60*60*24); ?> (спустя 24 часа после отправки).
                </div>
            <?php } ?>
            <!-- -->
        </div>
        <?php } ?>
    </div>
</div>
<?php echo $footer; ?>
