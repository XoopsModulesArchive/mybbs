<?

define("DATE_FORMAT", "y/m/d H:i");

// テキストエリア
//define("_DHTML_NO_DHTML_CODE",true);         // ← link とか img タグ
//define("_DHTML_NO_DHTML_CODE_FONT",true);    // ← フォント
//define("_DHTML_NO_DHTML_CODE_SMILY",false);   // ← smily

function get_form()
{
    switch ($_SERVER["REQUEST_METHOD"]) {
        case "POST":
            $val =& $_POST;
            break;
        case "GET":
            $val =& $_GET;
            break;
        default:
            $val =& $_REQUEST;
            break;
    }
   if (function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc()) {
        $val =& stripslashes_array(&$val);
    }
    return $val;
}

function stripslashes_array(&$given)
{
    return is_array($given) ? array_map('stripslashes_array', &$given) : stripslashes(&$given);
}

// debug print
function print_d($val)
{
    echo "<pre>";
    print_r($val);
    echo "</pre>";
}

// テキストエリア
//require_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
//require_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/formdhtmltextareale.php";
//$tarea = new XoopsFormDhtmlTextAreaLe('', 'message', $value='', $rows=5, $cols=40, $hiddentext="xoopsHiddenText");
//$form_message_tarea = $tarea->render();
//$xoopsTpl->assign ('form_message_tarea',$form_message_tarea);
?>
