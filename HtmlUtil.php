<?php
  function formatPrice($price) {
    setlocale(LC_MONETARY, 'en_US');
    try {
      $price = money_format('%i',$price);
      $price = "$".substr($price, strpos($price," ")+1);
    } catch (Exception $e) {
      $error = "Exception: ".$e->getMessage();
    }
    return $price;
  }
  function paginateList($listHtml, $pageSize) {
    $listItemsHtml = strip_tags($listHtml, "<li><span><audio><source>");
    $numPages = "" + substr_count($listItemsHtml, "<li>");
    $numPages = (strpos($numPages, ".") != -1 ? intval(substr($numPages, strpos("."))) + 1 : intval($numPages));
    $pagerHtml = "<table id='tbl_pager_controls'><tr>";
    for ($i = 0; $i < $numPages; $i++) {
      $pagerHtml .= "<td>".$i."</td>";
    }
    $pagerHtml .= "</tr></table>\n";
    $startIndex = 0;
    $currentListItemLength = strpos($listItemsHtml, "</li>") + 5;
    $currentPageSize = 0;
    $currentPageIndex = 0;
    $pagerHtml .= "<ul id='page" + $currentPageIndex + "'>";
    while ($startIndex + $currentListItemLength < strlen($listItemsHtml) && $currentPageIndex < $numPages) {
      $pagerHtml .= substr($listItemsHtml, $startIndex, $currentListItemLength);
      $currentPageSize++;
      $startIndex += $currentListItemLength;
      if ($currentPageSize == $pageSize && $currentPageIndex < $numPages - 1) {
        $pagerHtml .= "</ul><ul id='page" + $currentPageIndex + "'>";
      } else {
        $pagerHtml .= "</ul>";
      }
    }
    return $pagerHtml;
  }
  function createDropdown($delim, $options, $values, $name, $filter, $multiple = false) {
    $options_array = explode(",", $options);
    $dd = "<select id='".$name."' name='".$name."'".($multiple ? " multiple='multiple'" : "").">\n";
    $i = 0;
    foreach ($options_array as $option) {
      $value = $values[$i];
      $filters = explode(",",$filter);
      foreach ($filters as $filter) {
        $dd .= "<option value='".$value."'".(array_key_exists($filter, $options_array) ? " selected='selected'" : "").">".$option."</option>\n";
      }
      $i++;
    }
    $dd .= "</select>\n";
    return $dd;
  }
  //$options contains the name value pairs to be selected
  function createRadioButtonList($delim, $options, $name, $selected = 'none') {
    $options_array = explode(",",$options);
    $ul = "<ul id='".$name."'>\n";
    foreach ($options_array as $option) {
      if ($selected != 'none' && $selected == $option["name"]) {
        $ul .= "<li><input type='radio' name='".$option["name"]."' value='".$option["value"]."' checked>\n";
      } else {
        $ul .= "<li><input type='radio' name='".$option["name"]."' value='".$option["value"]."'>\n";
      }
    }
    $ul .= "</ul>\n";
    return $ul;
  }
?>	