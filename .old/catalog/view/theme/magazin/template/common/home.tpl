<?php echo $header; ?>
<div class="container" id="container">
    <div class="row slider_block">
		<div class="slider col-md-8 col-sm-6 hidden-xs">
            <?php echo $content_slider; ?>			
		</div>
        <div class="col-md-4 col-sm-6">
		<div class="slider_forma">
			<h2>Оставьте заявку<br/> и мы вам перезвоним</h2>
			<form class="form-horizontal" id="form1" role="form" action="">
                    <div class="col-sm-12">
                      <input type="text" class="form-control" name="name" id="name1" placeholder="Как Вас зовут?">
                      <input type="tel" class="form-control" name="phone" id="phone1" placeholder="Ваш номер телефона">
                      <input type="text" class="form-control input-sm" name="datecase" id="datecase1" placeholder="Укажите удобное время для звонка">
                      <input id="bottom1" class="btn btn-block" onclick="send1();" type="button" value="Заказать звонок">
                   
                </div>
            </form>
		</div>
        </div>
	</div>
	
	<div id="cart" class="content_body">
		
        <div class="akciya">			
            <?php echo $content_akcia; ?>
            <div class="clear"></div>
        </div>
        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="left">
                <?php echo $column_left; ?>
            </div>
        </div>
        <div class="col-md-8 col-sm-8 col-xs-12">
        <div class="right row">
            <div class="garantiya">
				<div class="garantiya_item col-md-3 col-sm-6 col-xs-12">
					<div class="img">
						<img src="garant1.png" alt="">
					</div>
					<h2>10000 довольных клиентов</h2>
					<p>  </p>
				</div>
				<div class="garantiya_item col-md-3 col-sm-6 col-xs-12">
					<div class="img">
						<img src="garant2.png" alt="">
					</div>
					<h2>Консультируем по уходу </h2>
					<p></p>
				</div>
				<div class="garantiya_item col-md-3 col-sm-6 col-xs-12">
					<div class="img">
						<img src="garant3.png" alt="">
					</div>
					<h2>Оплата при получении </h2>
					<p> </p>
				</div>
				<div class="garantiya_item col-md-3 col-sm-6 col-xs-12">
					<div class="img">
						<img src="garant4.png" alt="">
					</div>
					<h2>Доставляем по России</h2>
					<p></p>
				</div>
				
				
			<!--<br><br><br><center><h4>Доставка растений только по Москве и МО. По России возобновиться в апреле 2020 г.</h4></center>-->
<p><span style="color: rgb(0, 0, 0); font-family: &quot;Times New Roman&quot;, serif; font-size: 16px; font-weight: 700; text-align: justify;">Доставка по Москве и области</span></p>
<p><span style="color: rgb(0, 0, 0); font-family: &quot;Times New Roman&quot;, serif; font-size: 16px; font-weight: 700; text-align: justify;">Доставка растений по России только&nbsp;</span><span style="color: rgb(0, 0, 0); font-family: &quot;Times New Roman&quot;, serif; font-size: 16px; font-weight: 700; text-align: justify; background-color: yellow;">с апреля 2020 г</span></p>



                <div style="clear:both;"></div><center><img src="" width="100%" height="100%" alt=""></center>
			</div>
            <?php echo $content_top; ?>
        </div> 
        </div>
    <?php echo $content_bottom; ?>
<?php echo $footer; ?>
<div class="right">
			
			
			<div class="content_o">
				
            </div>
		</div>
		<div style="clear: both;"></div>
	</div>
</div>	
</div>