<?php

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

if (empty($videos)) {
    echo '<p class="alert alert-warning">' . Text::_('MOD_CCPBIOSIM_YOUTUBE_NO_VIDEOS') . '</p>';
    return;
}

$showTitle   = (bool) $params->get('show_title', 1);
$showDesc    = (bool) $params->get('show_description', 1);
$descLength  = (int)  $params->get('description_length', 120);
$openIn      = htmlspecialchars($params->get('open_in', '_blank'), ENT_QUOTES, 'UTF-8');
$autoplay    = (bool) $params->get('autoplay', 0);
$autoplayMs  = (int)  $params->get('autoplay_interval', 5000);

$uid = 'mod-ccpbiosim-yt-' . $module->id;
?>

<div id="<?php echo $uid; ?>"
     class="mod-ccpbiosim-youtube"
     data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
     data-interval="<?php echo $autoplayMs; ?>"
     role="region"
     aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_ARIA_LABEL'); ?>">

    <?php foreach ($videos as $index => $video) :
        $safeTitle   = htmlspecialchars($video->title,    ENT_QUOTES, 'UTF-8');
        $safeThumb   = htmlspecialchars($video->thumbnail, ENT_QUOTES, 'UTF-8');
        $safeUrl     = htmlspecialchars($video->url,       ENT_QUOTES, 'UTF-8');
        $safeEmbed   = htmlspecialchars($video->embedUrl,  ENT_QUOTES, 'UTF-8');
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

        $views = !empty($video->viewCount)
            ? number_format((int) $video->viewCount)
            : '';

        $isFirst = $index === 0;
    ?>
    <div class="ytc-slide<?php echo $isFirst ? ' active' : ''; ?>"
         role="listitem"
         aria-label="<?php echo Text::sprintf('MOD_CCPBIOSIM_YOUTUBE_SLIDE_LABEL', $index + 1, count($videos)); ?>"
         aria-hidden="<?php echo $isFirst ? 'false' : 'true'; ?>">

        <div class="card border-0 shadow-sm">

            <div class="ytc-thumb-wrap">
                <img src="<?php echo $safeThumb; ?>"
                     alt="<?php echo $safeTitle; ?>"
                     class="card-img-top"
                     loading="lazy"
                     width="480"
                     height="270" />

                <button class="ytc-play-btn"
                        type="button"
                        data-embed="<?php echo $safeEmbed; ?>"
                        data-title="<?php echo $safeTitle; ?>"
                        aria-label="<?php echo Text::sprintf('MOD_CCPBIOSIM_YOUTUBE_PLAY_ARIA', $safeTitle); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68 48" aria-hidden="true" focusable="false">
                        <path class="ytc-play-bg" d="M66.5 7.8a8.5 8.5 0 0 0-6-6C56 .5 34 .5 34 .5S12 .5 7.5 1.8a8.5 8.5 0 0 0-6 6C.3 10.3 0 16.9 0 24s.3 13.7 1.5 16.2a8.5 8.5 0 0 0 6 6C12 47.5 34 47.5 34 47.5s22 0 26.5-1.3a8.5 8.5 0 0 0 6-6C67.7 37.7 68 31.1 68 24s-.3-13.7-1.5-16.2z"/>
                        <path class="ytc-play-arrow" d="M45 24 27 14v20z"/>
                    </svg>
                </button>
            </div>

            <div class="card-body">

                <?php if ($showTitle) : ?>
                <h5 class="card-title ytc-title fw-semibold mb-2">
                    <a href="<?php echo $safeUrl; ?>"
                       target="<?php echo $openIn; ?>"
                       rel="noopener noreferrer">
                        <?php echo $safeTitle; ?>
                    </a>
                </h5>
                <?php endif; ?>

                <?php if ($showDesc && !empty($safeDesc)) : ?>
                <p class="card-text text-muted small ytc-desc mb-3">
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

            </div>

        </div>

    </div>
    <?php endforeach; ?>

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

        <div class="ytc-dots d-flex gap-2"
             role="tablist"
             aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_DOTS_ARIA'); ?>"
             id="<?php echo $uid; ?>-dots">
            <?php foreach ($videos as $index => $video) : ?>
            <button type="button"
                    role="tab"
                    class="ytc-dot<?php echo $index === 0 ? ' active' : ''; ?>"
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

    </div>

    <div class="ytc-progress mt-2" aria-hidden="true">
        <div class="ytc-bar" id="<?php echo $uid; ?>-bar"></div>
    </div>

