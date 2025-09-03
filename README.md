# Neuralab Branding plugin

Adds Neuralab branding to WordPress dashboard and login.

## Instructions

There are two ways to add the plugin:

1. Download option - Download the plugin from [the repo](https://github.com/neuralab/neuralab-branding).
__Note:__ Don't forget to rename the folder to `neuralab-branding-main` since GitHub adds the branch name to the folder name.
2. Clone option - Clone the plugin (`git clone git@github.com:neuralab/neuralab-branding.git`) to `wp-content/plugins` folder.
__Note:__ Don't forget to remove `.git` folder in the `neuralab-branding` folder.


## Add a.neuralab.site link to your themes footer

To add the _"a.neuralab.site"_ link to your themes footer place this to the desired location:
```php
  <?php
    if ( class_exists( 'NRLB_Branding' ) ) {
      echo nrlb_branding()->a_nrlb_site();
    }
  ?>
```

## Filters

`nrlb_branding_a_nrlb_site` - Filter the `HTML` markup of `nrlb_branding()->a_nrlb_site()` method:

```php
  <?php
    function pg_a_nrlb_site( $link, $url, $copy ) {
      return '<a class="my-class" rel="noopener" href="' . esc_url( $url ) . '">' . esc_html( $copy ) . '</a>';
    }
    add_filter( 'nrlb_branding_a_nrlb_site', 'pg_a_nrlb_site', 10, 3 );
  ?>
```

__Note:__ Don't forget to leave the `rel="noopener"` on the `<a>` tag if you need to filter the markup.
