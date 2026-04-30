<?php
/**
 * @package    com_ccpbiosim
 * @copyright  2025 CCPBioSim Team
 * @license    MIT
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/** @var \Ccpbiosim\Component\Ccpbiosim\Site\View\Statistics\HtmlView $this */

$data       = $this->statisticsData;
$events     = $data['events'];
$containers = $data['containers'];
$software   = $data['software'];

// --------------------------------------------------------------------------
// Pre-process event data for Plotly series
// --------------------------------------------------------------------------

$years      = $events['years'];
$categories = $events['categories'];
$byYear     = $events['byYear'];
$byCategory = $events['byCategory'];

// Map category names to Bootstrap bg-* CSS classes.
// Colours are resolved at runtime in JS from the live computed CSS,
// so site theme overrides are respected automatically.
$catCssClasses = [
    'Conferences'        => 'bg-primary',
    'Training Workshops' => 'bg-success',
    'Webinars'           => 'bg-danger',
];
$catFallbackClasses = ['bg-warning', 'bg-info', 'bg-secondary'];

$eventsPerYearSeries     = [];
$attendancePerYearSeries = [];
$fi = 0;
foreach ($categories as $catId => $catName) {
    $cssClass = $catCssClasses[$catName]
        ?? $catFallbackClasses[$fi++ % count($catFallbackClasses)];

    $countSeries = [];
    $attSeries   = [];
    foreach ($years as $year) {
        $countSeries[] = $byYear[$year][$catId]['count']      ?? 0;
        $attSeries[]   = $byYear[$year][$catId]['attendance'] ?? 0;
    }
    $eventsPerYearSeries[]     = ['name' => $catName, 'data' => $countSeries, 'cssClass' => $cssClass];
    $attendancePerYearSeries[] = ['name' => $catName, 'data' => $attSeries,   'cssClass' => $cssClass];
}

$jsYears    = json_encode(array_values($years));
$jsCatNames = json_encode(array_values($categories));

$jsEventsPerYear = json_encode($eventsPerYearSeries);
$jsAttPerYear    = json_encode($attendancePerYearSeries);

// Pie chart data — CSS class included so JS can resolve the live colour
$pieCatLabels    = [];
$pieCatValues    = [];
$pieCatClasses   = [];
$pieAttLabels    = [];
$pieAttValues    = [];
$fi2 = 0;
foreach ($byCategory as $catId => $cat) {
    $cssClass          = $catCssClasses[$cat['name']]
        ?? $catFallbackClasses[$fi2++ % count($catFallbackClasses)];
    $pieCatLabels[]    = $cat['name'];
    $pieCatValues[]    = $cat['count'];
    $pieCatClasses[]   = $cssClass;
    $pieAttLabels[]    = $cat['name'];
    $pieAttValues[]    = round($cat['attendance']);
}
$jsPieCatLabels  = json_encode($pieCatLabels);
$jsPieCatValues  = json_encode($pieCatValues);
$jsPieCatClasses = json_encode($pieCatClasses);
$jsPieAttLabels  = json_encode($pieAttLabels);
$jsPieAttValues  = json_encode($pieAttValues);

$conCatLabels = json_encode(array_keys($containers['byCategory']));
$conCatValues = json_encode(array_values($containers['byCategory']));

function fmt_date(?string $iso): string {
    if (!$iso) return '—';
    return date('j M Y', strtotime($iso));
}
?>

<?php
// FIX: Load Plotly WITHOUT defer so it is available when the inline script runs.
// Using addScript with no attributes ensures synchronous loading.
$this->document->addScript('https://cdn.plot.ly/plotly-2.32.0.min.js', ['version' => false]);
?>

<?php if ($this->params->get('show_page_heading')) : ?>
<div class="page-header">
    <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
</div>
<?php endif; ?>

