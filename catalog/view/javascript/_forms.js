$(document).on('inputmaskLoaded', function() {
	console.log('inputmaskLoaded');
	
	$("#phone, #phone1, #phone2").inputmask({
	  "mask": "+7 (999) 999-99-99"
	});
});

// Марат-скрипт
function send() {
  //Получаем параметры
  if ($('#name').val() == '') {
    $('input#name').css('border', '1px solid red');
  };
  if ($('#phone').val() == '') { $('input#phone').css('border', '1px solid red'); };
  if ($('#name').val() != '' && $('#phone').val() != '') {
    var name = $('#name').val();
    var phone = $('#phone').val();
    // var datecase = $('#datecase').val();
    // Отсылаем паметры
    $.ajax({
      type: "POST",
      url: "send.php",
      data: "name=" + name + "&phone=" + phone,//+ "&datecase=" + datecase
      // Выводим то что вернул PHP
      success: function (html) {
        //предварительно очищаем нужный элемент страницы
        $("#result").empty();
        //и выводим ответ php скрипта
        $("#result").append(html);
        $('input#name').css('border', '1px solid #ccc');
        $('input#phone').css('border', '1px solid #ccc');
        $('#result').css('display', 'block');
        $('#form')[0].reset();
        $('#myModal').modal('hide');
        alert("Спасибо! Ваше сообщение отправлено.");
      }
    });

  };
}
function send1() {
  //Получаем параметры
  if ($('#name1').val() == '') { $('input#name1').css('border', '1px solid red'); $('input#name1').css('padding-left', '0px') };
  if ($('#phone1').val() == '') { $('input#phone1').css('border', '1px solid red'); };
  if ($('#name1').val() != '' && $('#phone1').val() != '') {
    var name = $('#name1').val();
    var phone = $('#phone1').val();
    var datecase = $('#datecase1').val();
    // Отсылаем паметры
    $.ajax({
      type: "POST",
      url: "send.php",
      data: "name=" + name + "&phone=" + phone + "&datecase=" + datecase,
      // Выводим то что вернул PHP
      success: function (html) {
        //предварительно очищаем нужный элемент страницы
        $("#result").empty();
        //и выводим ответ php скрипта
        $("#result").append(html);
        $('input#name1').css('border', '1px solid #ccc');
        $('input#phone1').css('border', '1px solid #ccc');
        $('#result').css('display', 'block');
        $('#form1')[0].reset();
        alert("Спасибо! Ваше сообщение отправлено.");
      }
    });

  };
}
function send2() {
  if ($('#name2').val() == '') { $('input#name2').css('border', '1px solid red'); };
  if ($('#textarea2').val() == '') { $('textarea#textarea2').css('border', '1px solid red'); };
  if ($('#name2').val() != '') {
    var name = $('#name2').val();
    var textarea = $('#textarea2').val();

    $.ajax({
      type: "POST",
      url: "send2.php",
      data: "name=" + name + "&textarea=" + textarea,
      success: function (html) {
        $("#result").empty();
        $("#result").append(html);
        $('input#name2').css('border', '1px solid #d1d1d1');
        $('input#textarea2').css('border', '1px solid #d1d1d1');
        $('#form2')[0].reset();
        alert("Спасибо! Ваше сообщение отправлено.");
      }
    });
  };
}