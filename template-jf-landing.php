<?php
/*
 * Template Name: JF Landing Page
 */
$post_id = get_the_ID();
$content = get_post_field( 'post_content', $post_id );
$robots        = get_post_meta( $post_id, '_yoast_wpseo_meta-robots-noindex', true );
$robots_follow = get_post_meta( $post_id, '_yoast_wpseo_meta-robots-nofollow', true );

$content = wp_kses( $content, array(
  'iframe' => array(
    'src'            => array(),
    'title'          => array(),
    'loading'        => array(),
    'referrerpolicy' => array(),
    'allow'          => array(),
    'width'          => array(),
    'height'         => array(),
    'style'          => array(),
    'class'          => array(),
    'nitro-exclude'  => array(),
    'frameborder'    => array(),
  ),
) );

// The iframe fills the viewport, so never lazy-load it and keep NitroPack from lazy-loading it.
$content = preg_replace_callback( '/<iframe\b[^>]*>/i', function ( $m ) {
  $tag = preg_replace( '/\sloading=(["\'])lazy\1/i', '', $m[0] );
  if ( stripos( $tag, 'nitro-exclude' ) === false ) {
    $tag = preg_replace( '/^<iframe\b/i', '<iframe nitro-exclude', $tag );
  }
  return $tag;
}, $content );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
*, *::before, *::after { box-sizing: border-box; }
html, body { margin: 0; padding: 0; overflow: hidden; height: 100%; }
.jf-landing-wrapper { position: fixed; inset: 0; background: #f5f5f5; }
.jf-landing-wrapper iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: none; display: block; opacity: 0; transition: opacity 0.3s ease; }
.jf-landing-wrapper.is-ready iframe { opacity: 1; }
.jf-loader { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #f5f5f5; z-index: 10; transition: opacity 0.3s ease; }
.jf-landing-wrapper.is-ready .jf-loader { opacity: 0; pointer-events: none; }
.jf-spinner { width: 32px; height: 32px; border: 3px solid #ddd; border-top-color: #666; border-radius: 50%; animation: jf-spin 0.8s linear infinite; }
@keyframes jf-spin { to { transform: rotate(360deg); } }
</style>
<noscript><style>.jf-landing-wrapper iframe { opacity: 1; } .jf-loader { display: none; }</style></noscript>
<?php do_action( 'wpseo_head' ); ?>
<?php if ( $robots == '1' ) : ?>
<meta name="robots" content="noindex<?php echo $robots_follow == '1' ? ', nofollow' : ''; ?>">
<?php endif; ?>
<link rel="preconnect" href="https://app.jumpfactor.co">
</head>
<body>
<main>
  <div class="jf-landing-wrapper" id="jf-landing">
    <div class="jf-loader" aria-hidden="true" nitro-exclude>
      <div class="jf-spinner"></div>
      <p style="margin:16px 0 0;font-family:sans-serif;font-size:14px;color:#999;">Loading...</p>
    </div>
    <?php echo $content; ?>
  </div>
</main>
<script nitro-exclude>
(function () {
  var wrapper = document.getElementById('jf-landing');
  var shown = false;

  // Keep the iframe hidden until it has loaded so its internal layout shifts happen off-screen.
  function show() {
    if (shown) return;
    shown = true;
    wrapper.classList.add('is-ready');
  }

  // Mobile waits longer so the iframe's layout shifts stay hidden; desktop reveals sooner to keep Speed Index low.
  var isMobile = window.matchMedia('(max-width: 767px)').matches;
  var maxWait = setTimeout(show, isMobile ? 8000 : 3000);

  var iframe = wrapper.querySelector('iframe');
  if (!iframe) {
    show();
    return;
  }

  var addEvt = iframe._nitroInp_addEventListener || iframe.addEventListener.bind(iframe);
  addEvt('load', function () {
    clearTimeout(maxWait);
    setTimeout(show, 300);
  }, { once: true });
})();
</script>
</body>
</html>
