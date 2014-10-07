<?php

include('includes/config.php');

define('img_path', 'http://www.districtlines.com/designs/%ID%/%IMAGE%');
define('product_path', 'http://www.districtlines.com/%PRODUCT_ID%-%PRODUCT_CLEAN%/%VENDOR_CLEAN%');

class ParseXML{
      function GetChildren($vals, &$i) {
         $children = array(); // Contains node data
         if (isset($vals[$i]['value'])){
            $children['VALUE'] = $vals[$i]['value'];
         }

         while (++$i < count($vals)){
            switch ($vals[$i]['type']){

            case 'cdata':
               if (isset($children['VALUE'])){
                  $children['VALUE'] .= $vals[$i]['value'];
               } else {
                  $children['VALUE'] = $vals[$i]['value'];
               }
            break;

            case 'complete':
               if (isset($vals[$i]['attributes'])) {
                  $children[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
                  $index = count($children[$vals[$i]['tag']])-1;

                  if (isset($vals[$i]['value'])){
                     $children[$vals[$i]['tag']][$index]['VALUE'] = $vals[$i]['value'];
                  } else {
                     $children[$vals[$i]['tag']][$index]['VALUE'] = '';
                  }
               } else {
                  if (isset($vals[$i]['value'])){
                     $children[$vals[$i]['tag']] = $vals[$i]['value'];
                  } else {
                     $children[$vals[$i]['tag']] = '';
                  }
               }
            break;

            case 'open':
               if (isset($vals[$i]['attributes'])) {
                  $children[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
                  $index = count($children[$vals[$i]['tag']])-1;
                  $children[$vals[$i]['tag']][$index] = array_merge($children[$vals[$i]['tag']][$index],$this->GetChildren($vals, $i));
               } else {
                  $children[$vals[$i]['tag']][] = $this->GetChildren($vals, $i);
               }
            break;

            case 'close':
               return $children;
         }
      }
   }

      function GetXMLTree($xmlloc){
         if (file_exists($xmlloc)){
            $data = implode('', file($xmlloc));
         } else {
            $fp = fopen($xmlloc,'r');
            while(!feof($fp)){
               $data = $data . fread($fp, 1024);
            }

            fclose($fp);
         }

         $this->parse($data);
       }


			function parse($data) {
         $parser = xml_parser_create('ISO-8859-1');
         xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
         xml_parse_into_struct($parser, $data, $vals, $index);
         xml_parser_free($parser);

         $tree = array();
         $i = 0;

         if (isset($vals[$i]['attributes'])) {
            $tree[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
            $index = count($tree[$vals[$i]['tag']])-1;
            $tree[$vals[$i]['tag']][$index] =  array_merge($tree[$vals[$i]['tag']][$index], $this->GetChildren($vals, $i));
         } else {
            $tree[$vals[$i]['tag']][] = $this->GetChildren($vals, $i);
         }
      return $tree;
      }
}

	function quick_curl($url, $params = '', $debug = false) {
	    $ch = curl_init();    // initialize curl handle
	
	    curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
	    //curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects (security issue i guess)
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
	    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // times out after 5s
	    curl_setopt($ch, CURLOPT_POST, 1); // set POST method
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $params); // add POST fields
	    $result = curl_exec($ch); // run the whole process
	
	    if ($debug && curl_errno($ch)) {
	        print curl_errno($ch) . ': ' . curl_error($ch);
	    }
	
	    curl_close($ch);
	    return $result;
	}
	
	function curl_load($url, $params = '', $debug = false) {
	    $result = quick_curl($url, $params, $debug);
	    $parser = new ParseXML;
	    $rows = $parser->parse($result);
	    if ($debug) {
	        var_dump($rows);
	    }
	    return $rows['DATA'][0]['ROWS'][0]['ROW'];
	}
	
	function product($id) {
    if (is_array($id)) {
        $path = product_path;

        foreach ($id as $var => $val) {
            $path = str_replace('%' . strtoupper($var) . '%', $val, $path);
        }

        return $path;
    } else {
        return str_replace('%ID%', $id, product_path);
    }
}

function image($id, $image = null, $prefix = '') {
    return str_replace('%IMAGE%', $prefix . $image, str_replace('%ID%', $id, img_path));
}
	
    $result = curl_load('http://www.districtlines.com/api/?action=products&q_vendor_id=1886&o_date_added=DESC&limit=1&q_album_id=0&o_random=1&q_featured=1');
    $count = 0;
    if (is_array($result)) :
    	echo "<ul>";
        foreach ($result as $row) :
    	   $count++;
?>
				<li<?= $count == 4 || $count == 8 ? ' class="last"' : '' ?>><a href="<?= product($row) ?>" title="<?= $row['PRODUCT'] ?>"><img src="<?= image($row['PRODUCT_ID'],$row['IMAGE'],'browse_') ?>" alt="<?= $row['PRODUCT'] ?>" /><p class="buynow"><?=$row['PRODUCT'];?></p><span></span></a></li>
<?php
        endforeach;
        echo "</ul>";
    endif;
?>