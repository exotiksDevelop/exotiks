var rusChars = new Array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ч','ц','ш','щ','э','ю','я','ы','ъ','ь', ' ', '\'', '\"', '\#', '\$', '\%', '\&', '\*', '\,', '\:', '\;', '\<', '\>', '\?', '\[', '\]', '\^', '\{', '\}', '\|', '\!', '\@', '\(', '\)', '\-', '\=', '\+', '\/', '\\','.');
var transChars = new Array('a','b','v','g','d','e','jo','zh','z','i','j','k','l','m','n','o','p','r','s','t','u','f','h','ch','c','sh','csh','e','u','ya','y','', '', '_', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

function convert2EN(from)
{
  from = from.toLowerCase();
  var to = "";
  var len = from.length;
  var character, isRus;
  for(var i=0; i < len; i++)
    {
    character = from.charAt(i,1);
    isRus = false;
    for(var j=0; j < rusChars.length; j++)
      {
      if(character == rusChars[j])
        {
        isRus = true;
        break;
        }
      }
    to += (isRus) ? transChars[j] : character;
    }
  return to;
}

$(document).ready(function() {
	$('#input-name1, #input-title1').change(function() {
	  	if ($("#input-keyword").val()=='') $("#input-keyword").val(convert2EN($(this).val()));
	});
});