{if ($runtime.controller == "block_manager" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["b9F9DGfS34E", "H8-3jFXHnIY", "0hrAdg8mZ2o", "QfNsC4vlPIE"] open=false}
{elseif ($runtime.controller == "themes" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["ujQk7z0awNk"] open=false}
{elseif ("ULTIMATE"|fn_allowed_for && $runtime.controller == "companies" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["2nN7DRQ5d8E"] open="ULTIMATE:FREE"|fn_allowed_for && $runtime.mode == 'manage' videos_link=true}
{elseif ($runtime.controller == "index" && $runtime.mode == "index")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["L2xJJ3zRgig", "xNSRtm55ekA", "ygiaNCPPT0w"] open=false}
{elseif ($runtime.controller == "seo_rules" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["yNEGtUM3sZs"] open=false}
{elseif ($runtime.controller == "categories" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["jBLJZrGVaAk", "21cYpyQZ248"] open=false}
{elseif ($runtime.controller == "products" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["_ZF4Wf_jSY4"] open=false}
{elseif ($runtime.controller == "products" && $runtime.mode == "update")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["ZyX60aPH8Kg"] open=false}
{elseif ($runtime.controller == "products" && $runtime.mode == "add")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["ZyX60aPH8Kg"] open=false}
{elseif ($runtime.controller == "settings_wizard" && $runtime.mode == "view")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["JqoZaeR29BA"] open=false}
{elseif ($runtime.controller == "menus" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["bL0e7bB17fM"] open=false}
{elseif ($runtime.controller == "templates" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["jk-XPTMTPKE"] open=false}
{elseif ($runtime.controller == "tabs" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["kXF-c5yorec"] open=false}
{elseif ($runtime.controller == "seo_redirects" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["HMyT67CuTKs"] open=false}
{elseif ($runtime.controller == "discussion_manager" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["kcDONAIcde0"] open=false}
{elseif ($runtime.controller == "sitemap" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["IIMa8iIsvh4"] open=false}
{elseif ($runtime.controller == "promotions" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["R7UDijsQjJ8", "mzEeklPWrRI", "Sbb-vjd4aEc"] open=false}
{elseif ($runtime.controller == "cart" && $runtime.mode == "cart_list")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["6jqFZ173JPY"] open=false}
{elseif ($runtime.controller == "newsletters" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["n3WRSRbtiNg"] open=false}
{elseif ($runtime.controller == "gift_certificates" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["9ozF0Kern9U"] open=false}
{elseif ($runtime.controller == "banners" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["VbJcUXLBlSw"] open=false}
{elseif ($runtime.controller == "profile_fields" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["lPsm4LmiUqA"] open=false}
{elseif ($runtime.controller == "shippings" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["mx1GYt_v8qk"] open=false}
{elseif ($runtime.controller == "payments" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["iocWxNnzTS0"] open=false}
{elseif ($runtime.controller == "orders" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["RxQv7AZ3eMM"] open=false}
{elseif ($runtime.controller == "languages" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["th0MFbmw_rw"] open=false}
{elseif ($runtime.controller == "languages" && $runtime.mode == "translations")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["th0MFbmw_rw"] open=false}
{elseif ($runtime.controller == "exim" && $runtime.mode == "export")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["fR-N7gbwrsY"] open=false}
{elseif ($runtime.controller == "exim" && $runtime.mode == "import")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["KAvcOkSfq70"] open=false}
{elseif ($runtime.controller == "settings" && $runtime.mode == "manage" && $smarty.request.section_id == "General")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["9Cbcz98CLLQ"] open=false}
{elseif ($runtime.controller == "settings" && $runtime.mode == "manage" && $smarty.request.section_id == "Appearance")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["e31Gqduf8E4"] open=false}
{elseif ($runtime.controller == "settings" && $runtime.mode == "manage" && $smarty.request.section_id == "Company")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["LqzMQmdh8MI"] open=false}
{elseif ($runtime.controller == "settings" && $runtime.mode == "manage" && $smarty.request.section_id == "Stores")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["LqzMQmdh8MI"] params="&start=62" open=false}
{elseif ($runtime.controller == "settings" && $runtime.mode == "manage" && $smarty.request.section_id == "Checkout")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["mSan90fzgDk"] open=false}
{elseif ($runtime.controller == "settings" && $runtime.mode == "manage" && $smarty.request.section_id == "Emails")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["JGWn6mm2ESI"] open=false}
{elseif ($runtime.controller == "settings" && $runtime.mode == "manage" && $smarty.request.section_id == "Thumbnails")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["3QkZqI8ACig"] open=false}
{elseif ($runtime.controller == "settings" && $runtime.mode == "manage" && $smarty.request.section_id == "Security")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["Tkm7hTBew4c"] open=false}
{elseif ($runtime.controller == "settings" && $runtime.mode == "manage" && $smarty.request.section_id == "Sitemap")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["KUyg54ZmCBo"] open=false}
{elseif ($runtime.controller == "settings" && $runtime.mode == "manage" && $smarty.request.section_id == "Upgrade_center")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["5SKkeuZlmr4"] open=false}
{elseif ($runtime.controller == "settings" && $runtime.mode == "manage" && $smarty.request.section_id == "Logging")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["JqoZaeR29BA"] open=false}
{elseif ($runtime.controller == "settings" && $runtime.mode == "manage" && $smarty.request.section_id == "Reports")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["JqoZaeR29BA"] open=false}
{elseif ($runtime.controller == "discussion" && $runtime.mode == "update" && $smarty.request.discussion_type == "E")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["kcDONAIcde0"] open=false}
{elseif ($runtime.controller == "profiles" && $runtime.mode == "manage" && $smarty.request.user_type == "A")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["JNe_YhHyQ48"] open=false}
{elseif ($runtime.controller == "profiles" && $runtime.mode == "manage" && $smarty.request.user_type == "C")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["PnZ4AdYXzTM", "lom4xHHsS3o"] open=false}
{elseif ($runtime.controller == "file_editor" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["sOKSbZAcTAU"] open=false}
{elseif ($runtime.controller == "pages" && $runtime.mode == "manage" && $smarty.request.get_tree == "multi_level" && $smarty.request.page_type != "B")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["oJj1k790Kj0", "c3KH4UOBCK0", "whCqKKghECc"] open=false}
{elseif ($runtime.controller == "pages" && $runtime.mode == "manage" && $smarty.request.get_tree == "multi_level" && $smarty.request.page_type == "B")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["7esgMkMLCbc"] open=false}
{elseif ($runtime.controller == "product_filters" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["ZRFmJlxtGQ0"] params="&start=3" open=false}
{elseif ($runtime.controller == "product_features" && $runtime.mode == "manage")}
    {include file="addons/help_tutorial/components/video_sidebar.tpl" items=["b9c_K3oldHg"] params="&start=2" open=false}
{/if}