<div class="ccpbiosim-statistics py-4">

    <!-- ====================================================================
         SECTION 1 — EVENTS
    ===================================================================== -->
    <div class="container-fluid mb-5">

        <h2 class="h4 fw-semibold mb-4">
            <span class="badge bg-primary me-2">Events</span>
            All-Time Event Statistics
        </h2>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="display-6 fw-bold text-primary">
                            <?php echo number_format($events['allTime']['count']); ?>
                        </div>
                        <div class="text-muted small mt-1">Total Events</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="display-6 fw-bold text-success">
                            <?php echo number_format($events['allTime']['attendance']); ?>
                        </div>
                        <div class="text-muted small mt-1">Total Attendees</div>
                    </div>
                </div>
            </div>
            <?php foreach ($byCategory as $catId => $cat) :
                $kpiClass = $catCssClasses[$cat['name']] ?? 'bg-secondary';
                // Convert bg-* to text-* for Bootstrap text colouring on white cards
                $kpiTextClass = str_replace('bg-', 'text-', $kpiClass);
            ?>
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="display-6 fw-bold <?php echo $kpiTextClass; ?>">
                            <?php echo number_format($cat['count']); ?>
                        </div>
                        <div class="text-muted small mt-1"><?php echo htmlspecialchars($cat['name']); ?></div>
                        <div class="text-muted" style="font-size:.75rem">
                            <?php echo number_format($cat['attendance']); ?> attendees
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-semibold">Events by Category (All Time)</div>
                    <div class="card-body">
                        <div id="chart-events-pie" style="height:300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-semibold">Attendance by Category (All Time)</div>
                    <div class="card-body">
                        <div id="chart-attendance-pie" style="height:300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($years)) : ?>
        <div class="row g-3 mb-2">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-semibold">Events per Year by Category</div>
                    <div class="card-body">
                        <div id="chart-events-year" style="height:320px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-semibold">Attendance per Year by Category</div>
                    <div class="card-body">
                        <div id="chart-attendance-year" style="height:320px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php else : ?>
        <div class="alert alert-info">No dated events found — per-year charts will appear once events with dates are added.</div>
        <?php endif; ?>

    </div>

    <!-- ====================================================================
         SECTION 2 — TRAINING COURSES
    ===================================================================== -->
    <div class="container-fluid mb-5">

        <h2 class="h4 fw-semibold mb-4">
            <span class="badge bg-warning text-dark me-2">Training</span>
            Training Course Containers
        </h2>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body">
                        <div class="display-6 fw-bold text-warning">
                            <?php echo (int) $containers['total']; ?>
                        </div>
                        <div class="text-muted small mt-1">Total Containers</div>
                    </div>
                </div>
            </div>
            <?php foreach ($containers['byCategory'] as $catName => $catCount) : ?>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body">
                        <div class="display-6 fw-bold text-secondary"><?php echo $catCount; ?></div>
                        <div class="text-muted small mt-1"><?php echo htmlspecialchars(ucfirst($catName)); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="row g-3">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-semibold">Containers by Category</div>
                    <div class="card-body">
                        <div id="chart-containers-bar" style="height:280px;"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ====================================================================
         SECTION 3 — SOFTWARE PACKAGES
    ===================================================================== -->
    <div class="container-fluid mb-5">

        <h2 class="h4 fw-semibold mb-4">
            <span class="badge bg-success me-2">Software</span>
            Actively Maintained Software Packages
            <span class="badge bg-secondary ms-2"><?php echo count($software); ?></span>
        </h2>

        <div class="row g-4">
            <?php foreach ($software as $pkg) :
                $m        = $pkg['github_metrics'];
                $lastPush = $m['last_push']           ?? null;
                $latestRel   = $m['latest_release']      ?? null;
                $relDate     = $m['latest_release_date'] ?? null;
                $stars       = $m['stars']               ?? 0;
                $forks       = $m['forks']               ?? 0;
                $issues      = $m['open_issues']         ?? 0;
                $watchers    = $m['watchers']            ?? 0;
                $repoUrl     = $m['html_url']            ?? $pkg['github'];
            ?>
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white d-flex align-items-center gap-2">
                        <span class="fw-semibold"><?php echo htmlspecialchars($pkg['name']); ?></span>
                        <span class="badge bg-light text-dark border ms-auto">
                            <?php echo htmlspecialchars($pkg['category']); ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted small mb-3">
                            <?php echo htmlspecialchars($pkg['shortdesc']); ?>
                        </p>
                        <?php if (!empty($m)) : ?>
                        <div class="row row-cols-2 g-2 mb-3">
                            <div class="col">
                                <div class="d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#f5a623" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>
                                    <span class="fw-semibold"><?php echo number_format($stars); ?></span>
                                    <span class="text-muted small">Stars</span>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#6c757d" viewBox="0 0 16 16"><path d="M5 5.372v.878c0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75v-.878a2.25 2.25 0 1 1 1.5 0v.878a2.25 2.25 0 0 1-2.25 2.25h-1.5v2.128a2.251 2.251 0 1 1-1.5 0V8.5h-1.5A2.25 2.25 0 0 1 3.5 6.25v-.878a2.25 2.25 0 1 1 1.5 0ZM5 3.25a.75.75 0 1 0-1.5 0 .75.75 0 0 0 1.5 0zm6.75.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5zm-3 8.75a.75.75 0 1 0-1.5 0 .75.75 0 0 0 1.5 0z"/></svg>
                                    <span class="fw-semibold"><?php echo number_format($forks); ?></span>
                                    <span class="text-muted small">Forks</span>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#dc3545" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/></svg>
                                    <span class="fw-semibold"><?php echo number_format($issues); ?></span>
                                    <span class="text-muted small">Open Issues</span>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#0d6efd" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>
                                    <span class="fw-semibold"><?php echo number_format($watchers); ?></span>
                                    <span class="text-muted small">Watchers</span>
                                </div>
                            </div>
                        </div>
                        <ul class="list-unstyled small text-muted mb-3">
                            <?php if ($latestRel) : ?>
                            <li>
                                <strong>Latest release:</strong>
                                <?php echo htmlspecialchars($latestRel); ?>
                                <?php if ($relDate) : ?>
                                    <span class="text-muted">(<?php echo fmt_date($relDate); ?>)</span>
                                <?php endif; ?>
                            </li>
                            <?php endif; ?>
                            <?php if ($lastPush) : ?>
                            <li><strong>Last push:</strong> <?php echo fmt_date($lastPush); ?></li>
                            <?php endif; ?>
                        </ul>
                        <?php else : ?>
                        <p class="text-muted small">GitHub metrics unavailable.</p>
                        <?php endif; ?>
                        <div class="d-flex flex-wrap gap-1">
                            <?php if ($pkg['pypi']) : ?>
                            <span class="badge bg-primary">PyPI</span>
                            <?php endif; ?>
                            <?php if ($pkg['conda']) : ?>
                            <span class="badge bg-success">Conda</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex gap-2">
                        <?php if (!empty($repoUrl)) : ?>
                        <a href="<?php echo htmlspecialchars($repoUrl); ?>" class="btn btn-sm btn-outline-dark" target="_blank" rel="noopener noreferrer">GitHub</a>
                        <?php endif; ?>
                        <?php if (!empty($pkg['docs'])) : ?>
                        <a href="<?php echo htmlspecialchars($pkg['docs']); ?>" class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener noreferrer">Docs</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>

