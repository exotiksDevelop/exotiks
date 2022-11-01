var myMap;

var allPvzList = [];
var selectedTariff;
var selectedPvz;

function cdekPvzClick(tariff, pvzType) {
  cdekPvzUnSelect();
  selectedTariff = tariff;
  selectShippingMethod(selectedTariff);
  cdekymap.ready(initMap(pvzType));
  showHideMap(1);
}

function selectShippingMethod(tariff) {
  var inputVal = 'cdek.'+tariff;
  $("input[value='"+inputVal+"']").click();
}

function initMap (pvzType) 
{
    var pvzlist = cdekGetPvzList(pvzType);
    var mapcenter = [pvzlist[0].coordY, pvzlist[0].coordX];
    
    if (myMap) {
      destroyMap();
    }
    
    showHideMap(1);
    myMap = new cdekymap.Map('sdek_map', {
      center: mapcenter,
      zoom: 10,
      controls: ['zoomControl','fullscreenControl']
    }, {
      searchControlProvider: 'yandex#search'
    });   

    var iname = 1;
    pvzlist.forEach(function(item, i, arr) 
    {
      var description = '';
      var description = description + item.Address+'<BR>';
      var description = description + item.Phone+'<BR>';
      var description = description + item.WorkTime+'<BR>';
      var myGeoObject = new cdekymap.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [item.coordY, item.coordX]
            },
            // Свойства.
            properties: {
                // Контент метки.
                iconContent: iname,
                hintContent: description
            }
        }, {
            // Опции.
            // Иконка метки будет растягиваться под размер ее содержимого.
            preset: 'islands#blueIcon',
            // Метку можно перемещать.
            draggable: false
        });
      iname = iname+1;
      myGeoObject.events.add('click', function () 
      { 
        cdekPvzSelect(item.Code); 
      });
      myMap.geoObjects.add(myGeoObject);
    });
myMap.geoObjects.options.set("openBalloonOnClick", false);
}

function showHideMap(value)
{
  var modal = $('.sdek_modal_div');
  var overlay = $('#sdek_overlay');
  var modaldiv = $('#sdek_modal1');
  var mapdiv = $('#sdek_map');

  if(value == 1)
  {
     mapdiv.css({"width":"100%", "height":"100%"});
     overlay.fadeIn(400, //пoкaзывaем oверлэй
             function(){ // пoсле oкoнчaния пoкaзывaния oверлэя
                 modaldiv // берем стрoку с селектoрoм и делaем из нее jquery oбъект
                     .css('display', 'block') 
                     .animate({opacity: 1, top: '50%'}, 200); // плaвнo пoкaзывaем
         });

    var close = $('.sdek_modal_close, #sdek_overlay');
    $(close).click(function(){ destroyMap(); });
  }
  else
  {
    mapdiv.css({"width":"0", "height":"0"});
    modal // все мoдaльные oкнa
             .animate({opacity: 0, top: '45%'}, 200, // плaвнo прячем
                 function(){ // пoсле этoгo
                     $(this).css('display', 'none');
                     overlay.fadeOut(400); // прячем пoдлoжку
                 }
             );
  }
}

function destroyMap() {
  showHideMap(0);
  myMap.destroy();
}


function cdekPvzSelect(pvzCode) {
  $('.cdek_selectedPvzInfo').html('');

  selectedPvz = pvzCode;

  var selectedPvzItem = cdekGetPvzByCode(pvzCode);
  var pvzInfo = selectedPvzItem.Address+" "+selectedPvzItem.Phone;
  $("#cdek_selectedPvzInfo_"+selectedTariff).html(pvzInfo);
  $( "#sdek_pvz_input" ).val(pvzCode);
  $( "#sdek_pvzinfo_input" ).val(selectedPvzItem.Address+' tel:'+selectedPvzItem.Phone);
  
  destroyMap();

  sdek_shipping_continue();
}

function cdekPvzUnSelect() {
  $('.cdek_selectedPvzInfo').html('');
  $( "#sdek_pvz_input" ).val('');
  $( "#sdek_pvzinfo_input" ).val('');
}

function cdekGetPvzByCode(pvzCode) {
  var searchedPvz;

  allPvzList.forEach(function(item, i, arr) 
  {
    if(item.Code == pvzCode) {
      searchedPvz = item;
    }
  });

  return searchedPvz;
}

function cdekGetPvzList(pvzType) {
  var jsonstr = $('#sdek_pvzlist').text();
  allPvzList = jQuery.parseJSON(jsonstr);
  var pvzList = [];
  allPvzList.forEach(function(item, i, arr) 
  {
    if(item.Type == pvzType) {
      pvzList.push(item);
    }
  });
  return pvzList;
}

function sdek_shipping_continue()
{
  $.ajax({
    url: 'index.php?route=shipping/cdek/sessionAdd',
    type: 'post',
    data: $('#cdek_data input[type=\'radio\']:checked, #cdek_data textarea, #cdek_data input:hidden'),
    async: true,
    success: function(xhr, ajaxOptions, thrownError) 
    {
      console.log('cdek data remembered');
    },
    error: function(xhr, ajaxOptions, thrownError) 
    {
      console.log('sdek_shipping_continue error: '+thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  }); 
}

function sdek_chech_pvz()
{
  $status = 'good';
  $.ajax({
    url: 'index.php?route=shipping/cdek/chechPvz',
    type: 'post',
    data: $('input[name=\'shipping_method\'][type=\'radio\']:checked, input[name^=\'need_pvz\']'),
    dataType: 'json',  
    async: false,    
    success: function(json) 
    {
      console.log(json);
      if(json.status=='error')
      {
        alert(json.message);
        $status = 'bad';
      }
    },
    error: function(xhr, ajaxOptions, thrownError) 
    {
      console.log('sdek_chech_pvz error: '+thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  }); 

  return $status;
}