</div>

<div id="<?php echo $uid; ?>-lightbox"
     class="ytc-lightbox"
     style="display:none;"
     role="dialog"
     aria-modal="true"
     aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_LIGHTBOX_ARIA'); ?>">
    <div class="ytc-lightbox-inner">
        <button class="ytc-lightbox-close btn-close btn-close-white"
                type="button"
                id="<?php echo $uid; ?>-close"
                aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_CLOSE'); ?>">
        </button>
        <div class="ytc-player ratio ratio-16x9">
            <iframe id="<?php echo $uid; ?>-iframe"
                    src=""
                    title=""
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                    loading="lazy">
            </iframe>
        </div>
    </div>
</div>

<style>
#<?php echo $uid; ?> .ytc-slide          { display: none; }
#<?php echo $uid; ?> .ytc-slide.active   { display: block; }

#<?php echo $uid; ?> .ytc-thumb-wrap {
    position: relative;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    background: #000;
    border-radius: var(--bs-card-inner-border-radius, .375rem) var(--bs-card-inner-border-radius, .375rem) 0 0;
}

#<?php echo $uid; ?> .ytc-thumb-wrap img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
    transition: transform .4s ease, opacity .4s ease;
}

#<?php echo $uid; ?> .card:hover .ytc-thumb-wrap img {
    transform: scale(1.04);
    opacity: .88;
}

#<?php echo $uid; ?> .ytc-play-btn {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    background: transparent; border: none; cursor: pointer; padding: 0;
}

#<?php echo $uid; ?> .ytc-play-btn svg {
    width: 68px; height: 48px;
    filter: drop-shadow(0 2px 8px rgba(0,0,0,.5));
    transition: transform .2s ease, filter .2s ease;
}

#<?php echo $uid; ?> .ytc-play-btn:hover svg,
#<?php echo $uid; ?> .ytc-play-btn:focus-visible svg {
    transform: scale(1.1);
    filter: drop-shadow(0 4px 16px rgba(0,0,0,.7));
}

#<?php echo $uid; ?> .ytc-play-bg    { fill: rgba(20,20,20,.78); transition: fill .2s; }
#<?php echo $uid; ?> .ytc-play-btn:hover .ytc-play-bg { fill: #dc3545; }
#<?php echo $uid; ?> .ytc-play-arrow { fill: #fff; }

#<?php echo $uid; ?> .ytc-title {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

#<?php echo $uid; ?> .ytc-title a            { color: inherit; text-decoration: none; }
#<?php echo $uid; ?> .ytc-title a:hover,
#<?php echo $uid; ?> .ytc-title a:focus      { color: #dc3545; text-decoration: underline; }

#<?php echo $uid; ?> .ytc-desc {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

#<?php echo $uid; ?> .ytc-dot {
    width: 10px; height: 10px; border-radius: 50%;
    border: 2px solid #adb5bd; background: transparent;
    padding: 0; cursor: pointer;
    transition: background .2s, border-color .2s, transform .2s;
}

#<?php echo $uid; ?> .ytc-dot.active,
#<?php echo $uid; ?> .ytc-dot:hover {
    background: #dc3545;
    border-color: #dc3545;
    transform: scale(1.3);
}

#<?php echo $uid; ?> .ytc-dot:focus-visible {
    outline: 2px solid #dc3545;
    outline-offset: 3px;
}

#<?php echo $uid; ?> .ytc-progress {
    height: 3px;
    background: #dee2e6;
    border-radius: 2px;
    overflow: hidden;
}

#<?php echo $uid; ?> .ytc-bar {
    height: 100%;
    background: #dc3545;
    border-radius: 2px;
    width: 0;
}

#<?php echo $uid; ?>-lightbox {
    background: rgba(0,0,0,.88);
    border-radius: var(--bs-border-radius-lg, .5rem);
    padding: 2.5rem 1rem 1rem;
    margin-top: 1rem;
    position: relative;
}

