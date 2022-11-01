<?php echo $header; ?>
<style type="text/css">
  .content1{
    display: none; 
    min-height: 0px!important;
    border-left: 4px solid #5690CC;
    padding-left: 10px;
  }
  .content1 .content1{
    display: none; 
    min-height: 0px!important;
    border-left: 4px solid #475C94;
    padding-left: 15px;
  }
  .content1 .qestion {
    font-size: 20px;
    border-left: 4px solid #FBD66C;
    padding-left: 10px;
  }
  .content1 .answer {
    margin-left: 20px;
    font-size: 15px;
    border-left: 4px solid #68D876;
    padding-left: 10px;
  }
</style>
<div id="content">
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" />Документация</h1>
      <div class="buttons"></div>
    </div>
    <div class="content">

      <?php foreach ($sections as $key => $value) { ?>
        <div class="box">
        <div class="heading">
          <h1><a class="linkh" data-hidediv="<?php echo $key; ?>"><?php echo $value['label']; ?></a></h1>
          <div class="buttons"></div>
        </div>
        <div class="content1 hidden-div" id="<?php echo $key; ?>">
          <?php echo $value['text']; ?>
          <?php if(isset($value['child'])) { ?>
            <?php foreach ($value['child'] as $keychild => $valuechild) { ?>
              <div class="box">
              <div class="heading">
                <h1><a class="linkh" data-hidediv="<?php echo $key.$keychild; ?>"><?php echo $valuechild['label']; ?></a></h1>
                <div class="buttons"></div>
              </div>
              <div class="content1 hidden-div" id="<?php echo $key.$keychild; ?>">
                <?php echo $valuechild['text']; ?>
              </div>
              </div>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
      <?php } ?>

    </div>
  </div>
</div>
<script>

$('.linkh').click(function(){
  var divid = $(this).data("hidediv");
  $("#"+divid).slideToggle("slow");
});
</script>
<?php echo $footer; ?>