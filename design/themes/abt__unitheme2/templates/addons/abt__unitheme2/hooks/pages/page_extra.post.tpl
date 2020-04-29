{if $page.abt__ut2_microdata_schema_type}
    <script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "{$page.abt__ut2_microdata_schema_type}",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{"pages.view?page_id=`$page.page_id`"|fn_url}"
    },
    "headline": "{$page.page|escape:"javascript" nofilter}",
    "image": "{$page.main_pair.icon.image_path|default:$logos.theme.image.image_path}",
    "datePublished": "{$page.timestamp|date_format:"%Y-%m-%d"}",
    "dateModified": "{$page.timestamp|date_format:"%Y-%m-%d"}",
    "author": {
        "@type": "Person",
        "name": "{$page.author|default:$runtime.company_data.company|escape:"javascript" nofilter}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "{$runtime.company_data.company|escape:"javascript" nofilter}",
        "logo": {
            "@type": "ImageObject",
            "url": "{$logos.theme.image.image_path}"
        }
    },
    "description": "{$page.description|strip_tags|truncate:380:"..."|escape:"javascript" nofilter}"
}
</script>
{/if}