#<?php echo $uid; ?>-lightbox .ytc-lightbox-close {
    position: absolute;
    top: .6rem;
    right: .75rem;
}

#<?php echo $uid; ?>-lightbox .ytc-player iframe {
    border: none;
    border-radius: var(--bs-border-radius, .375rem);
}

@media (prefers-reduced-motion: reduce) {
    #<?php echo $uid; ?> .ytc-thumb-wrap img,
    #<?php echo $uid; ?> .ytc-play-btn svg,
    #<?php echo $uid; ?> .ytc-dot,
    #<?php echo $uid; ?> .ytc-bar { transition: none !important; }
}
</style>

<script>
(function () {
    'use strict';

    const uid      = '<?php echo $uid; ?>';
    const carousel = document.getElementById(uid);
    if (!carousel) return;

    const allSlides = Array.from(carousel.querySelectorAll('.ytc-slide'));
    const allDots   = Array.from(document.getElementById(uid + '-dots').querySelectorAll('.ytc-dot'));
    const prevBtn   = document.getElementById(uid + '-prev');
    const nextBtn   = document.getElementById(uid + '-next');
    const bar       = document.getElementById(uid + '-bar');
    const lightbox  = document.getElementById(uid + '-lightbox');
    const iframe    = document.getElementById(uid + '-iframe');
    const closeBtn  = document.getElementById(uid + '-close');

    const total    = allSlides.length;
    const doAuto   = carousel.dataset.autoplay === 'true';
    const intMs    = parseInt(carousel.dataset.interval, 10) || 5000;
    let   current  = 0;
    let   autoTimer = null;

    function goTo(index) {
        allSlides[current].classList.remove('active');
        allSlides[current].setAttribute('aria-hidden', 'true');
        allDots[current] && allDots[current].classList.remove('active');
        allDots[current] && allDots[current].setAttribute('aria-selected', 'false');

        current = ((index % total) + total) % total;

        allSlides[current].classList.add('active');
        allSlides[current].setAttribute('aria-hidden', 'false');
        allDots[current] && allDots[current].classList.add('active');
        allDots[current] && allDots[current].setAttribute('aria-selected', 'true');

        prevBtn.disabled = false;
        nextBtn.disabled = false;
    }

    function startAutoplay() {
        if (!doAuto) return;
        clearInterval(autoTimer);
        if (bar) {
            bar.style.transition = 'none';
            bar.style.width = '0%';
            requestAnimationFrame(() => {
                bar.style.transition = 'width ' + intMs + 'ms linear';
                bar.style.width = '100%';
            });
        }
        autoTimer = setInterval(function () { goTo(current + 1); startAutoplay(); }, intMs);
    }

    function resetAutoplay() { clearInterval(autoTimer); startAutoplay(); }

    prevBtn && prevBtn.addEventListener('click', function () { goTo(current - 1); resetAutoplay(); });
    nextBtn && nextBtn.addEventListener('click', function () { goTo(current + 1); resetAutoplay(); });

    allDots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            goTo(parseInt(dot.dataset.index, 10));
            resetAutoplay();
        });
    });

    // Keyboard
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

    // Pause on hover / focus
    carousel.addEventListener('mouseenter', function () { clearInterval(autoTimer); });
    carousel.addEventListener('mouseleave', startAutoplay);
    carousel.addEventListener('focusin',    function () { clearInterval(autoTimer); });
    carousel.addEventListener('focusout',   startAutoplay);

    carousel.addEventListener('click', function (e) {
        var btn = e.target.closest('.ytc-play-btn');
        if (!btn || !lightbox || !iframe) return;
        iframe.src   = btn.dataset.embed + '?autoplay=1&rel=0';
        iframe.title = btn.dataset.title || '';
        lightbox.style.display = 'block';
        clearInterval(autoTimer);
        if (closeBtn) closeBtn.focus();
    });

    function closeLightbox() {
        if (!lightbox || !iframe) return;
        iframe.src = '';
        lightbox.style.display = 'none';
        startAutoplay();
    }

    closeBtn  && closeBtn.addEventListener('click', closeLightbox);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && lightbox && lightbox.style.display !== 'none') closeLightbox();
    });

    goTo(0);
    startAutoplay();

}());
</script>
