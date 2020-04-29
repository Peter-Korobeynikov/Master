{capture name="mainbox"}
    <div>{__("hidpi.text_restore_images")}</div>
    <div>{__("hidpi.warning_restore_images")}</div>
    <br>
    {btn type="text" class="cm-ajax cm-comet btn btn-primary" text=__("hidpi.restore_btn") href="hidpi.restore" method="POST"}
{/capture}

{include file="common/mainbox.tpl" title=__("hidpi.restore_images") content=$smarty.capture.mainbox}