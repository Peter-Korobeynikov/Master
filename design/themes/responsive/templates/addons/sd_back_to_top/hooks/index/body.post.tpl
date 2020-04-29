{if !$sd_back_to_topEmbedded}
    <a href ="#" title="{__("up")}" class="sd-back-to-top {if $addons.sd_back_to_top.stripe_mode == 'left'}stripe stripe--left{else if $addons.sd_back_to_top.stripe_mode == 'right'}stripe stripe--right{else}stripe--off{/if} {if $addons.sd_back_to_top.elevator_pos == 'right'}elevator--right{else}elevator--left{/if}" id ="sd_back_to_top">
    <svg class="sd-back-to-top-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
        <path d="M32 24.414l16.293 16.293 1.414-1.56-17-17.147h-1.414l-17 17.146 1.414 1.488"/>
        <path d="M32 64c8.55 0 16.584-3.33 22.628-9.373S64 40.547 63.998 32c0-8.55-3.327-16.585-9.37-22.628C48.583 3.33 40.547 0 32 0 23.45 0 15.415 3.33 9.37 9.373 3.33 15.417 0 23.453.002 32c0 8.55 3.327 16.585 9.37 22.628C15.417 60.67 23.453 64 32 64zM10.786 10.787C16.452 5.12 23.987 2 32 2h.003c8.012 0 15.545 3.12 21.21 8.786C58.88 16.452 62 23.986 62 32c0 8.013-3.12 15.547-8.786 21.213S40.014 62 32 62h-.003c-8.012 0-15.545-3.12-21.21-8.786C5.12 47.548 2 40.014 2 32c0-8.013 3.12-15.547 8.787-21.213z"/>
    </svg>
    </a>
{/if}