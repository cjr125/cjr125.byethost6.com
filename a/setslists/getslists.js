// Multiple Drop-Down Select Lists Script - http://coursesweb.net/
// Class to display multiple Drop-Down <select> lists
var GetSLists = function() {
  var thisob = this;        // stores the 'this' reference of the object with this class

  var optHierarchy = {'0':[]};          // stores items with 'parent_id':[child_IDs] for each <option> in <select> (starts with 'root')
  var optData = {'0':{'value':'Root', 'content':'', 'parent':-1}};

  var tableMysql = 0;     // if it is different from 0, represents table name, and gets data from MySQL
  var opt1 = 1;       // to know when is selected `1st option in <select>, 1 (modified in addSubSelect(), used in addCategory())
  var optID = 0;             // for the ID of current <option>
  var slevel = 1;          // level of current select lists
  var selID = 'sl1';       // id of current select list

/** Functions to get <select> data **/

  // adds 1st <select> with main options
  this.add1stSelect = function(from, resource) {
    // reset these properties couse after delete it is displayed 1st list
    optID = 0;
    slevel = 1;

    if(from == 'file') getSlistsTxt(resource);    // gets Drop-Down Lists from TXT file
    else if(from == 'mysql') {    // gets 1st Select from MySQL table
      tableMysql = resource;
      getSlistsMysql();
    }
  }

  // return <select> list with options-IDs in $options array in $optHierarchy
  var getSelect = function(options) {
    // starts current list
    var arrowsign = (slevel > 1) ? ' &rarr; ' : '';
    var slist = arrowsign +'<select onchange="obGSL.addSubSelect(this)" id="sl'+slevel+'" name="dslists[]"><option value="'+optID+'_'+'">---</option>';
    var nrarsubctg = options.length

    // traverse the object and create <option> with value=idctg_level
    for(var i in options) {
      if(optData[options[i]]) slist += '<option value="'+options[i]+'_'+optData[options[i]].value+'">'+optData[options[i]].value+'</option>';
    }

    return slist+'</select> <span id="n_sl'+(slevel+1)+'" class="dslists"></span>';
  }

  // function to get the Drop-Down list, receives the selected list object
  this.addSubSelect = function(slist) {
    selID = slist.id;
    slevel = 1 + selID.match(/[0-9]+/) * 1;         // sets the level of selected list in slevel property
    var opid_lvl = slist.value.split('_');      // separes id and parent-category of selected category /option
    optID = opid_lvl[0] * 1;           // id of selected option

    // if selected option is 1st <option> (value ends with '_'), empty the tag for next-select
    if(slist.value.match(/^[0-9]+_$/)) opt1 = 1;       // to know it was 1st <option>
    else  opt1 = 0;       // to know isn't 1st <option>
    document.getElementById('n_sl'+slevel).innerHTML = '';   // empty tag where will add next <select>
    document.getElementById('optioncnt').innerHTML = '';   // empty tag where will add option-content

    // if $tableMysql property is 0, use data from JS objects, else, calls getSlistsMysql()
    if(tableMysql == 0) {
      // if exists iitem of the selected option in $optHierarchy adds the <select> with <option>s and tag for next sub-<select>
      // else, empty the tag in which is added the sub-<select> of this <select>
      if(opt1 == 0 && optHierarchy[optID] && optHierarchy[optID].length > 0) {
        document.getElementById('n_sl'+slevel).innerHTML = getSelect(optHierarchy[optID]);
      }

      // if not 1st <option> and optData item, adds its Value and Content in form field, else, empty those fields
      if(opt1 == 0 && optData[optID]) {
        document.getElementById('optioncnt').innerHTML = optData[optID].content;
      }
      else document.getElementById('optioncnt').innerHTML = '';    //  option-content under <select>;
    }
    else if(opt1 == 0) getSlistsMysql();
  }

  // create the XMLHttpRequest object, according to browser
  function getXmlHttp() {
    var xmlHttp = null;           // will stere and return the XMLHttpRequest

    if(window.XMLHttpRequest) xmlHttp = new XMLHttpRequest();     // Forefox, Opera, Safari, ...
    else if(window.ActiveXObject) xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");     // IE

    return xmlHttp;
  }

  // gets JSON objects from 'file' and sets $optHierarchy and $optData with their data
  var getSlistsTxt = function(tofile) {
    var ajaxreq =  getXmlHttp();		// get XMLHttpRequest object

    ajaxreq.open("POST", tofile, true);			// create the request

    ajaxreq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");    // header for POST
    ajaxreq.send('&ajx=1');		//  make the ajax request, poassing the data

    // checks and receives the response
    ajaxreq.onreadystatechange = function() {
      // if valid JSON format, gets the JSON, array with two objects, for $optHierarchy and $optData
      if (ajaxreq.readyState == 4) {
        // if page not found, alert message, else, processes response-data
        if(ajaxreq.status == 404) alert('The resource: "'+ tofile +'" not found');
        else {
          // if valid JSON, sets the  $optHierarchy and $optData and adds the 1st select-list
          try {
            var jsonob = JSON.parse(ajaxreq.responseText);
            optHierarchy = jsonob[0];
            optData = jsonob[1];
            if(optHierarchy[0].length > 0) document.getElementById('n_sl1').innerHTML = getSelect(optHierarchy[0]);
          }
          catch(e) { alert('Incorrect JSON format'); }
        }
      }
    }
  }

  // gets and adds the required <select> and content of selected <option>
  var getSlistsMysql = function() {
    var ajaxreq =  getXmlHttp();		// get XMLHttpRequest object

    // message displayed till data is loaded, and empty option-content
    document.getElementById('n_sl'+ slevel).innerHTML = ' <em style="color:blue; font-weight:800;">Loading Data ...</em>';
    document.getElementById('optioncnt').innerHTML = '';

    ajaxreq.open("POST", 'setslists/setslists.php', true);			// create the request

    ajaxreq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");    // header for POST
    ajaxreq.send('resname='+tableMysql+'&idop='+optID+'&ajx=1');		//  make the ajax request, poassing the data

    // checks and receives the response
    ajaxreq.onreadystatechange = function() {
      if (ajaxreq.readyState == 4) {
        // if resource not exists, alerts message, else, gets the JSON, array with two arrays: 0=string_select_lists, 1=option_content
        if(ajaxreq.responseText == 'Resource Not Found') alert('The resource: "'+ tableMysql +'" not found');
        else {
          try {
            // gets an 2dArray with data, sets and adds the <select> with child-options, and content of selected option
            var jsonob = JSON.parse(ajaxreq.responseText);
            if(jsonob['idv'].length > 0) document.getElementById('n_sl'+ slevel).innerHTML = addSelect(jsonob['idv']);
            else document.getElementById('n_sl'+ slevel).innerHTML = '';    // delete message added till data is loaded
            document.getElementById('optioncnt').innerHTML = jsonob['content'];
          }
          catch(e) { alert('Incorrect JSON format'); }
        }
      }
    }
  }

  // return <select> list with options data in $options array
  var addSelect = function(options) {
    // startws current list
    var arrowsign = (slevel > 1) ? ' &rarr; ' : '';
    var slist = arrowsign +'<select onchange="obGSL.addSubSelect(this)" id="sl'+slevel+'" name="dslists[]"><option value="'+optID+'_'+'">---</option>';
    var nrarsubctg = options.length

    // traverse the object and create <option> with value=idctg_level
    for(var i in options) {
      slist += '<option value="'+options[i]+'">'+options[i].replace(/^[0-9]+_/, '')+'</option>';
    }

    return slist+'</select> <span id="n_sl'+(slevel+1)+'" class="dslists"></span>';
  }
}

// create object to MakeSLists
var obGSL = new GetSLists();