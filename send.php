<script type="text/javascript">
   setTimeout(function(){$("#result").empty();},5000);  //30000 = 30 секунд*/
   
</script>


<?php
if (isset($_POST['name'])){ $name=$_POST['name']; }
if (isset($_POST['phone'])){ $phone=$_POST['phone']; }
if (isset($_POST['datecase'])){ $datecase=$_POST['datecase']; }



$name = stripslashes($name);
$datecase = stripslashes($datecase);
$phone = stripslashes($phone);


$name = htmlspecialchars ($name);
$datecase = htmlspecialchars ($datecase);
$phone = htmlspecialchars ($phone);


if ($name == '' or $phone=='' ){

echo "";
}
else {




// список получателей
	$address  = 'info@exotiks.ru, vildanabdrahmanov@yandex.ru' ;

	// Тема сообщения
	$subject = 'Заказ звонка';

	// Сообщение в виде HTML-формате
	$message =  "

Имя клиента: ".$name."
Телефон:".$phone." 
Время звонка.:".$datecase."
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