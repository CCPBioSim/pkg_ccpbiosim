<?php

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

if (empty($videos)) {
    echo '<p class="mod-yt-carousel__error">' . Text::_('MOD_CCPBIOSIM_YOUTUBE_NO_VIDEOS') . '</p>';
    return;
}

$showTitle       = (bool) $params->get('show_title', 1);
$showDescription = (bool) $params->get('show_description', 1);
$descLength      = (int)  $params->get('description_length', 120);
$openIn          = $params->get('open_in', '_blank');
$autoplay        = (bool) $params->get('autoplay', 0);
$autoplayMs      = (int)  $params->get('autoplay_interval', 5000);

$uid = htmlspecialchars($moduleId, ENT_QUOTES, 'UTF-8');
?>

<div id="<?php echo $uid; ?>" class="mod-yt-carousel" data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>" data-interval="<?php echo $autoplayMs; ?>" aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_ARIA_LABEL'); ?>" role="region">

    <!-- Carousel track -->
    <div class="mod-yt-carousel__viewport" aria-live="polite">
        <ul class="mod-yt-carousel__track" role="list">
            <?php foreach ($videos as $index => $video) :
                $safeTitle = htmlspecialchars($video->title, ENT_QUOTES, 'UTF-8');
                $safeThumbnail = htmlspecialchars($video->thumbnail, ENT_QUOTES, 'UTF-8');
                $safeUrl = htmlspecialchars($video->url, ENT_QUOTES, 'UTF-8');
                $safeEmbed = htmlspecialchars($video->embedUrl, ENT_QUOTES, 'UTF-8');
                $desc = $video->description;
                if ($descLength > 0 && mb_strlen($desc) > $descLength) {
                    $desc = mb_substr($desc, 0, $descLength) . '…';
                }
                $safeDesc = htmlspecialchars($desc, ENT_QUOTES, 'UTF-8');
                $isActive = $index === 0;
            ?>
            <li class="mod-yt-carousel__slide<?php echo $isActive ? ' is-active' : ''; ?>"
                role="listitem"
                aria-label="<?php echo Text::sprintf('MOD_CCPBIOSIM_YOUTUBE_SLIDE_LABEL', $index + 1, count($videos)); ?>"
                aria-hidden="<?php echo $isActive ? 'false' : 'true'; ?>">

                <div class="mod-yt-carousel__card">

                    <!-- Thumbnail / play button -->
                    <div class="mod-yt-carousel__thumb-wrap">
                        <img
                            src="<?php echo $safeThumbnail; ?>"
                            alt="<?php echo $safeTitle; ?>"
                            class="mod-yt-carousel__thumb"
                            loading="lazy"
                            width="480"
                            height="270"
                        />
                        <button
                            class="mod-yt-carousel__play-btn"
                            data-embed="<?php echo $safeEmbed; ?>"
                            data-title="<?php echo $safeTitle; ?>"
                            aria-label="<?php echo Text::sprintf('MOD_CCPBIOSIM_YOUTUBE_PLAY_ARIA', $safeTitle); ?>"
                            type="button"
                        >
                            <!-- Play icon SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68 48" aria-hidden="true" focusable="false">
                                <path class="mod-yt-carousel__play-bg" d="M66.5 7.8a8.5 8.5 0 0 0-6-6C56 .5 34 .5 34 .5S12 .5 7.5 1.8a8.5 8.5 0 0 0-6 6C.3 10.3 0 16.9 0 24s.3 13.7 1.5 16.2a8.5 8.5 0 0 0 6 6C12 47.5 34 47.5 34 47.5s22 0 26.5-1.3a8.5 8.5 0 0 0 6-6C67.7 37.7 68 31.1 68 24s-.3-13.7-1.5-16.2z"/>
                                <path class="mod-yt-carousel__play-arrow" d="M45 24 27 14v20z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Card body -->
                    <div class="mod-yt-carousel__body">
                        <?php if ($showTitle) : ?>
                        <h3 class="mod-yt-carousel__title">
                            <a href="<?php echo $safeUrl; ?>"
                               target="<?php echo htmlspecialchars($openIn, ENT_QUOTES, 'UTF-8'); ?>"
                               rel="noopener noreferrer">
                                <?php echo $safeTitle; ?>
                            </a>
                        </h3>
                        <?php endif; ?>

                        <?php if ($showDescription && !empty($safeDesc)) : ?>
                        <p class="mod-yt-carousel__desc"><?php echo $safeDesc; ?></p>
                        <?php endif; ?>
                    </div><!-- /.card-body -->

                </div><!-- /.card -->
            </li>
            <?php endforeach; ?>
        </ul><!-- /.track -->
    </div><!-- /.viewport -->

    <!-- Controls -->
    <div class="mod-yt-carousel__controls" aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_CONTROLS_ARIA'); ?>">
        <button class="mod-yt-carousel__btn mod-yt-carousel__btn--prev" type="button" aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_PREV'); ?>" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
        </button>

        <!-- Dot indicators -->
        <div class="mod-yt-carousel__dots" role="tablist" aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_DOTS_ARIA'); ?>">
            <?php foreach ($videos as $index => $video) : ?>
            <button
                class="mod-yt-carousel__dot<?php echo $index === 0 ? ' is-active' : ''; ?>"
                type="button"
                role="tab"
                aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                aria-label="<?php echo Text::sprintf('MOD_CCPBIOSIM_YOUTUBE_DOT_ARIA', $index + 1); ?>"
                data-index="<?php echo $index; ?>"
            ></button>
            <?php endforeach; ?>
        </div>

        <button class="mod-yt-carousel__btn mod-yt-carousel__btn--next" type="button" aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_NEXT'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M10 6 8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
        </button>
    </div><!-- /.controls -->

