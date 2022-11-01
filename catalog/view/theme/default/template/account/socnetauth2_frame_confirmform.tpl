
<style>
h1
{    
	font-family: "Trebuchet MS","Arial";
	color: #0099FF;
    font-size: 24px;
	padding-left: 5px;
}

p
{
	font-family: "Trebuchet MS","Arial";
	color: #999999;
	padding-left: 5px;
}

td
{
	font-family: "Trebuchet MS","Arial";
	color: #000;
	padding: 5px;
}

input[type=submit]
{
	  background-color: #F0F0F0;
    /* background-image: url("/img/widget/button_bg.gif"); */
    background-repeat: repeat-x;
    border: 1px solid #C4C4C4;
    color: #838383;
    font-family: "Arial";
    font-size: 18px;
    font-weight: bold;
    padding: 5px;
}

input[type=text]
{
	border: 1px #ccc solid;
	width: 160px;
}


input[type=submit]:hover
{
	background-color: #F4FAFD;
    /* background-image: url("http://loginza.ru/img/widget/button_hover_bg.gif"); */
    border: 1px solid #D3ECFD;
    color: #2E9CD8;
}

.err
{
	color: red;
	font-family: "Trebuchet MS","Arial";
	font-weight: bold;
	padding-left: 5px;
}

</style>
<h1><?php echo $confirmform_header; ?></h1>
<p><?php echo $confirmform_message; ?></p>
<form action="<?php echo $action; ?>" method="POST">
<?php foreach($data as $key=>$val) { ?>
	<input type="hidden" name="data[<?php echo $key; ?>]" value='<?php 
	
	$val = str_replace("'", "\'", $val);
	$val = preg_replace("/[\\\]+\'/", "\'", $val);
	
	echo $val; ?>'>
<?php } ?>

<table>
<tr>
	<td><?php echo $confirmform_entry_code; ?>*</td>
	<td><input type="text" name="code" value=""><?php if( $error_code ) { ?><span class="err"><?php echo $error_code; 
	?></span><?php } ?></td>
</tr>
<tr>
	<td width=80></td>
	<td><input type="submit" value="<?php echo $confirmform_button; ?>"></td>
</tr>
</table>


</form>