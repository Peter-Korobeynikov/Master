{capture name="ab__adv_buttons_tools_list"}
<li>{btn type="list" text="{__("add_banner")} ({__("graphic_banner")} {__("or")} {__("text_banner")})" href="banners.add"}</li>
{hook name="banners:ab__adv_buttons_tools_list"}{/hook}
{/capture}
{dropdown content=$smarty.capture.ab__adv_buttons_tools_list class="" icon="icon-plus"}