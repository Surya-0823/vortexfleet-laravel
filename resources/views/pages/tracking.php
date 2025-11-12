<?php 
// PUTHU MAATRAM: 'header.php'-ah remove pannittom
?>

<?php // Namma content inga thaan start aaguthu nu solrom ?>
<?php $__env->startSection('content'); ?>

<div id="alert-container" class="alert-container-global"></div>

<?php // PUTHU MAATRAM: Leaflet CSS link-ah inga move panrom ?>
<link 
  rel="stylesheet" 
  href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" 
/>

<div class="page-header tracking-page-header">
  <div class="header-left-content">
    <h1 class="page-title">
      <?= $page_title ?? 'Real-time Tracking Map'; ?>
    </h1>
    <p class="page-subtitle">
      <?= $page_subtitle ?? 'Monitor all active buses on the map.'; ?>
    </p>
    <p class="page-header-note">
      Map uses OpenStreetMap and Leaflet.js. Locations are updated every 5 seconds by the driver app.
    </p>
  </div>

  <div class="header-right-actions">
    <a
      href="javascript:void(0);"
      id="map-refresh-btn"
      class="add-button"
      style="
        background: hsl(var(--secondary));
        color: hsl(var(--secondary-foreground));
        box-shadow: 0 4px 15px hsla(var(--secondary), 0.5);
      "
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="16"
        height="16"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="lucide lucide-rotate-cw"
      >
        <path d="M21 12a9 9 0 1 1-9-9c2.3 3.1 3.9 3.9 3.9 3.9" />
        <path d="M10 16v-4h4" />
      </svg>
      <span>Refresh Map</span>
    </a>
  </div>
</div>

<?php // --- PUTHU MAATRAM INGA START AAGUTHU --- ?>
<div class="tracking-body-container">
  
  <?php // Ithu namma pazhaya map container ?>
  <div id="map-container"></div>

  <?php // Ithu namma puthusa add panna list sidebar ?>
  <aside id="bus-list-sidebar">
    <div class="bus-list-header">
      <h3>Active Buses</h3>
      <span id="bus-count-badge">0 Buses</span>
    </div>
    <div id="bus-list-content">
      <?php // Inga namma bus list-ah JS moolama load pannuvom ?>
      <div class="bus-list-empty">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
        </svg>
        <span>Loading locations...</span>
      </div>
    </div>
  </aside>

</div>
<?php // --- PUTHU MAATRAM MUDINJATHU --- ?>


<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<?php $__env->stopSection(); ?>

<?php 
// PUTHU MAATRAM: Namma 'master.php' layout-ah inga render panrom
// 'footer.php'-ah remove pannittom
echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); 
?>