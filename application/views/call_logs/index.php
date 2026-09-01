<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-6"><h4>Recent Call Logs</h4></div>
        </div>
      </div>

      <div class="card-box mb-20">
        <div class="pd-20">
          <form id="filters" class="form-inline" onsubmit="return false;">
            <label class="mr-2">From</label>
            <input type="date" class="form-control mr-3" id="f-from" value="<?= html_escape($from) ?>">
            <label class="mr-2">To</label>
            <input type="date" class="form-control mr-3" id="f-to" value="<?= html_escape($to) ?>">
            <label class="mr-2">Direction</label>
            <select class="form-control mr-3" id="f-direction">
              <option value="">All</option>
              <option value="inbound">Inbound</option>
              <option value="outbound">Outbound</option>
            </select>
            <input type="text" class="form-control mr-2" id="f-status" placeholder="Status (e.g. answered)">
            <input type="text" class="form-control mr-2" id="f-agent" placeholder="Agent (name/email)">
            <input type="text" class="form-control mr-2" id="f-number" placeholder="Number">
            <button class="btn btn-primary" id="btn-apply">Apply</button>
            <button class="btn btn-light ml-2" id="btn-clear">Clear</button>
          </form>
        </div>
      </div>

      <div class="card-box mb-30">
        <div class="pb-20">
          <table id="logs" class="data-table table stripe hover nowrap" style="width:100%">
            <thead>
              <tr>
                <th>When</th>
                <th>Direction</th>
                <th>Status</th>
                <th>Number</th>
                <th>Agent</th>
                <th>Duration</th>
                <th>Recording</th>
                <th>Tags</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>

<?php $this->load->view('includes/footer'); ?>

<script>
// Server-side DataTables with filters
$(function(){
  var table = $('#logs').DataTable({
    processing: true,
    serverSide: true,
    searching: true,   // DT global search box
    order: [[0, 'desc']],
    ajax: {
      url: "<?= site_url('call-logs/data') ?>",
      type: "POST",
      data: function(d){
        d.from      = $('#f-from').val();
        d.to        = $('#f-to').val();
        d.direction = $('#f-direction').val();
        d.status    = $('#f-status').val();
        d.agent     = $('#f-agent').val();
        d.number    = $('#f-number').val();
      }
    },
    // enable export buttons if your assets are included
    dom: 'Bfrtip',
    buttons: ['copy','csv','excel','print'],
    columnDefs: [
      { targets: [6], orderable: false, searchable: false } // recording link
    ]
  });

  $('#btn-apply').on('click', function(){ table.ajax.reload(); });
  $('#btn-clear').on('click', function(){
    $('#filters').find('input[type="date"], input[type="text"]').val('');
    $('#f-direction').val('');
    table.ajax.reload();
  });
});
</script>
</html>
