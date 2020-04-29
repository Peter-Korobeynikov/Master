{$data = ""|fn_abt__ut2_get_microdata}
{if $data}
    <script type="application/ld+json">
        {$data|json_encode nofilter}
    </script>
{/if}
