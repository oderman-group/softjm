  </div> <!-- Cierre de modern-main-content -->
</div> <!-- Cierre de modern-layout -->

<!-- Modern Core JavaScript -->
<script src="modern-ui/js/modern-core.js"></script>

<?php if(isset($footerJS) && is_array($footerJS)): ?>
  <!-- JavaScript adicional del footer -->
  <?php foreach($footerJS as $js): ?>
    <script src="<?= $js ?>"></script>
  <?php endforeach; ?>
<?php endif; ?>

<?php if(isset($inlineScript)): ?>
  <!-- JavaScript inline de la página -->
  <script>
    <?= $inlineScript ?>
  </script>
<?php endif; ?>

</body>
</html>

