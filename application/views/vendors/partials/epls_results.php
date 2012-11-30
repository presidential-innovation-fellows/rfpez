<!-- shouldn't need this ifelse, other than to account for legacy data -->
<?php if ($bid->epls_names): ?>
  <table class="table epls-lookups">
    <thead>
      <tr>
        <th>Name</th>
        <th>EPLS</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($bid->epls_names as $name => $epls_match) { ?>
        <tr>
          <td><?php echo e($name); ?></td>
          <?php if ($epls_match): ?>
            <td class="red">
              <a href="http://rfpez-apis.presidentialinnovationfellows.org/exclusions?name=<?php echo e(rawurlencode($name)); ?>">yes</a>
            </td>
          <?php else: ?>
            <td class="green">
              <a href="http://rfpez-apis.presidentialinnovationfellows.org/exclusions?name=<?php echo e(rawurlencode($name)); ?>">no</a>
            </td>
          <?php endif; ?>
        </tr>
      <?php } ?>
    </tbody>
  </table>
<?php elseif ($bid->employee_details): ?>
  <p><?php echo $bid->employee_details; ?></p>
<?php endif; ?>