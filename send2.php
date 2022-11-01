<script type="text/javascript">
   setTimeout(function(){$("#result").empty();},5000);  //30000 = 30 секунд*/
   
</script>


<?php
if (isset($_POST['name'])){ $name=$_POST['name']; }
if (isset($_POST['textarea'])){ $textarea=$_POST['textarea']; }



$name = stripslashes($name);
$name = stripslashes($textarea);


$name = htmlspecialchars ($name);
$phone = htmlspecialchars ($textarea);


if ($name == '' or $textarea=='' ){

echo "";
}
else {




// список получателей
	$address  = 'info@exotiks.ru' ;

	// Тема сообщения
	$subject = 'Оставили отзыв';

	// Сообщение в виде HTML-формате
	$message =  "

Имя клиента: ".$name."
Отзыв:".$textarea."
";

$verify = mail($address,$subject,$message, "Content-type:text/plain; Charset=utf-8\r\n");

echo "<div id='spasibo'>
            <div class='bank1'>
            <h3>Большое спасибо!</h3>
            <p>Ваша заявка принята, наши менеджеры свяжутся<br/>
             с Вами в течении одного рабочего дня</p>
        </div> 
        </div>
 "; 
}


?>