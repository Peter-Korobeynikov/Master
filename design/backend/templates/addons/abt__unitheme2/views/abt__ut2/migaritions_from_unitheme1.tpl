{include file="addons/abt__unitheme2/views/abt__ut2/components/uni1_to_uni2/{$abt__ut2_action}.tpl"}
{include file="common/mainbox.tpl"
title=__("abt__ut2.migaritions_from_unitheme1.{$abt__ut2_action}")
title_start = __("abt__unitheme2")
title_end = __("abt__ut2.migaritions_from_unitheme1.{$abt__ut2_action}")
content=$smarty.capture.mainbox
buttons=$smarty.capture.buttons
content_id="abt__ut2_po_update_form"}