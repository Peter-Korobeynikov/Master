{strip}
<script>
    (function(_, $) {
        $(document).ready(function () {
            var abtam = $('div.abt__ut2_am');
            var ids = [];
            abtam.each(function () {
                ids.push($(this).attr('id'));
            });

            $.ceAjax('request', fn_url('abt__ut2.ajax_block_upload.load_menu'), {
                result_ids: ids.join(','),
                method: 'post',
                hidden: true,
                callback: function(data) { }
            });
        });
    }(Tygh, Tygh.$));
</script>
{/strip}