</div><!-- /.ccpbiosim-statistics -->

<!-- ======================================================================
     PLOTLY CHART INITIALISATION
     FIX: Plotly is loaded synchronously above, so it is guaranteed to exist
     by the time this script runs. We still wrap in DOMContentLoaded to ensure
     the chart div elements are in the DOM.
====================================================================== -->
<script>
(function () {
    'use strict';

    /**
     * Resolve the background-color of a Bootstrap utility class by briefly
     * injecting a hidden element, reading its computed style, then removing it.
     * This means Plotly always uses whatever colour your site CSS defines for
     * bg-primary / bg-success / bg-danger etc., even if they are overridden.
     */
    function cssClassToColour(cls) {
        const el = document.createElement('div');
        el.className = cls;
        el.style.cssText = 'position:absolute;width:1px;height:1px;opacity:0;pointer-events:none';
        document.body.appendChild(el);
        const colour = getComputedStyle(el).backgroundColor;
        document.body.removeChild(el);
        return colour;
    }

    function initCharts() {
        const baseLayout = {
            margin:  { t: 20, r: 20, b: 40, l: 50 },
            paper_bgcolor: 'rgba(0,0,0,0)',
            plot_bgcolor:  'rgba(0,0,0,0)',
            font:    { family: 'inherit', size: 12 },
            legend:  { orientation: 'h', y: -0.2 },
        };
        const config = { responsive: true, displayModeBar: false };

        const years             = <?php echo $jsYears; ?>;
        const catNames          = <?php echo $jsCatNames; ?>;
        const eventsPerYear     = <?php echo $jsEventsPerYear; ?>;
        const attendancePerYear = <?php echo $jsAttPerYear; ?>;
        const pieCatLabels      = <?php echo $jsPieCatLabels; ?>;
        const pieCatValues      = <?php echo $jsPieCatValues; ?>;
        const pieCatClasses     = <?php echo $jsPieCatClasses; ?>;
        const pieAttLabels      = <?php echo $jsPieAttLabels; ?>;
        const pieAttValues      = <?php echo $jsPieAttValues; ?>;
        const conCatLabels      = <?php echo $conCatLabels; ?>;
        const conCatValues      = <?php echo $conCatValues; ?>;

        // Resolve all category colours from live CSS now, once
        const pieCatColrs = pieCatClasses.map(cssClassToColour);
        eventsPerYear.forEach(s     => { s.colour = cssClassToColour(s.cssClass); });
        attendancePerYear.forEach(s => { s.colour = cssClassToColour(s.cssClass); });

        // Chart 1: Events by category (pie)
        const elEventsPie = document.getElementById('chart-events-pie');
        if (elEventsPie && pieCatValues.length) {
            Plotly.newPlot(elEventsPie, [{
                type: 'pie', hole: 0.4,
                labels: pieCatLabels, values: pieCatValues,
                marker: { colors: pieCatColrs },
                textinfo: 'label+percent',
            }], { ...baseLayout, showlegend: false }, config);
        }

        // Chart 2: Attendance by category (pie)
        const elAttPie = document.getElementById('chart-attendance-pie');
        if (elAttPie && pieAttValues.length) {
            Plotly.newPlot(elAttPie, [{
                type: 'pie', hole: 0.4,
                labels: pieAttLabels, values: pieAttValues,
                marker: { colors: pieCatColrs },
                textinfo: 'label+percent',
            }], { ...baseLayout, showlegend: false }, config);
        }

        // Bar charts need extra bottom margin and a lower legend y so the
        // legend doesn't overlap the "Year" x-axis title.
        const barLayout = {
            ...baseLayout,
            margin: { t: 20, r: 20, b: 80, l: 50 },
            legend: { orientation: 'h', y: -0.35 },
        };

        // Chart 3: Events per year (grouped bar)
        const elEventsYear = document.getElementById('chart-events-year');
        if (elEventsYear && years.length) {
            Plotly.newPlot(elEventsYear,
                eventsPerYear.map(s => ({
                    name: s.name, x: years, y: s.data,
                    type: 'bar', marker: { color: s.colour },
                })),
                { ...barLayout, barmode: 'group',
                  xaxis: { title: 'Year', tickformat: 'd' },
                  yaxis: { title: 'Events', dtick: 1 } },
                config
            );
        }

        // Chart 4: Attendance per year (grouped bar)
        const elAttYear = document.getElementById('chart-attendance-year');
        if (elAttYear && years.length) {
            Plotly.newPlot(elAttYear,
                attendancePerYear.map(s => ({
                    name: s.name, x: years, y: s.data,
                    type: 'bar', marker: { color: s.colour },
                })),
                { ...barLayout, barmode: 'group',
                  xaxis: { title: 'Year', tickformat: 'd' },
                  yaxis: { title: 'Attendees' } },
                config
            );
        }

        // Chart 5: Containers by category (horizontal bar)
        const elContainers = document.getElementById('chart-containers-bar');
        if (elContainers && conCatValues.length) {
            Plotly.newPlot(elContainers, [{
                type: 'bar', orientation: 'h',
                x: conCatValues,
                y: conCatLabels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                marker: { color: '#fd7e14' },
                text: conCatValues, textposition: 'auto',
            }], {
                ...baseLayout,
                margin: { t: 20, r: 40, b: 40, l: 100 },
                xaxis: { title: 'Count', dtick: 1 },
                yaxis: { automargin: true },
            }, config);
        }
    }

    // Plotly is synchronous so it is already defined here, but the chart
    // div elements may not yet be painted. DOMContentLoaded is the safest hook.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCharts);
    } else {
        // DOM already ready (script is in body, after the divs)
        initCharts();
    }
})();
</script>
