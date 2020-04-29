{strip}
<script>
(function(_, $) {
$.extend(_, {
ab__dotd: {
current_dispatch: '{$runtime.controller}.{$runtime.mode}',
current_promotion_id: {$smarty.request.promotion_id|default:0},
max_height: '{$addons.ab__deal_of_the_day.max_height|intval|default:250|escape:"javascript"}',
more: '{"ab__dotd.more"|__|escape:"javascript"}',
less: '{"ab__dotd.less"|__|escape:"javascript"}',
}
});
}(Tygh, Tygh.$));
</script>
{/strip}
{script src="js/addons/ab__deal_of_the_day/func.js"}