</div><!-- /.mod-yt-carousel -->

<!-- Lightbox overlay for embedded player -->
<div id="<?php echo $uid; ?>-lightbox" class="mod-yt-carousel__lightbox" role="dialog" aria-modal="true" aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_LIGHTBOX_ARIA'); ?>" hidden>
    <div class="mod-yt-carousel__lightbox-inner">
        <button class="mod-yt-carousel__lightbox-close" type="button" aria-label="<?php echo Text::_('MOD_CCPBIOSIM_YOUTUBE_CLOSE'); ?>">&#x2715;</button>
        <div class="mod-yt-carousel__lightbox-player">
            <iframe id="<?php echo $uid; ?>-iframe"
                src=""
                title=""
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                loading="lazy"
            ></iframe>
        </div>
    </div>
</div>

<style>
/* ── mod_ccpbiosim_youtube styles ──────────────────────────────────────────────── */
#<?php echo $uid; ?> {
    --ytc-accent:     #ff0000;
    --ytc-bg:         #1a1a2e;
    --ytc-card-bg:    #16213e;
    --ytc-card-hover: #0f3460;
    --ytc-text:       #e0e0e0;
    --ytc-muted:      #9e9e9e;
    --ytc-radius:     12px;
    --ytc-gap:        1.5rem;
    --ytc-transition: 0.45s cubic-bezier(.4,0,.2,1);
    font-family: inherit;
    position: relative;
}

.mod-yt-carousel__viewport {
    overflow: hidden;
    border-radius: var(--ytc-radius);
}

.mod-yt-carousel__track {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    transition: transform var(--ytc-transition);
    will-change: transform;
}

.mod-yt-carousel__slide {
    flex: 0 0 100%;
    width: 100%;
    box-sizing: border-box;
    padding: 0;
}

.mod-yt-carousel__card {
    background: var(--ytc-card-bg);
    border-radius: var(--ytc-radius);
    overflow: hidden;
    transition: background var(--ytc-transition), box-shadow var(--ytc-transition);
    box-shadow: 0 4px 20px rgba(0,0,0,.35);
}

.mod-yt-carousel__card:hover {
    background: var(--ytc-card-hover);
    box-shadow: 0 8px 32px rgba(0,0,0,.5);
}

/* Thumbnail */
.mod-yt-carousel__thumb-wrap {
    position: relative;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    background: #000;
}

.mod-yt-carousel__thumb {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform var(--ytc-transition), opacity var(--ytc-transition);
}

.mod-yt-carousel__card:hover .mod-yt-carousel__thumb {
    transform: scale(1.04);
    opacity: .85;
}

/* Play button */
.mod-yt-carousel__play-btn {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
}

