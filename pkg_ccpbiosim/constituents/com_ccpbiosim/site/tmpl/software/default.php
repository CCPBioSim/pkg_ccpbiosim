<?php
/**
 * @package    com_ccpbiosim
 * @copyright  2025 CCPBioSim Team
 * @license    MIT
 */

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;

// Import CSS & JS
$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_ccpbiosim.site')
   ->useScript('com_ccpbiosim.site');
$json = "https://ccpbiosim.github.io/assets.json";
$data = json_decode(file_get_contents($json), true);
$data_sorted = array("scientific" => array("catname" => "Scientific Software"),
                     "data-tools" => array("catname" => "Data Tools"));
foreach ($data["software"] as $application => $applicationdata) {
  $data_sorted[$applicationdata["category"]]["software"][$application] = $applicationdata;
}
?>
<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
    </div>
<?php endif; ?>
<p>Our software packages that are actively maintained by our core CoSeC support team:</p>
<div class="container mt-5">
  <div class="software-category-bar text-center">
    <button class="btn btn-outline-primary software-category-btn active" data-category="all">All</button>
    <?php foreach ($data_sorted as $category => $categorydata) : ?>
      <button class="btn btn-outline-primary software-category-btn" data-category="<? echo $category; ?>"><?php echo $categorydata["catname"]; ?></button>
    <? endforeach ?>
  </div>
  <div class="row g-4">
    <?php foreach ($data_sorted as $category => $categorydata) : ?>
      <?php foreach ($categorydata["software"] as $app => $appdata) : ?>
        <div class="col-md-4 product"
          data-category="<?php echo $category; ?>"
          data-name="<?php echo $appdata["name"]; ?>"
          data-logo="/images/logos/<?php echo $appdata["image"]; ?>"
          data-summary="<?php echo $appdata["shortdesc"]; ?>"
          data-description="<?php echo $appdata["longdesc"]; ?>"
          data-pip="pip install <?php echo $app; ?>"
          data-conda="conda install -c CCPBioSim <?php echo $app; ?>"
          data-docs="<?php echo $appdata["docs"]; ?>"
          data-source="<?php echo $appdata["github"]; ?>"
          data-authors="Authors: <?php echo $appdata["authors"]; ?>">
          <div class="card software-card h-100"
            data-bs-toggle="modal"
            data-bs-target="#modal">
            <div class="card-body">
              <div class="software-logo-box"><img src="/images/logos/<?php echo $appdata["image"]; ?>"></div>
              <h5><?php echo $appdata["name"]; ?></h5>
              <p><?php echo $appdata["shortdesc"]; ?></p>
            </div>
          </div>
        </div>
      <? endforeach ?>
    <? endforeach ?>
  </div>
</div>
<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Software Package Name</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
          <div class="row">
            <div class="col-3">
              <img src="" id="modalLogo" class="img-fluid mb-3 software-modal-logo" alt="Software Logo">
            </div>
            <div class="col">
              <p id="modalSummary" class="fw-bold modalsoftware-summarytext"></p>
              <p id="modalAuthors" class="fw-bold modalsoftware-authors"></p>
            </div>
          </div>
        </div>
        <p id="modalDescription"></p>
        <p>Install by Pip</p>
        <code id="modalPip"></code>
        <p style="padding-top: 10px;">Install by Conda</p>
        <code id="modalConda"></code>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <a href="#" id="modalSource" target="_blank" class="btn btn-primary">Source Code</a>
        <a href="#" id="modalDocs" target="_blank" class="btn btn-success">Documentation</a>
      </div>
    </div>
  </div>
</div>

<script src="/media/vendor/bootstrap/js/modal.min.js?5.3.8" type="module"></script>

<script>
const products = document.querySelectorAll(".product");
const modalTitle = document.getElementById("modalTitle");
const modalSummary = document.getElementById("modalSummary");
const modalDescription = document.getElementById("modalDescription");
const modalLogo = document.getElementById("modalLogo");
const modalPip = document.getElementById("modalPip");
const modalConda = document.getElementById("modalConda");
const modalSource = document.getElementById("modalSource");
const modalDocs = document.getElementById("modalDocs");
const modalAuthors = document.getElementById("modalAuthors");

products.forEach(product => {
  product.addEventListener("click", () => {
    modalTitle.innerText = product.dataset.name;
    modalSummary.innerText = product.dataset.summary;
    modalDescription.innerText = product.dataset.description;
    modalLogo.src = product.dataset.logo;
    modalPip.innerText = product.dataset.pip;
    modalConda.innerText = product.dataset.conda;
    modalAuthors.innerText = product.dataset.authors;
    modalSource.href = product.dataset.source;
    modalDocs.href = product.dataset.docs;
  });
});

const categoryButtons = document.querySelectorAll(".software-category-btn");
let activeCategory = "all";
categoryButtons.forEach(btn => {
  btn.addEventListener("click", () => {
    categoryButtons.forEach(b=>b.classList.remove("active"));
    btn.classList.add("active");
    activeCategory = btn.dataset.category;
    filterProducts();
  });
});

function filterProducts(){
  products.forEach(product=>{
    const name = product.dataset.name.toLowerCase();
    const category = product.dataset.category;
    let show = true;
    if(activeCategory!="all" && category!=activeCategory) show=false;
    product.style.display = show ? "block" : "none";
  });
}
</script>
