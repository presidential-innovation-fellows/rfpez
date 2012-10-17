<tbody class="bid" data-project-id="<?php echo Jade\Dumper::_text($bid->project->id); ?>" data-bid-id="<?php echo Jade\Dumper::_text($bid->id); ?>" data-vendor-company-name="<?php echo Jade\Dumper::_text($bid->vendor->company_name); ?>">
  <tr>
    <td>
      <a data-toggle="collapse" data-target="#bid<?php echo Jade\Dumper::_text($bid->id); ?>"><?php echo Jade\Dumper::_text($bid->vendor->company_name); ?></a>
      <?php if ($bid->awarded_at): ?>
        <span class="label label-success">Winning Bid!</span>
      <?php endif; ?>
    </td>
    <td>$<?php echo Jade\Dumper::_text(intval($bid->total_price())); ?></td>
    <td class="star-td <?php echo Jade\Dumper::_text($bid->starred ? 'starred' : ''); ?>">
      <a class="btn btn-inverse unstar-button">Starred</a>
      <a class="btn star-button">Star</a>
    </td>
    <td>
      <?php if (Auth::officer()->is_verified_contracting_officer()): ?>
        <?php if (!$bid->awarded_at): ?>
          <?php if($bid->dismissed()): ?>
            <a class="btn btn-info undismiss-button" data-move-to-table="true">Un-dismiss</a>
            <div>
              <em>Dismissed: <?php echo Jade\Dumper::_text($bid->dismissal_reason); ?></em>
            </div>
          <?php else: ?>
            <a class="btn btn-warning show-dismiss-modal" data-move-to-table="true">Dismiss?</a>
            <?php if (!$bid->project->winning_bid()): ?>
              <a class="btn btn-primary show-award-modal" data-move-to-table="true">Award</a>
            <?php endif; ?>
          <?php endif; ?>
        <?php else: ?>
          Congrats on finding a great bid!
        <?php endif; ?>
      <?php else: ?>
        Only COs can dismiss bids.
      <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td class="bid-details" colspan="4">
      <div id="bid<?php echo Jade\Dumper::_text($bid->id); ?>" class="collapse">
        <?php echo Jade\Dumper::_html(View::make('bids.partials.bid_details_officers_view')->with('bid', $bid)); ?>
      </div>
    </td>
  </tr>
</tbody>