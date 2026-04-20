<?php

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

if (empty($videos)) {
    echo '<p class="alert alert-warning">' . Text::_('MOD_CCPBIOSIM_YOUTUBE_NO_VIDEOS') . '</p>';
    return;
}

$showTitle  = (bool) $params->get('show_title', 1);
$showDesc   = (bool) $params->get('show_description', 1);
$descLength = (int)  $params->get('description_length', 120);
$openIn     = htmlspecialchars($params->get('open_in', '_blank'), ENT_QUOTES, 'UTF-8');
$autoplay   = (bool) $params->get('autoplay', 0);
$autoplayMs = (int)  $params->get('autoplay_interval', 5000);

$uid = 'mod-ccpbiosim-yt-' . $module->id;

$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_ccpbiosim.site');
?>

<div id="<?php echo $uid; ?>"
     class="youtube_mod"
     data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
     data-interval="<?php echo $autoplayMs; ?>"
     role="region"
     aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_ARIA_LABEL'); ?>">

    <?php foreach ($videos as $index => $video) :
        $safeTitle   = htmlspecialchars($video->title,        ENT_QUOTES, 'UTF-8');
        $safeThumb   = htmlspecialchars($video->thumbnail,    ENT_QUOTES, 'UTF-8');
        $safeUrl     = htmlspecialchars($video->url,          ENT_QUOTES, 'UTF-8');
        $safeEmbed   = htmlspecialchars($video->embedUrl,     ENT_QUOTES, 'UTF-8');
        $safeChannel = htmlspecialchars($video->channelTitle, ENT_QUOTES, 'UTF-8');

        $desc = $video->description;
        if ($descLength > 0 && mb_strlen($desc) > $descLength) {
            $desc = mb_substr($desc, 0, $descLength) . '&hellip;';
        }
        $safeDesc = htmlspecialchars($desc, ENT_QUOTES, 'UTF-8');

        $pubDate = '';
        if (!empty($video->publishedAt)) {
            try {
                $dt      = new \DateTime($video->publishedAt);
                $pubDate = $dt->format('j M Y');
            } catch (\Exception $e) {}
        }

        $views   = !empty($video->viewCount) ? number_format((int) $video->viewCount) : '';
        $isFirst = $index === 0;
    ?>
    <div class="youtube_mod_slide<?php echo $isFirst ? ' active' : ''; ?>"
         role="listitem"
         aria-label="<?php echo Text::sprintf('MOD_CCPBIOSIM_YOUTUBE_SLIDE_LABEL', $index + 1, count($videos)); ?>"
         aria-hidden="<?php echo $isFirst ? 'false' : 'true'; ?>">

        <div class="card border-0 shadow-sm">

            <!-- Thumbnail — swapped out for the iframe when play is clicked -->
            <div class="youtube_mod_thumb_wrap">
                <img src="<?php echo $safeThumb; ?>"
                     alt="<?php echo $safeTitle; ?>"
                     class="youtube_mod_thumb card-img-top"
                     loading="lazy"
                     width="480"
                     height="270" />

                <button class="youtube_mod_play_btn"
                        type="button"
                        data-embed="<?php echo $safeEmbed; ?>"
                        data-title="<?php echo $safeTitle; ?>"
                        aria-label="<?php echo Text::sprintf('MOD_CCPBIOSIM_YOUTUBE_PLAY_ARIA', $safeTitle); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68 48" aria-hidden="true" focusable="false">
                        <path class="youtube_mod_play_bg"    d="M66.5 7.8a8.5 8.5 0 0 0-6-6C56 .5 34 .5 34 .5S12 .5 7.5 1.8a8.5 8.5 0 0 0-6 6C.3 10.3 0 16.9 0 24s.3 13.7 1.5 16.2a8.5 8.5 0 0 0 6 6C12 47.5 34 47.5 34 47.5s22 0 26.5-1.3a8.5 8.5 0 0 0 6-6C67.7 37.7 68 31.1 68 24s-.3-13.7-1.5-16.2z"/>
                        <path class="youtube_mod_play_arrow" d="M45 24 27 14v20z"/>
                    </svg>
                </button>
            </div>

            <!-- Inline player — hidden until play is clicked, replaces the thumbnail area -->
            <div class="youtube_mod_player ratio ratio-16x9" style="display:none;">
                <iframe src=""
                        title=""
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                </iframe>
            </div>

            <div class="card-body">

                <?php if ($showTitle) : ?>
                <h5 class="card-title youtube_mod_title fw-semibold mb-2">
                    <a href="<?php echo $safeUrl; ?>"
                       target="<?php echo $openIn; ?>"
                       rel="noopener noreferrer">
                        <?php echo $safeTitle; ?>
                    </a>
                </h5>
                <?php endif; ?>

                <?php if ($showDesc && !empty($safeDesc)) : ?>
                <p class="card-text text-muted small youtube_mod_desc mb-3">
                    <?php echo $safeDesc; ?>
                </p>
                <?php endif; ?>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="badge bg-danger"><?php echo $safeChannel; ?></span>
                    <?php if ($views) : ?>
                    <small class="text-muted"><?php echo $views; ?> views</small>
                    <?php endif; ?>
                    <?php if ($pubDate) : ?>
                    <small class="text-muted ms-auto"><?php echo $pubDate; ?></small>
                    <?php endif; ?>
                </div>

            </div><!-- /.card-body -->

        </div><!-- /.card -->

    </div><!-- /.youtube_mod_slide -->
    <?php endforeach; ?>

    <!-- Carousel controls -->
    <div class="d-flex align-items-center justify-content-center gap-2 mt-3"
         aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_CONTROLS_ARIA'); ?>">

        <button id="<?php echo $uid; ?>-prev"
                class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center"
                style="width:38px;height:38px;"
                type="button"
                aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_PREV'); ?>"
                disabled>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true" focusable="false">
                <path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
            </svg>
        </button>

        <div class="youtube_mod_dots d-flex gap-2"
             role="tablist"
             aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_DOTS_ARIA'); ?>"
             id="<?php echo $uid; ?>-dots">
            <?php foreach ($videos as $index => $video) : ?>
            <button type="button"
                    role="tab"
                    class="youtube_mod_dot<?php echo $index === 0 ? ' active' : ''; ?>"
                    aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                    aria-label="<?php echo Text::sprintf('MOD_CCPBIOSIM_YOUTUBE_DOT_ARIA', $index + 1); ?>"
                    data-index="<?php echo $index; ?>">
            </button>
            <?php endforeach; ?>
        </div>

        <button id="<?php echo $uid; ?>-next"
                class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center"
                style="width:38px;height:38px;"
                type="button"
                aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_NEXT'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true" focusable="false">
                <path d="M10 6 8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
            </svg>
        </button>

    </div><!-- /.controls -->

    <!-- Autoplay progress bar -->
    <div class="youtube_mod_progress mt-2" aria-hidden="true">
        <div class="youtube_mod_bar" id="<?php echo $uid; ?>-bar"></div>
    </div>