.mod-yt-carousel__play-btn svg {
    width: 72px;
    height: 51px;
    filter: drop-shadow(0 2px 8px rgba(0,0,0,.5));
    transition: transform .2s ease, filter .2s ease;
}

.mod-yt-carousel__play-btn:hover svg,
.mod-yt-carousel__play-btn:focus-visible svg {
    transform: scale(1.1);
    filter: drop-shadow(0 4px 16px rgba(0,0,0,.7));
}

.mod-yt-carousel__play-bg  { fill: rgba(30,30,30,.82); transition: fill .2s; }
.mod-yt-carousel__play-btn:hover .mod-yt-carousel__play-bg { fill: var(--ytc-accent); }
.mod-yt-carousel__play-arrow { fill: #fff; }

/* Card body */
.mod-yt-carousel__body {
    padding: 1rem 1.25rem 1.25rem;
}

.mod-yt-carousel__title {
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 .5rem;
    line-height: 1.4;
    color: var(--ytc-text);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.mod-yt-carousel__title a {
    color: inherit;
    text-decoration: none;
}

.mod-yt-carousel__title a:hover,
.mod-yt-carousel__title a:focus {
    color: var(--ytc-accent);
    text-decoration: underline;
}

.mod-yt-carousel__desc {
    font-size: .85rem;
    color: var(--ytc-muted);
    margin: 0;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Controls */
.mod-yt-carousel__controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .75rem;
    margin-top: 1rem;
}

.mod-yt-carousel__btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--ytc-card-bg);
    border: 2px solid transparent;
    cursor: pointer;
    transition: background var(--ytc-transition), border-color var(--ytc-transition);
    color: var(--ytc-text);
    flex-shrink: 0;
}

.mod-yt-carousel__btn svg {
    width: 20px;
    height: 20px;
    fill: currentColor;
}

.mod-yt-carousel__btn:hover:not(:disabled) {
    background: var(--ytc-card-hover);
    border-color: var(--ytc-accent);
}

.mod-yt-carousel__btn:focus-visible {
    outline: 3px solid var(--ytc-accent);
    outline-offset: 2px;
}

.mod-yt-carousel__btn:disabled {
    opacity: .35;
    cursor: not-allowed;
}

/* Dots */
.mod-yt-carousel__dots {
    display: flex;
    gap: .45rem;
    flex-wrap: wrap;
    justify-content: center;
}

.mod-yt-carousel__dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid var(--ytc-muted);
    background: transparent;
    cursor: pointer;
    padding: 0;
    transition: background var(--ytc-transition), border-color var(--ytc-transition), transform var(--ytc-transition);
}

.mod-yt-carousel__dot.is-active,
.mod-yt-carousel__dot:hover {
    background: var(--ytc-accent);
    border-color: var(--ytc-accent);
    transform: scale(1.3);
}

.mod-yt-carousel__dot:focus-visible {
    outline: 2px solid var(--ytc-accent);
    outline-offset: 3px;
}

/* Lightbox */
.mod-yt-carousel__lightbox {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0,0,0,.88);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.mod-yt-carousel__lightbox[hidden] { display: none; }

.mod-yt-carousel__lightbox-inner {
    position: relative;
    width: 100%;
    max-width: 900px;
}

.mod-yt-carousel__lightbox-close {
    position: absolute;
    top: -2.5rem;
    right: 0;
    background: transparent;
    border: none;
    color: #fff;
    font-size: 1.5rem;
    cursor: pointer;
    padding: .25rem .5rem;
    line-height: 1;
    transition: color .2s;
}

.mod-yt-carousel__lightbox-close:hover { color: var(--ytc-accent); }

.mod-yt-carousel__lightbox-player {
    position: relative;
    padding-top: 56.25%; /* 16:9 */
    background: #000;
    border-radius: var(--ytc-radius);
    overflow: hidden;
}

.mod-yt-carousel__lightbox-player iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: none;
}

