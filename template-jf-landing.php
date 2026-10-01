<?php
/*
 * Template Name: JF Landing Page
 */
$post_id = get_the_ID();
$content = get_post_field( 'post_content', $post_id );
$robots        = get_post_meta( $post_id, '_yoast_wpseo_meta-robots-noindex', true );
$robots_follow = get_post_meta( $post_id, '_yoast_wpseo_meta-robots-nofollow', true );
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
.jf-landing-wrapper iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: none; display: block; }
.jf-loader { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #f5f5f5; z-index: 10; transition: opacity 0.3s ease; }
.jf-loader.hidden { opacity: 0; pointer-events: none; }
.jf-spinner { width: 32px; height: 32px; border: 3px solid #ddd; border-top-color: #666; border-radius: 50%; animation: jf-spin 0.8s linear infinite; }
@keyframes jf-spin { to { transform: rotate(360deg); } }
</style>
<?php do_action( 'wpseo_head' ); ?>
<?php if ( $robots == '1' ) : ?>
<meta name="robots" content="noindex<?php echo $robots_follow == '1' ? ', nofollow' : ''; ?>">
<?php endif; ?>
<link rel="preconnect" href="https://app.jumpfactor.co" crossorigin>
<link rel="preconnect" href="https://images.leadconnectorhq.com" crossorigin>
<link rel="preconnect" href="https://assets.cdn.filesafe.space" crossorigin>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
<link rel="dns-prefetch" href="https://app.jumpfactor.co">
<link rel="dns-prefetch" href="https://images.leadconnectorhq.com">
<link rel="dns-prefetch" href="https://assets.cdn.filesafe.space">
<link rel="dns-prefetch" href="https://stcdn.leadconnectorhq.com">
</head>
<body>
<main>
  <div class="jf-landing-wrapper">
    <div class="jf-loader" id="jf-loader" aria-hidden="true" nitro-exclude>
      <div class="jf-spinner"></div>
      <p style="margin:16px 0 0;font-family:sans-serif;font-size:14px;color:#999;">Loading...</p>
    </div>
    <?php echo wp_kses( $content, array(
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
    ) ); ?>
  </div>
</main>
<script nitro-exclude>
(function () {
  var done = false;

  function hideLoader() {
    if (done) return;
    done = true;
    var loader = document.getElementById('jf-loader');
    if (loader) loader.classList.add('hidden');
  }

  function attachIframeListener() {
    var iframe = document.querySelector('.jf-landing-wrapper iframe');
    if (!iframe) return;
    if (iframe.contentDocument && iframe.contentDocument.readyState === 'complete') {
      hideLoader();
      return;
    }
    var addEvt = iframe._nitroInp_addEventListener || iframe.addEventListener.bind(iframe);
    addEvt('load', hideLoader, { once: true });
  }

  setTimeout(hideLoader, 3000);

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', attachIframeListener);
  } else {
    attachIframeListener();
  }
})();
</script>
</body>
</html>
