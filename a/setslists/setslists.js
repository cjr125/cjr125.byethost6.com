// Multiple Drop-Down Select Lists Creator Script - http://coursesweb.net/

// Class to create multiple Drop-Down <select> lists
var MakeSLists = function() {
  // messages displayed
  var lang = {
    'erraddnct': 'Add a value in text field for option',
    'errjson': 'Incorrect JSON format',
    'errchg0': 'Root cannot be changed.',
    'errm_name': 'Add a name for resource, between 2 and 250 characters.\n Only Letters, Numbers, "-", and "_"',
    'dchanged':'Data changed',
    'delitem': 'The selected Option and its Sub-options succesfuly deleted'
  }
  var thisob = this;        // stores the 'this' reference of the object with this class

  var geto = 'txt';         // if 'mysql' value, gets data from MySQL database
  var saveto = 'txt';       // to tell php where to save data
  var optHierarchy = {'0':[]};          // stores items with 'parent_id':[child_IDs] for each <option> in <select> (starts with 'root')
  var optData = {'0':{'value':'Root', 'content':'', 'parent':-1}};

  var opt1 = 1;       // to know when is selected `1st option in <select>, 1 (modified in addSubSelect(), used in addCategory())
  var optID = 0;             // for the ID of current <option>
  var slevel = 1;          // level of current select lists
  var selID = 'sl1';       // id of current select list

/** Functions to set <select> data **/

  // empty option data
  var resetData = function() {
    document.getElementById('opval').value = '';    // option value
    document.getElementById('opcnt').value = '';    // option content
    document.getElementById('optioncnt').innerHTML = '';    //  option-content under <select>
  }

  // registers new option and its content
  function newOption() {
    var opval = document.getElementById('opval').value.replace(/\</g, '&lt;').replace(/\>/g, '&gt;').replace(/"/g, '&quot;');    // option value
    var opcnt = document.getElementById('opcnt').value;    // option content

    if(opval.length > 0) {
      // adds it in $optData. Gets the last-index, to set /add id of new category
      var idop = 0;
      for(var ix in optData) {
        if((ix * 1) > idop) idop = ix * 1;
      }
      idop++;

      // stores data of new option
      optData[idop] = {'value':opval, 'content':opcnt, 'parent':optID};

      // if parent-category exists in $optHierarchy, adds it in array, else, create the parent-category array
      if(optHierarchy[optID]) optHierarchy[optID].push(idop);
      else optHierarchy[optID] = [idop];

      // sets the level of <select> which is displayed with the new category ($opt1=1 indicates the selection is current <select>)
      slevel = (opt1 == 1) ? selID.match(/[0-9]+/) * 1 : slevel;

      // adds the <select> with <option>s and tag for next sub-<select>
      if(optHierarchy[optID].length > 0) document.getElementById('n_sl'+ slevel).innerHTML = getSelect(optHierarchy[optID]);

      // shows message 'data added' for 1 sec., then resets select data
      document.getElementById('optioncnt').innerHTML = ' <div style="margin-left:28%;font-weight:800;text-align:center;color:blue;">Option data Added</div>';
      setTimeout(function() {
        resetData();          // empty <select> and setting data
        document.getElementById('curentitem').innerHTML = optData[optID].value;    // display curent selected item
      }, 1110);
    }
    else {
      // focus text field, and message
      document.getElementById('opval').focus();
      alert(lang.erraddnct);
    }
  }

  // modify the value and content of selected <option>
  function changeOptData() {
    var opval = document.getElementById('opval').value.replace(/\</g, '&lt;').replace(/\>/g, '&gt;').replace(/"/g, '&quot;');    // option value (replace <>" with their html-encodes)
    var opcnt = document.getElementById('opcnt').value;    // option content

    // if not root
    if(optID != 0) {
      // change the name and link in optData, to the id of category
      optData[optID].value = opval;
      optData[optID].content = opcnt;
      var parentid = optData[optID].parent;
      optID = parentid;       // sets current-id to parentid couse refreshes current <select>
      opt1 = 1;     // to know it is on 1st option in <select>
      slevel--;

      // re-adds the <select> with <option>s, in the <span> with current <select>
      if(optHierarchy[parentid].length > 0) document.getElementById('n_sl'+slevel).innerHTML = getSelect(optHierarchy[parentid]);

      resetData();          // empty category-name, reset link url

      document.getElementById('curentitem').innerHTML = optData[optID].value;    // display curent selected item
      alert(lang.dchanged);
    }
    else alert(lang.errchg0);
  }

  // delete the selected <option> (including all its child-options), delete also its id in array of its parent in $optHierarchy
  function deleteOpt() {
    // if not root
    if(optID > 0) {
      var parentid = optData[optID].parent;   // gets parent-ID
      delete optData[optID];
      delete optHierarchy[optID];

      // traverses the items in parent to remove deleted item
      var remainctg = [];          // stores the remaining items, to be added in parent
      for(var i in optHierarchy[parentid]) {
        if(optHierarchy[parentid][i] != optID) remainctg.push(optHierarchy[parentid][i]);
      }
      optHierarchy[parentid] = remainctg;

      addSelCtgs();      // resets data and adds 1st <select> list
      resetData();          // empty fields for option data

      document.getElementById('curentitem').innerHTML = optData[0].value;    // display curent selected Option
      alert(lang.delitem);
    }
    else alert(lang.errchg0);
  }

  // resets data and adds 1st <select> with main categories
  function addSelCtgs() {
    // reset these properties couse after delete it is displayed 1st list
    optID = 0;
    slevel = 1;

    // if items in Root, shows 1st <select>, else, resets properties to initial value
    if(optHierarchy[0].length > 0) document.getElementById('n_sl'+slevel).innerHTML = getSelect(optHierarchy[0]);
    else {
      optHierarchy = {'0':[]};
      optData = {'0':{'value':'Root', 'content':'', 'parent':-1}};
      document.getElementById('n_sl'+slevel).innerHTML = '';
    }
  }

  // return <select> list with options-IDs in $arsubop array in $optHierarchy, receives the array, and the ID for <select>
  var getSelect = function(arsubop) {
    var nrarsubctg = arsubop.length;      // number of options

    // starts current list
    var slist = '<div><div><sub>Level '+ slevel +' / '+ nrarsubctg +' Op</sub><select onchange="obMSL.addSubSelect(this)" id="sl'+slevel+'" name="dslists[]"><option value="'+optID+'_'+'">---</option>';

    // traverse the object and create <option> with value=idctg_level
    for(var i in arsubop) {
      if(optData[arsubop[i]]) slist += '<option value="'+arsubop[i]+'_'+optData[arsubop[i]].value+'">'+optData[arsubop[i]].value+'</option>';
    }

    return slist+'</select></div><span id="n_sl'+(slevel+1)+'"></span></div>';
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

    // if exists iitem of the selected option in $optHierarchy adds the <select> with <option>s and tag for next sub-<select>
    // else, empty the tag in which is added the sub-<select> of this <select>
    if(opt1 == 0 && optHierarchy[optID] && optHierarchy[optID].length > 0) {
      document.getElementById('n_sl'+slevel).innerHTML = getSelect(optHierarchy[optID]);
    }
    else document.getElementById('n_sl'+slevel).innerHTML = '';

    // if not 1st <option> and optData item, adds its Value and Content in form field, else, empty those fields
    if(opt1 == 0 && optData[optID]) {
      document.getElementById('opval').value = optData[optID].value.replace(/&lt;/ig, '<').replace(/&gt;/ig, '>').replace(/&quot;/ig, '"');
      document.getElementById('opcnt').value = optData[optID].content;
      document.getElementById('optioncnt').innerHTML = optData[optID].content;
    }
    else resetData();

    document.getElementById('curentitem').innerHTML = optData[optID].value;    // display curent selected item
  }

/** Get-Codes functions **/

  // returns a string with JSON code of objects $optHierarchy and $optData
  var setJsonStr = function() {
    return '['+ JSON.stringify(optHierarchy) +', '+ JSON.stringify(optData) +']';
  }

  // adds in #sqlcode textarea the SQL code that can be used to store $optData in SQL database
  var setSql = function() {
    var addcode = 'CREATE TABLE `table_name` (`id` INT UNSIGNED PRIMARY KEY, `value` CHAR(255) NOT NULL DEFAULT "", `content` VARCHAR(5000) NOT NULL DEFAULT "", `parent` INT NOT NULL DEFAULT 0) CHARACTER SET utf8 COLLATE utf8_general_ci;\nINSERT INTO `table_name` (`id`, `value`, `content`, `parent`) VALUES ';

    // sets to adds the items data from optData as rows in SQL
    for(var i in optData) {
      addcode += "("+ i +", '"+ optData[i].value +"', '"+ optData[i].content +"', "+ optData[i].parent +"),";
    }

    return addcode.replace(/,$/, '');      // returns the code, deleting the last ','
  }

  // to add codes in textareas
  this.addTxtarea = function() {
    document.getElementById('jsoncode').value = setJsonStr();
    document.getElementById('dslinpage').value = document.getElementById('dslinpage').value.replace(/var sliststr = ([\w\W\d\s]*);  \/\/op_data/ig, 'var sliststr = '+ setJsonStr() +';  \/\/op_data');    // 
    document.getElementById('sqlcode').value = setSql();
  }

/** Get, Save, Ajax and Others **/

  // gets Drop-Down lists saved in TXT file
  this.getData = function() {
    var resname = document.getElementById('getslists').value;        // resource name
    resetData();          // empty <select> and setting data

    // if $geto is 'txt', get data directly via Ajax, else, if 'mysql', gets with Ajax via php
    if(geto == 'txt') Ajax(0, 'get', 'slists/'+ resname +'.txt');
    else if(geto == 'mysql') Ajax('resname='+ resname +'&geto='+ geto, 'get', 'setslists/setslists.php');

    thisob.tabShowHide('sets', 'gets', 'codes');    // switch to Set Data Tab
    document.getElementById('resname').value = resname;       // add resource name in text-field for saving data
  }

  // gets and pass data to Ajax, to be saved on server
  this.saveData = function() {
    // if it is added name for resource to save
    var resname = document.getElementById('resname');
    if(resname.value.match(/^[a-z0-9_-]{2,250}$/i)) {
      document.getElementById('btnsave').style.visibility = 'hidden';     // hides the Save button

      // sets data (pass categories structure in JSON)
      var datasend = 'resname='+ resname.value +'&sdata='+ encodeURIComponent(setJsonStr()) +'&saveto='+ saveto +'&admname='+ document.getElementById('admname').value +'&admpass='+ document.getElementById('admpass').value;
      Ajax(datasend, 'save', 'setslists/setslists.php');
    }
    else {
      resname.focus();
      alert(lang.errm_name);
    }
  }

  // create the XMLHttpRequest object, according to browser
  function getXmlHttp() {
    var xmlHttp = null;           // will stere and return the XMLHttpRequest

    if(window.XMLHttpRequest) xmlHttp = new XMLHttpRequest();     // Forefox, Opera, Safari, ...
    else if(window.ActiveXObject) xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");     // IE

    return xmlHttp;
  }

  // sends data to PHP and receives the response
  function Ajax(datasend, req, tofile) {
    var ajaxreq =  getXmlHttp();		// get XMLHttpRequest object

    ajaxreq.open("POST", tofile, true);			// create the request

    ajaxreq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");    // header for POST
    ajaxreq.send(datasend +'&ajx=1');		//  make the ajax request, poassing the data

    // checks and receives the response
    ajaxreq.onreadystatechange = function() {
      if (ajaxreq.readyState == 4) {
        // if page not found, alert message, else, processes response-data
        if(ajaxreq.status == 404 || ajaxreq.responseText == 'Resource Not Found') alert('The resource: "'+ document.getElementById('getslists').value +'" not found');
        else {
          // if $req is 'get', request to get data (JSON with two arrays, for $optHierarchy, and $optData)
          // else, request to save data, and alerts response
          if(req == 'get') {
            // gets the JSON, array with two objects, for $optHierarchy and $optData
            try {
              var jsonob = JSON.parse(ajaxreq.responseText);
              optHierarchy = jsonob[0];
              optData = jsonob[1];

              addSelCtgs();    // resets data and adds 1st <select> with main categories
            }
            catch(e) { alert(lang.errjson); }
          }
          else if(req == 'save') {
            document.getElementById('btnsave').style.visibility = 'visible';     // shows the Save button
            alert(ajaxreq.responseText);
          }
        }
      }
    }
  }

  // for tab effec, receives the ID of element to show and IDs of elements to hide
  this.tabShowHide = function(sid, hid1, hid2) {
    document.getElementById(sid).style.display = 'block';
    document.getElementById(hid1).style.display = 'none';
    document.getElementById(hid2).style.display = 'none';

    // add class="tabvi" to tab that shows, and deletes it from the other tabs
    document.getElementById('tab'+sid).setAttribute('class', 'tabvi');
    document.getElementById('tab'+hid1).removeAttribute('class');
    document.getElementById('tab'+hid2).removeAttribute('class');
  }

  // to register events
  this.regEvets = function() {
    // to not submit forms
    document.getElementById('fgets').onsubmit = function(){ return false;};
    document.getElementById('fset').onsubmit = function(){ return false;};
    document.getElementById('savedata').onsubmit = function(){ return false;};

    // for tab efect
    document.getElementById('tabgets').onclick = function(){ thisob.tabShowHide('gets', 'sets', 'codes');};
    document.getElementById('tabsets').onclick = function(){ thisob.tabShowHide('sets', 'gets', 'codes');};
    document.getElementById('tabcodes').onclick = function(){
      thisob.tabShowHide('codes', 'gets', 'sets');
      thisob.addTxtarea();
    };
    // tabs-codes
    document.getElementById('tabchtml').onclick = function(){ thisob.tabShowHide('chtml', 'cjson', 'csql');};
    document.getElementById('tabcjson').onclick = function(){ thisob.tabShowHide('cjson', 'chtml', 'csql');};
    document.getElementById('tabcsql').onclick = function(){ thisob.tabShowHide('csql', 'chtml', 'cjson');};

    // button to add/modify/delete option lists
    document.getElementById('newopt').onclick = newOption;
    document.getElementById('chgdata').onclick = changeOptData;
    document.getElementById('delopt').onclick = deleteOpt;

    // for radio buttons that set from which resource type to get data
    document.getElementById('getxt').onclick = function(){ geto = this.value;};
    document.getElementById('getmysql').onclick = function(){ geto = this.value;};

    document.getElementById('btnget').onclick = thisob.getData;     // button to get data

    // for radio buttons that set where to save data
    document.getElementById('totxt').onclick = function(){ saveto = this.value;};
    document.getElementById('tomysql').onclick = function(){ saveto = this.value;};
    document.getElementById('totxtmysql').onclick = function(){ saveto = this.value;};

    document.getElementById('btnsave').onclick = thisob.saveData;     // button to save data

    // to auto select data in textarea
    document.getElementById('dslinpage').onfocus = function(){ this.select();};
    document.getElementById('jsoncode').onfocus = function(){ this.select();};
    document.getElementById('dslintxt').onfocus = function(){ this.select();};
    document.getElementById('sqlcode').onfocus = function(){ this.select();};
    document.getElementById('dslinmysql').onfocus = function(){ this.select();};
  }

  this.regEvets();
}

// create object to MakeSLists
var obMSL = new MakeSLists();