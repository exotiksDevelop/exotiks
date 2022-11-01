<?php
    header("Content-type: text/css; charset: UTF-8");
    include 'oca_back_to_top_var.php';
?>

.cd-top {
  display: inline-block;
  height: <?php echo $button_height; ?>px;
  width: <?php echo $button_width; ?>px;
  position: fixed;
  bottom: <?php echo $margin_bottom ; ?>px;
  right: <?php echo $margin_right ; ?>px;
  z-index: 10;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
  /* image replacement properties */
  overflow: hidden;
  text-indent: 100%;
  white-space: nowrap;
  background: #<?php echo $background; ?> url(cd-top-arrow.svg) no-repeat center 50%;
  visibility: hidden;
  opacity: 0;
  -webkit-transition: opacity .3s 0s, visibility 0s .3s;
  -moz-transition: opacity .3s 0s, visibility 0s .3s;
  transition: opacity .3s 0s, visibility 0s .3s;
}
.cd-top.cd-is-visible, .cd-top.cd-fade-out, .no-touch .cd-top:hover {
  -webkit-transition: opacity .3s 0s, visibility 0s 0s;
  -moz-transition: opacity .3s 0s, visibility 0s 0s;
  transition: opacity .3s 0s, visibility 0s 0s;
}
.cd-top.cd-is-visible {
  /* the button becomes visible */
  visibility: visible;
  opacity: 1;
}
.cd-top.cd-fade-out {
  /* if the user keeps scrolling down, the button is out of focus and becomes less visible */
  opacity: .5;
}
.no-touch .cd-top:hover {
  background-color: #e86256;
  opacity: 1;
}

<?php if(!$mobile_tablet) { ?>
@media only screen and (max-width: 768px) {
  .cd-top {
    display: none;
  }
}
<?php } ?>