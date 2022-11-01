<?php echo $header; ?>
<div id="content"><!-- модуль сдэк -->
  <div class="box">
    <img src="view/image/cdek/cdeklogo.png"/>
    <div class="content">
      <div class="box">
        <div class="content">
        <?php if($status) { ?>
        <h2 style="color: #2B6BFF!important;"><?php echo $message; ?></h2>
        <?php } else { ?> 
        <h2 style="color: #FB050E!important;"><?php echo $message; ?></h2>
        <?php } ?> 
        <p>
          Благодарим за выбор компании СДЭК в качестве службы доставки! Пожалуйста, введите e-mail и пароль, который Вы указали в Службе Поддержки Клиентов.
        </p>
          <form action="<?php echo $action; ?>" method="POST">
          <table class="list">
            <tr>
              <td class="left">Имя пользователя:</td>
              <td class="left"><input type="text" name="cdekLicense_user" value=""></td>
            </tr>
            <tr>
              <td class="left">Пароль:</td>
              <td class="left"><input type="text" name="cdekLicense_password" value=""></td>
            </tr>
            <tr>
              <td class="left"></td>
              <td class="left"><input type="submit"></td>
            </tr>
          </table>
          </form>
          <p>
            Если Вы раннее не регистрировались в компании СДЭК как пользователь модуля, пожалуйста, перейдите на <a target="_blank" href="http://cdek-souz.ru/users/registrate/">страницу регистрации</a>. После прохождения процедуры идентификации, с Вами свяжется персональный менеджер, который будет сопровождать Ваш бизнес и помогать взаимодействовать с любым подразделением СДЭК.
          </p>
          <p>
              Для заключении нового договора, Вы также можете заполнить <a target="_blank" href="http://cdek-souz.ru/files/kartochka_dlya_dogovora.xlsx">прилагаемую анкету</a> с реквизитами и направить её в отдел по работе с ключевыми клиентами СДЭК на e-mail: <b>integrator@cdek.im</b>. По любым вопросам, связанным с заключением договора и/или взаиморасчётам можно обращаться на бесплатный номер: <b>8-800-350-04-05</b>.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>