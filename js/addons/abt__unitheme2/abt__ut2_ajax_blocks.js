/*******************************************************************************************
*   ___  _          ______                     _ _                _                        *
*  / _ \| |         | ___ \                   | (_)              | |              © 2019   *
* / /_\ | | _____  _| |_/ /_ __ __ _ _ __   __| |_ _ __   __ _   | |_ ___  __ _ _ __ ___   *
* |  _  | |/ _ \ \/ / ___ \ '__/ _` | '_ \ / _` | | '_ \ / _` |  | __/ _ \/ _` | '_ ` _ \  *
* | | | | |  __/>  <| |_/ / | | (_| | | | | (_| | | | | | (_| |  | ||  __/ (_| | | | | | | *
* \_| |_/_|\___/_/\_\____/|_|  \__,_|_| |_|\__,_|_|_| |_|\__, |  \___\___|\__,_|_| |_| |_| *
*                                                         __/ |                            *
*                                                        |___/                             *
* ---------------------------------------------------------------------------------------- *
* This is commercial software, only users who have purchased a valid license and  accept   *
* to the terms of the License Agreement can install and use this program.                  *
* ---------------------------------------------------------------------------------------- *
* website: https://cs-cart.alexbranding.com                                                *
*   email: info@alexbranding.com                                                           *
*******************************************************************************************/
(function(_, $) {
$(document).ready(function(){
$(".ut2-upload-block").each(function() {
var $this = $(this);
var data = {
object_dispatch: $this.data('ut2-object-dispatch'),
object_type: $this.data('ut2-object-type'),
object_id: $this.data("ut2-object-id")
};
$.ceAjax('request', fn_url("abt__ut2.ajax_block_upload." + $this.data("ut2-action") ), {
method: "post",
hidden: true,
data: data,
callback: function ( answer ) {
var res = answer.result;
if ( res != void(0) ) {
$this.append(res.html);
}
}
});
});
});
}(Tygh, Tygh.$));