/* Error message */
.mod-yt-carousel__error {
    color: var(--ytc-muted, #9e9e9e);
    font-style: italic;
    padding: 1rem;
    text-align: center;
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    .mod-yt-carousel__track,
    .mod-yt-carousel__thumb,
    .mod-yt-carousel__card,
    .mod-yt-carousel__btn,
    .mod-yt-carousel__dot {
        transition: none !important;
    }
}
</style>

<script>
(function () {
    'use strict';

    const carousel = document.getElementById('<?php echo $uid; ?>');
    if (!carousel) return;

    const track    = carousel.querySelector('.mod-yt-carousel__track');
    const slides   = Array.from(carousel.querySelectorAll('.mod-yt-carousel__slide'));
    const dots     = Array.from(carousel.querySelectorAll('.mod-yt-carousel__dot'));
    const btnPrev  = carousel.querySelector('.mod-yt-carousel__btn--prev');
    const btnNext  = carousel.querySelector('.mod-yt-carousel__btn--next');
    const lightbox = document.getElementById('<?php echo $uid; ?>-lightbox');
    const iframe   = document.getElementById('<?php echo $uid; ?>-iframe');
    const closeBtn = lightbox ? lightbox.querySelector('.mod-yt-carousel__lightbox-close') : null;

    let current  = 0;
    let autoTimer = null;
    const total  = slides.length;
    const doAuto = carousel.dataset.autoplay === 'true';
    const interval = parseInt(carousel.dataset.interval, 10) || 5000;

    function goTo(index) {
        const prev = current;
        current = (index + total) % total;

        slides[prev].classList.remove('is-active');
        slides[prev].setAttribute('aria-hidden', 'true');
        slides[current].classList.add('is-active');
        slides[current].setAttribute('aria-hidden', 'false');

        dots[prev] && dots[prev].classList.remove('is-active');
        dots[prev] && dots[prev].setAttribute('aria-selected', 'false');
        dots[current] && dots[current].classList.add('is-active');
        dots[current] && dots[current].setAttribute('aria-selected', 'true');

        track.style.transform = `translateX(-${current * 100}%)`;

        btnPrev.disabled = false;
        btnNext.disabled = false;
    }

    // Navigation
    btnPrev && btnPrev.addEventListener('click', () => { goTo(current - 1); resetAutoplay(); });
    btnNext && btnNext.addEventListener('click', () => { goTo(current + 1); resetAutoplay(); });

    dots.forEach(dot => {
        dot.addEventListener('click', () => { goTo(parseInt(dot.dataset.index, 10)); resetAutoplay(); });
    });

    // Keyboard on carousel
    carousel.addEventListener('keydown', e => {
        if (e.key === 'ArrowLeft')  { goTo(current - 1); resetAutoplay(); }
        if (e.key === 'ArrowRight') { goTo(current + 1); resetAutoplay(); }
    });

    // Touch / swipe
    let touchStartX = 0;
    carousel.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].clientX; }, { passive: true });
    carousel.addEventListener('touchend', e => {
        const dx = e.changedTouches[0].clientX - touchStartX;
        if (Math.abs(dx) > 40) {
            goTo(dx < 0 ? current + 1 : current - 1);
            resetAutoplay();
        }
    }, { passive: true });

    // Autoplay
    function startAutoplay() {
        if (!doAuto) return;
        autoTimer = setInterval(() => goTo(current + 1), interval);
    }

    function resetAutoplay() {
        clearInterval(autoTimer);
        startAutoplay();
    }

    // Pause on hover / focus
    carousel.addEventListener('mouseenter', () => clearInterval(autoTimer));
    carousel.addEventListener('mouseleave', startAutoplay);
    carousel.addEventListener('focusin',   () => clearInterval(autoTimer));
    carousel.addEventListener('focusout',  startAutoplay);

    // Lightbox
    carousel.querySelectorAll('.mod-yt-carousel__play-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!lightbox || !iframe) return;
            const embedBase = btn.dataset.embed;
            iframe.src   = embedBase + '?autoplay=1&rel=0';
            iframe.title = btn.dataset.title || '';
            lightbox.hidden = false;
            lightbox.focus();
            clearInterval(autoTimer);
        });
    });

    function closeLightbox() {
        if (!lightbox || !iframe) return;
        iframe.src = '';
        lightbox.hidden = true;
        startAutoplay();
    }

    closeBtn && closeBtn.addEventListener('click', closeLightbox);

    lightbox && lightbox.addEventListener('click', e => {
        if (e.target === lightbox) closeLightbox();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && lightbox && !lightbox.hidden) closeLightbox();
    });

    // Init
    goTo(0);
    startAutoplay();
}());
</script>