</div><!-- /.youtube_mod -->

<script>
(function () {
    'use strict';

    var uid      = '<?php echo $uid; ?>';
    var carousel = document.getElementById(uid);
    if (!carousel) return;

    var allSlides = Array.from(carousel.querySelectorAll('.youtube_mod_slide'));
    var allDots   = Array.from(document.getElementById(uid + '-dots').querySelectorAll('.youtube_mod_dot'));
    var prevBtn   = document.getElementById(uid + '-prev');
    var nextBtn   = document.getElementById(uid + '-next');
    var bar       = document.getElementById(uid + '-bar');

    var total     = allSlides.length;
    var doAuto    = carousel.dataset.autoplay === 'true';
    var intMs     = parseInt(carousel.dataset.interval, 10) || 5000;
    var current   = 0;
    var autoTimer = null;

    // ── Stop any playing video in a slide and restore its thumbnail ────────
    function stopVideo(slide) {
        var thumbWrap = slide.querySelector('.youtube_mod_thumb_wrap');
        var player    = slide.querySelector('.youtube_mod_player');
        var iframe    = slide.querySelector('.youtube_mod_player iframe');
        if (!player || !thumbWrap || !iframe) return;
        iframe.src              = '';
        player.style.display    = 'none';
        thumbWrap.style.display = '';
    }

    // ── Go to slide ────────────────────────────────────────────────────────
    function goTo(index) {
        stopVideo(allSlides[current]);

        allSlides[current].classList.remove('active');
        allSlides[current].setAttribute('aria-hidden', 'true');
        if (allDots[current]) {
            allDots[current].classList.remove('active');
            allDots[current].setAttribute('aria-selected', 'false');
        }

        current = ((index % total) + total) % total;

        allSlides[current].classList.add('active');
        allSlides[current].setAttribute('aria-hidden', 'false');
        if (allDots[current]) {
            allDots[current].classList.add('active');
            allDots[current].setAttribute('aria-selected', 'true');
        }

        prevBtn.disabled = false;
        nextBtn.disabled = false;
    }

    // ── Autoplay ───────────────────────────────────────────────────────────
    function startAutoplay() {
        if (!doAuto) return;
        clearInterval(autoTimer);
        if (bar) {
            bar.style.transition = 'none';
            bar.style.width = '0%';
            requestAnimationFrame(function () {
                bar.style.transition = 'width ' + intMs + 'ms linear';
                bar.style.width = '100%';
            });
        }
        autoTimer = setInterval(function () { goTo(current + 1); startAutoplay(); }, intMs);
    }

    function resetAutoplay() { clearInterval(autoTimer); startAutoplay(); }

    // ── Carousel controls ──────────────────────────────────────────────────
    prevBtn && prevBtn.addEventListener('click', function () { goTo(current - 1); resetAutoplay(); });
    nextBtn && nextBtn.addEventListener('click', function () { goTo(current + 1); resetAutoplay(); });

    allDots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            goTo(parseInt(dot.dataset.index, 10));
            resetAutoplay();
        });
    });

    // Keyboard arrow navigation
    carousel.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowLeft')  { goTo(current - 1); resetAutoplay(); }
        if (e.key === 'ArrowRight') { goTo(current + 1); resetAutoplay(); }
    });

    // Touch / swipe
    var touchStartX = 0;
    carousel.addEventListener('touchstart', function (e) {
        touchStartX = e.changedTouches[0].clientX;
    }, { passive: true });
    carousel.addEventListener('touchend', function (e) {
        var dx = e.changedTouches[0].clientX - touchStartX;
        if (Math.abs(dx) > 40) { goTo(dx < 0 ? current + 1 : current - 1); resetAutoplay(); }
    }, { passive: true });

    // Pause autoplay while hovering or focused
    carousel.addEventListener('mouseenter', function () { clearInterval(autoTimer); });
    carousel.addEventListener('mouseleave', startAutoplay);
    carousel.addEventListener('focusin',    function () { clearInterval(autoTimer); });
    carousel.addEventListener('focusout',   startAutoplay);

    // ── In-place video player ──────────────────────────────────────────────
    // Clicking the play button hides the thumbnail and loads the iframe
    // directly inside the same card in its place. Navigating to another
    // slide (prev/next/dot) automatically stops the video and restores
    // the thumbnail via stopVideo() called inside goTo().
    carousel.addEventListener('click', function (e) {
        var btn = e.target.closest('.youtube_mod_play_btn');
        if (!btn) return;

        var slide     = btn.closest('.youtube_mod_slide');
        var thumbWrap = slide.querySelector('.youtube_mod_thumb_wrap');
        var player    = slide.querySelector('.youtube_mod_player');
        var iframe    = slide.querySelector('.youtube_mod_player iframe');
        if (!slide || !thumbWrap || !player || !iframe) return;

        clearInterval(autoTimer);

        iframe.src              = btn.dataset.embed + '?autoplay=1&rel=0';
        iframe.title            = btn.dataset.title || '';
        thumbWrap.style.display = 'none';
        player.style.display    = '';
    });

    // ── Init ──────────────────────────────────────────────────────────────
    goTo(0);
    startAutoplay();

}());
</script>
