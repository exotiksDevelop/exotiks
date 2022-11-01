<?php
// True File Manager
// Версия 1.1.0
// Разработчик sitecreator.ru 2019(c)



?>
<div id="filemanager" class="modal-dialog" style="  width:auto; display: table;">
  <div class="modal-header" style="display:table-header-group;">
    <div style="background: #fbfbfb; padding: 10px 10px 10px 15px; border-radius: 2px 2px 0 0;">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">True File Manager <span style="font-size: 12px; color: #b0b0b0; font-weight: normal;">by Sitecreator.ru v. 1.1.0</span></h4>
  </div>
  </div>
  <div class="" style="display: table-row;">

    <div class="" style="display: table-cell;"><a id="elfinder_a_click" class="thumbnail" href="" style="display: none;"></a>
      <div id="elfinder"></div>
    </div>
  </div>
</div>


<script>

  //$('#modal-image').undelegate('a.thumbnail','click');

  if(typeof stcrtr_jQuery_3_4_1 === 'function' && typeof stcrtr_old_$  === 'function') {
    (function($, jQuery, old_$){

      if(typeof jQuery.ui !== "object" || typeof elFinder !== "function") {
        var txt  = 'jQuery.ui or/and elFinder not found';
        console.log(txt);
        alert(txt);
      }
      else {
        (function() {
          // not CKEditor +++++++++++++
          <?php if(empty($target) && empty($cke)){ ?>
          // Get the current selection
          var range = window.getSelection().getRangeAt(0);
          var node = range.startContainer;
          var startOffset = range.startOffset;  // where the range starts
          var endOffset = range.endOffset;      // where the range ends
          <?php } ?>
          // not CKEditor --------------


          var url = 'index.php?route=common/filemanager/connector&token=' + '<?php echo $token; ?>';
          var options = {
            url  : url,
            lang : 'en',
            ui: ['toolbar', 'places', 'tree', 'path', 'stat'],
            height: 600,
            resizable: true,
            commandsOptions : {
              getfile : {
                multiple : false,
                oncomplete : 'close'
              }
            },
            getFileCallback: function(files, fm){
              console.log('files:');
              console.log(files);
              var summernote_id = false;
              // если выбор файла, но не вставка изображения в редакторе
              <?php if (!empty($target)) { ?>
                <?php if (!empty($thumb)) { ?>
                  $('#<?php echo $thumb; ?>').find('img').attr('src', files.tmb);
                <?php } ?>
                var path = files.path;
                // for windows
                path = path.replace(/\\\\/g,'/');
                path = path.replace(/\\/g,'/');
                $('#<?php echo $target; ?>').attr('value', path);

                old_$('#elfinder_a_click').attr('href', files.url).trigger('click');
              //                old_$('#modal-image').modal('hide');  // лишнее
              <?php } else { ?>


                //CKEditor
                <?php if (!empty($cke)){ ?>
                var cke_target = '<?php echo $cke; ?>' || null;
                console.log(cke_target);
                cke_target = cke_target.split( ':' ); //link,txtUrl
                CKEDITOR.dialog.getCurrent().setValueOf(cke_target[0], cke_target[1], files.url);

                <?php }
              // not CKEditor +++++++++++++
                else  {
                if (!empty($summernote_id)) { ?>
                summernote_id = '<?php echo $summernote_id; ?>';
                <?php } ?>
                // Summer Note 0.8.* for opencart 2.3 sitecreator.ru

                if(typeof summernote === 'function' && summernote_id) {
                  $('#' + summernote_id).summernote('insertImage', files.url);
                }
                else {
                  // Summer Note 0.5.* for opencart 2.1 sitecreator.ru
                  // Create a new range from the orginal selection
                  var range = document.createRange();
                  range.setStart(node, startOffset);
                  range.setEnd(node, endOffset);

                  var img = document.createElement('img');
                  img.src = files.url;
                  range.insertNode(img);
                }
                <?php } ?>
              <?php } ?>


              old_$('#modal-image').modal('hide');
            }
          };
          $('#elfinder').elfinder(options);

        })();
      }
    })(stcrtr_jQuery_3_4_1, stcrtr_jQuery_3_4_1, stcrtr_old_$);
  }
  else {
    var txt  = 'stcrtr_jQuery_3_4_1 or/and stcrtr_old_$ not found';
    console.log(txt);
    alert(txt);
  }

</script>